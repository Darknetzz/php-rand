#!/usr/bin/env php
<?php
/**
 * Update GitHub release descriptions from CHANGELOG.md.
 *
 * Prerequisites:
 *   - GitHub CLI (gh) installed and authenticated: https://cli.github.com/
 *
 * Usage (from repo root):
 *   php scripts/update-release-descriptions.php
 *   php scripts/update-release-descriptions.php --dry-run   # print only, no API calls
 */

$repoRoot = dirname(__DIR__);
$changelogPath = $repoRoot . '/CHANGELOG.md';

if (!file_exists($changelogPath)) {
    fwrite(STDERR, "CHANGELOG.md not found.\n");
    exit(1);
}

$dryRun = in_array('--dry-run', $argv ?? [], true);
$content = file_get_contents($changelogPath);
if ($content === false) {
    fwrite(STDERR, "Could not read CHANGELOG.md.\n");
    exit(1);
}

// Find all version headers and extract body between them
if (!preg_match_all('/^## \[(v[\d.]+)\]\s*(?:\(([^)]*)\))?\s*$/m', $content, $matches, PREG_OFFSET_CAPTURE)) {
    fwrite(STDERR, "No version headers found in CHANGELOG.md.\n");
    exit(1);
}

$versions = [];
for ($i = 0; $i < count($matches[0]); $i++) {
    $version = $matches[1][$i][0];
    $date = isset($matches[2][$i][0]) ? $matches[2][$i][0] : '';
    $start = $matches[0][$i][1] + strlen($matches[0][$i][0]);
    $end = $i + 1 < count($matches[0]) ? $matches[0][$i + 1][1] : strlen($content);
    $body = trim(substr($content, $start, $end - $start));
    $versions[$version] = [
        'date' => $date,
        'body' => toReleaseMarkdown($body),
    ];
}

function toReleaseMarkdown(string $section): string {
    // Remove horizontal rules and leading/trailing ---
    $section = trim($section);
    $section = preg_replace('/^---\s*$/m', '', $section);
    // Unwrap <details>: keep only the inner markdown (content of .changelog-panel)
    $section = preg_replace('/<details[^>]*>.*?<summary[^>]*>.*?<\\/summary>\s*<div[^>]*>\s*/s', '', $section);
    $section = preg_replace('/\s*<\\/div>\s*<\\/details>/s', '', $section);
    return trim($section);
}

foreach ($versions as $tag => $data) {
    $notes = $data['body'];
    if ($notes === '') {
        echo "Skip {$tag}: no body\n";
        continue;
    }
    if ($dryRun) {
        echo "Would update release {$tag}\n";
        echo "---\n" . substr($notes, 0, 300) . (strlen($notes) > 300 ? "...\n\n" : "\n\n");
        continue;
    }
    $notesFile = $repoRoot . '/.release-notes-' . str_replace('.', '-', $tag) . '.md';
    file_put_contents($notesFile, $notes);
    $escaped = escapeshellarg($notesFile);
    $tagArg = escapeshellarg($tag);
    $cmd = "gh release edit {$tagArg} --notes-file {$escaped}";
    passthru($cmd, $code);
    @unlink($notesFile);
    if ($code !== 0) {
        echo "Skipped {$tag} (no release on GitHub)\n";
    } else {
        echo "Updated {$tag}\n";
    }
}

echo "\nDone.\n";
