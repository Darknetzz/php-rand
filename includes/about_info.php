<?php

declare(strict_types=1);

/**
 * Whether PHP is running inside a Docker container.
 */
function isRunningInDocker(): bool
{
    return is_readable('/.dockerenv');
}

/**
 * VERSION= from docker-image.config (tracked release label for non-container builds).
 */
function getDockerImageConfigVersion(): ?string
{
    $path = APP_ROOT . DIRSEP . 'docker-image.config';
    if (!is_readable($path)) {
        return null;
    }
    $content = file_get_contents($path);
    if ($content === false) {
        return null;
    }
    if (preg_match('/^VERSION=(.+)$/m', $content, $m)) {
        $version = trim($m[1]);
        return $version !== '' ? $version : null;
    }
    return null;
}

/**
 * True when CHANGELOG.md [Unreleased] has at least one list entry.
 */
function hasUnreleasedChangelogChanges(): bool
{
    $path = APP_ROOT . DIRSEP . 'CHANGELOG.md';
    if (!is_readable($path)) {
        return false;
    }
    $content = file_get_contents($path);
    if ($content === false) {
        return false;
    }
    if (!preg_match('/## \[Unreleased\]\s*\R(.*?)(?=\n## \[|\z)/s', $content, $m)) {
        return false;
    }
    return (bool) preg_match('/^- /m', $m[1]);
}

/**
 * Human-readable php-rand version for the About panel.
 */
function getPhpRandVersionLabel(): string
{
    $env = getenv('PHP_RAND_VERSION');
    if (is_string($env) && $env !== '') {
        return $env;
    }

    $configVersion = getDockerImageConfigVersion();
    if ($configVersion !== null) {
        if (hasUnreleasedChangelogChanges()) {
            return $configVersion . ' (unreleased changes)';
        }
        return $configVersion;
    }

    $latest = getLatestChangelogVersion();
    if ($latest !== null && !empty($latest['version'])) {
        return (string) $latest['version'];
    }

    return 'unknown';
}

/**
 * Extensions the app relies on; shown with loaded/missing status in About.
 *
 * @return array<string, string> extension name => short label
 */
function getAboutKeyExtensions(): array
{
    return [
        'gmp' => 'GMP (large integers, primes)',
        'gd' => 'GD (image / logo tools)',
        'mbstring' => 'Multibyte strings',
        'intl' => 'Internationalization',
        'openssl' => 'Cryptography',
        'zip' => 'ZIP archives',
        'xml' => 'XML parsing',
        'dom' => 'DOM / XML',
        'mysqli' => 'MySQL (mysqli)',
        'pdo' => 'PDO',
        'pdo_mysql' => 'PDO MySQL',
        'curl' => 'HTTP client',
        'json' => 'JSON',
        'yaml' => 'YAML (native)',
        'sodium' => 'Sodium crypto',
        'opcache' => 'OPcache',
    ];
}

/**
 * Server/runtime details for the About modal (JSON-serializable).
 *
 * @return array<string, mixed>
 */
function getAboutInfo(): array
{
    $loaded = array_map('strtolower', get_loaded_extensions());
    $loadedSet = array_flip($loaded);

    $keyExtensions = [];
    foreach (getAboutKeyExtensions() as $ext => $label) {
        $keyExtensions[] = [
            'name' => $ext,
            'label' => $label,
            'loaded' => isset($loadedSet[strtolower($ext)]),
        ];
    }

    $allLoaded = $loaded;
    sort($allLoaded, SORT_STRING);

    $dockerImageVersion = null;
    if (isRunningInDocker()) {
        $env = getenv('PHP_RAND_VERSION');
        if (is_string($env) && $env !== '') {
            $dockerImageVersion = $env;
        }
    }

    $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? null;
    if (!is_string($serverSoftware) || trim($serverSoftware) === '') {
        $serverSoftware = null;
    }

    return [
        'demo_url' => DEMO_URL,
        'php_rand_version' => getPhpRandVersionLabel(),
        'php_version' => PHP_VERSION,
        'php_sapi' => PHP_SAPI,
        'environment' => isRunningInDocker() ? 'docker' : 'native',
        'docker_image_version' => $dockerImageVersion,
        'server_software' => $serverSoftware,
        'os' => PHP_OS_FAMILY,
        'key_extensions' => $keyExtensions,
        'loaded_extensions' => $allLoaded,
        'loaded_extension_count' => count($allLoaded),
        'has_unreleased_changes' => hasUnreleasedChangelogChanges(),
    ];
}
