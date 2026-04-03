<?php

declare(strict_types=1);

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

function syntax_validate_max_len(): int {
    return 200000;
}

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_dispatch(string $kind, string $input): array {
    $kind = strtolower(trim($kind));
    return match ($kind) {
        'json' => syntax_validate_json($input),
        'yaml' => syntax_validate_yaml($input),
        'php' => syntax_validate_php_cli($input),
        'python' => syntax_validate_python_cli($input),
        default => ['ok' => false, 'message' => 'Unknown language.'],
    };
}

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_json(string $input): array {
    $input = str_replace("\r", '', $input);
    if (trim($input) === '') {
        return ['ok' => false, 'message' => 'Input is empty.'];
    }
    try {
        json_decode($input, true, 512, JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) {
        return [
            'ok' => false,
            'message' => $e->getMessage(),
        ];
    }

    return ['ok' => true, 'message' => 'Valid JSON.'];
}

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_yaml(string $input): array {
    $input = str_replace("\r", '', $input);
    if (trim($input) === '') {
        return ['ok' => false, 'message' => 'Input is empty.'];
    }
    try {
        Yaml::parse($input);
    } catch (ParseException $e) {
        $detail = $e->getParsedLine() !== null ? 'Near line ' . (string) $e->getParsedLine() : null;

        return [
            'ok' => false,
            'message' => $e->getMessage(),
            'detail' => $detail,
        ];
    }

    return ['ok' => true, 'message' => 'Valid YAML.'];
}

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_php_cli(string $input): array {
    $input = str_replace("\r", '', $input);
    if (trim($input) === '') {
        return ['ok' => false, 'message' => 'Input is empty.'];
    }

    $toLint = $input;
    if (!preg_match('/^\s*<\?php\b/', $toLint)) {
        $toLint = "<?php\n" . $toLint;
    }

    $tempBase = tempnam(sys_get_temp_dir(), 'phplint_');
    if ($tempBase === false) {
        return ['ok' => false, 'message' => 'Unable to create a temporary file.'];
    }
    $path = $tempBase . '.php';
    if (!@rename($tempBase, $path)) {
        $path = $tempBase;
    }
    if (@file_put_contents($path, $toLint) === false) {
        @unlink($path);
        return ['ok' => false, 'message' => 'Unable to write temporary PHP file.'];
    }

    $php = cli_find_binary('php');
    if ($php === '') {
        @unlink($path);
        return ['ok' => false, 'message' => 'php CLI is not available on this host.'];
    }

    $result = cli_run_command([$php, '-l', $path]);
    @unlink($path);

    if (($result['ok'] ?? false) !== true) {
        return ['ok' => false, 'message' => (string) ($result['error'] ?? 'Unable to run php -l.')];
    }

    $exit = (int) ($result['exit_code'] ?? 1);
    $stderr = trim((string) ($result['stderr'] ?? ''));
    $stdout = trim((string) ($result['stdout'] ?? ''));

    if ($exit === 0) {
        return ['ok' => true, 'message' => 'No PHP syntax errors detected.'];
    }

    $msg = $stderr !== '' ? $stderr : ($stdout !== '' ? $stdout : 'PHP reported a syntax error.');

    return ['ok' => false, 'message' => $msg];
}

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_python_cli(string $input): array {
    $input = str_replace("\r", '', $input);
    if (trim($input) === '') {
        return ['ok' => false, 'message' => 'Input is empty.'];
    }

    $py = cli_find_binary('python3');
    if ($py === '') {
        $py = cli_find_binary('python');
    }
    if ($py === '') {
        return ['ok' => false, 'message' => 'python3/python CLI is not available on this host.'];
    }

    $code = 'import ast,sys; ast.parse(sys.stdin.read())';
    $result = cli_run_command([$py, '-c', $code], $input);

    if (($result['ok'] ?? false) !== true) {
        return ['ok' => false, 'message' => (string) ($result['error'] ?? 'Unable to run Python.')];
    }

    $exit = (int) ($result['exit_code'] ?? 1);
    $stderr = trim((string) ($result['stderr'] ?? ''));
    if ($exit === 0) {
        return ['ok' => true, 'message' => 'Valid Python syntax.'];
    }

    return ['ok' => false, 'message' => $stderr !== '' ? $stderr : 'Python reported a syntax error.'];
}
