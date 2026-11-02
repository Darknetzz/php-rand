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
        'Zend OPcache' => 'OPcache (opcode cache enabled)',
    ];
}

/**
 * Read a file under APP_ROOT, or null when missing/unreadable.
 */
function aboutReadAppFile(string $relativePath): ?string
{
    $path = APP_ROOT . DIRSEP . str_replace('/', DIRSEP, $relativePath);
    if (!is_readable($path)) {
        return null;
    }
    $content = file_get_contents($path);
    return $content === false ? null : $content;
}

/**
 * Parse a CDN asset URL into a library name + version (null version = unpinned).
 *
 * @return array{name: string, version: ?string, host: string}|null
 */
function aboutParseCdnUrl(string $url): ?array
{
    if (preg_match('#cdn\.jsdelivr\.net/npm/((?:@[^/@]+/)?[^/@]+)(?:@([^/]+))?/#', $url, $m)) {
        return ['name' => $m[1], 'version' => ($m[2] ?? '') !== '' ? $m[2] : null, 'host' => 'jsDelivr'];
    }
    if (preg_match('#unpkg\.com/((?:@[^/@]+/)?[^/@]+)(?:@([^/]+))?/#', $url, $m)) {
        return ['name' => $m[1], 'version' => ($m[2] ?? '') !== '' ? $m[2] : null, 'host' => 'unpkg'];
    }
    if (preg_match('#cdnjs\.cloudflare\.com/ajax/libs/([^/]+)/([^/]+)/#', $url, $m)) {
        return ['name' => $m[1], 'version' => $m[2], 'host' => 'cdnjs'];
    }
    if (preg_match('#code\.jquery\.com/([a-z]+)-(\d[\w.-]*?)(?:\.min)?\.js#', $url, $m)) {
        return ['name' => $m[1], 'version' => $m[2], 'host' => 'code.jquery.com'];
    }
    return null;
}

/**
 * External libraries and their versions, read from the files that load them
 * (index.php tags, js/rand.js lazy loaders, bundled js/*.min.js, Composer).
 *
 * @return list<array{name: string, version: string, type: string, source: string, url: string}>
 */
function getAboutDependencies(): array
{
    $deps = [];
    $add = static function (string $name, ?string $version, string $type, string $source, string $url) use (&$deps): void {
        $key = strtolower($name);
        if (isset($deps[$key])) {
            return;
        }
        $deps[$key] = [
            'name' => $name,
            'version' => $version ?? 'latest (unpinned)',
            'type' => $type,
            'source' => $source,
            'url' => $url,
        ];
    };
    $cdnPattern = '#"(https://(?:cdn\.jsdelivr\.net|cdnjs\.cloudflare\.com|unpkg\.com|code\.jquery\.com)/[^"]*)"(?:\s*\+\s*([A-Z_][A-Z0-9_]*))?#';

    // Page-level CDN tags (HTML comments stripped so disabled includes are skipped).
    $index = aboutReadAppFile('index.php');
    if ($index !== null) {
        $index = (string) preg_replace('/<!--.*?-->/s', '', $index);
        if (preg_match_all($cdnPattern, $index, $matches)) {
            foreach ($matches[1] as $url) {
                $lib = aboutParseCdnUrl($url);
                if ($lib !== null) {
                    $add($lib['name'], $lib['version'], 'Frontend', 'CDN (' . $lib['host'] . ')', 'https://www.npmjs.com/package/' . $lib['name']);
                }
            }
        }
    }

    // Lazy-loaded assets in rand.js; resolves `"…/" + SOME_VERSION` concatenations.
    $randJs = aboutReadAppFile('js/rand.js');
    if ($randJs !== null) {
        preg_match_all('/\bconst\s+([A-Z_][A-Z0-9_]*)\s*=\s*"([^"]*)"/', $randJs, $constMatches);
        $consts = array_combine($constMatches[1], $constMatches[2]) ?: [];
        if (preg_match_all($cdnPattern, $randJs, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $url = $m[1] . (isset($m[2]) && isset($consts[$m[2]]) ? $consts[$m[2]] . '/' : '');
                $lib = aboutParseCdnUrl($url);
                if ($lib !== null) {
                    $add($lib['name'], $lib['version'], 'Frontend', 'CDN (' . $lib['host'] . '), on demand', 'https://www.npmjs.com/package/' . $lib['name']);
                }
            }
        }
    }

    // Bundled minified scripts with a `/*! Name vX.Y.Z` banner.
    foreach (glob(APP_ROOT . DIRSEP . 'js' . DIRSEP . '*.min.js') ?: [] as $file) {
        $head = (string) file_get_contents($file, false, null, 0, 300);
        if (preg_match('#^/\*!\s*([A-Za-z][\w.-]*)\s+v?(\d[\w.-]*)#', $head, $m)) {
            $add(strtolower($m[1]), $m[2], 'Frontend', 'Bundled (js/' . basename($file) . ')', 'https://www.npmjs.com/package/' . strtolower($m[1]));
        }
    }

    // Composer packages actually installed (falls back to composer.lock).
    $direct = [];
    $composerJson = json_decode((string) aboutReadAppFile('composer.json'), true);
    if (is_array($composerJson) && is_array($composerJson['require'] ?? null)) {
        $direct = array_flip(array_keys($composerJson['require']));
    }
    $installed = json_decode((string) (aboutReadAppFile('vendor/composer/installed.json') ?? aboutReadAppFile('composer.lock')), true);
    $packages = is_array($installed) ? ($installed['packages'] ?? $installed) : [];
    $composerRows = [];
    foreach (is_array($packages) ? $packages : [] as $pkg) {
        if (!is_array($pkg) || !isset($pkg['name'], $pkg['version'])) {
            continue;
        }
        $isDirect = isset($direct[$pkg['name']]);
        $composerRows[] = [$isDirect ? 0 : 1, (string) $pkg['name'], ltrim((string) $pkg['version'], 'v'), $isDirect];
    }
    sort($composerRows);
    foreach ($composerRows as [, $name, $version, $isDirect]) {
        $add($name, $version, 'PHP', $isDirect ? 'Composer' : 'Composer (dependency)', 'https://packagist.org/packages/' . $name);
    }

    return array_values($deps);
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
        $isLoaded = isset($loadedSet[strtolower($ext)]);
        // PHP 8.5 always loads OPcache, so report whether it is actually caching.
        if ($ext === 'Zend OPcache' && $isLoaded) {
            $status = function_exists('opcache_get_status') ? @opcache_get_status(false) : false;
            $isLoaded = is_array($status) && !empty($status['opcache_enabled']);
        }
        $keyExtensions[] = [
            'name' => $ext,
            'label' => $label,
            'loaded' => $isLoaded,
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
        'dependencies' => getAboutDependencies(),
        'loaded_extensions' => $allLoaded,
        'loaded_extension_count' => count($allLoaded),
        'has_unreleased_changes' => hasUnreleasedChangelogChanges(),
    ];
}
