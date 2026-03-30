<?php
declare(strict_types=1);

/**
 * Serves CHANGELOG.md for the modal (avoids relying on static .md delivery).
 */
$path = __DIR__ . '/CHANGELOG.md';
if (!is_readable($path)) {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Changelog not found.';
    exit;
}

header('Content-Type: text/markdown; charset=utf-8');
// Fresh changelog after edits; avoid long-lived CDN/browser caches on this dynamic endpoint.
header('Cache-Control: private, no-cache, must-revalidate');
readfile($path);
