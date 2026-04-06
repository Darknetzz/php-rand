<?php

declare(strict_types=1);

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

function syntax_validate_max_len(): int {
    return 200000;
}

function syntax_validate_jsonl_max_lines(): int {
    return 25000;
}

/**
 * @return list<string>
 */
function syntax_validate_allowed_kinds(): array {
    return ['json', 'yaml', 'xml', 'ini', 'jsonl', 'cron', 'php', 'python', 'ruby', 'javascript', 'shell'];
}

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_dispatch(string $kind, string $input): array {
    $kind = strtolower(trim($kind));
    return match ($kind) {
        'json' => syntax_validate_json($input),
        'yaml' => syntax_validate_yaml($input),
        'xml' => syntax_validate_xml($input),
        'ini' => syntax_validate_ini($input),
        'jsonl' => syntax_validate_jsonl($input),
        'cron' => syntax_validate_cron($input),
        'php' => syntax_validate_php_cli($input),
        'python' => syntax_validate_python_cli($input),
        'ruby' => syntax_validate_ruby_cli($input),
        'javascript' => syntax_validate_javascript_cli($input),
        'shell' => syntax_validate_shell_cli($input),
        default => ['ok' => false, 'message' => 'Unknown language.'],
    };
}

/**
 * Run a syntax-check CLI that takes a single source file path as the last argument.
 *
 * @param list<string> $argvHead e.g. [$rubyBin, '-c']
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_cli_file_last_arg(string $input, string $tempPrefix, string $fileExt, array $argvHead, string $runErrorPrefix): array {
    $input = str_replace("\r", '', $input);
    if (trim($input) === '') {
        return ['ok' => false, 'message' => 'Input is empty.'];
    }

    $tempBase = tempnam(sys_get_temp_dir(), $tempPrefix);
    if ($tempBase === false) {
        return ['ok' => false, 'message' => 'Unable to create a temporary file.'];
    }
    $path = $tempBase . '.' . $fileExt;
    if (!@rename($tempBase, $path)) {
        $path = $tempBase;
    }
    if (@file_put_contents($path, $input) === false) {
        @unlink($path);
        return ['ok' => false, 'message' => 'Unable to write temporary file.'];
    }

    $cmd = array_merge($argvHead, [$path]);
    $result = cli_run_command($cmd);
    @unlink($path);

    if (($result['ok'] ?? false) !== true) {
        return ['ok' => false, 'message' => $runErrorPrefix . (string) ($result['error'] ?? '')];
    }

    $exit = (int) ($result['exit_code'] ?? 1);
    $stderr = trim((string) ($result['stderr'] ?? ''));
    $stdout = trim((string) ($result['stdout'] ?? ''));

    if ($exit === 0) {
        return ['ok' => true, 'message' => 'No syntax errors detected.'];
    }

    $msg = $stderr !== '' ? $stderr : ($stdout !== '' ? $stdout : 'Syntax check failed.');

    return ['ok' => false, 'message' => $msg];
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
function syntax_validate_xml(string $input): array {
    $input = str_replace("\r", '', $input);
    if (trim($input) === '') {
        return ['ok' => false, 'message' => 'Input is empty.'];
    }

    $useErrors = libxml_use_internal_errors(true);
    libxml_clear_errors();
    $doc = new DOMDocument();
    $loaded = @$doc->loadXML($input, LIBXML_NONET);
    $errors = libxml_get_errors();
    libxml_clear_errors();
    libxml_use_internal_errors($useErrors);

    if ($loaded) {
        return ['ok' => true, 'message' => 'Valid XML.'];
    }

    $msg = 'Invalid XML.';
    if ($errors !== []) {
        $first = $errors[0];
        $msg = trim($first->message) . ' (line ' . (string) $first->line . ')';
    }

    return ['ok' => false, 'message' => $msg];
}

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_ini(string $input): array {
    $input = str_replace("\r", '', $input);
    if (trim($input) === '') {
        return ['ok' => false, 'message' => 'Input is empty.'];
    }

    $parsed = @parse_ini_string($input, true);
    if ($parsed === false) {
        return ['ok' => false, 'message' => 'Invalid INI syntax.'];
    }

    return ['ok' => true, 'message' => 'Valid INI.'];
}

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_jsonl(string $input): array {
    $input = str_replace("\r", '', $input);
    if (trim($input) === '') {
        return ['ok' => false, 'message' => 'Input is empty.'];
    }

    $lines = explode("\n", $input);
    if (count($lines) > syntax_validate_jsonl_max_lines()) {
        return ['ok' => false, 'message' => 'Too many lines (max ' . (string) syntax_validate_jsonl_max_lines() . ').'];
    }

    $lineNum = 0;
    foreach ($lines as $line) {
        $lineNum++;
        if (trim($line) === '') {
            continue;
        }
        try {
            json_decode($line, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return ['ok' => false, 'message' => 'Line ' . (string) $lineNum . ': ' . $e->getMessage()];
        }
    }

    return ['ok' => true, 'message' => 'All non-empty lines are valid JSON.'];
}

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_cron(string $input): array {
    $input = str_replace("\r", '', $input);
    $expression = '';
    foreach (explode("\n", $input) as $line) {
        $t = trim($line);
        if ($t === '' || str_starts_with($t, '#')) {
            continue;
        }
        $expression = $t;
        break;
    }
    if ($expression === '') {
        return ['ok' => false, 'message' => 'No cron expression found (empty or comments only).'];
    }

    $parsed = cron_parse_expression_fields($expression);
    if (!$parsed['ok']) {
        return ['ok' => false, 'message' => $parsed['error']];
    }
    if ($parsed['reboot']) {
        return ['ok' => true, 'message' => 'Valid cron schedule (@reboot).'];
    }

    return ['ok' => true, 'message' => 'Valid cron expression.'];
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

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_ruby_cli(string $input): array {
    $ruby = cli_find_binary('ruby');
    if ($ruby === '') {
        return ['ok' => false, 'message' => 'ruby CLI is not available on this host.'];
    }
    $r = syntax_validate_cli_file_last_arg($input, 'rubylint_', 'rb', [$ruby, '-c'], 'Unable to run ruby: ');
    if ($r['ok']) {
        return ['ok' => true, 'message' => 'Valid Ruby syntax.'];
    }

    return $r;
}

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_javascript_cli(string $input): array {
    $node = cli_find_binary('node');
    if ($node === '') {
        return ['ok' => false, 'message' => 'node CLI is not available on this host.'];
    }
    $r = syntax_validate_cli_file_last_arg($input, 'nodelint_', 'js', [$node, '--check'], 'Unable to run node: ');
    if ($r['ok']) {
        return ['ok' => true, 'message' => 'Valid JavaScript syntax.'];
    }

    return $r;
}

/**
 * @return array{ok: bool, message: string, detail?: string}
 */
function syntax_validate_shell_cli(string $input): array {
    $bash = cli_find_binary('bash');
    if ($bash !== '') {
        $r = syntax_validate_cli_file_last_arg($input, 'shelllint_', 'sh', [$bash, '-n'], 'Unable to run bash: ');
        if ($r['ok']) {
            return ['ok' => true, 'message' => 'Valid bash script syntax (bash -n).'];
        }

        return $r;
    }

    $sh = cli_find_binary('sh');
    if ($sh !== '') {
        $r = syntax_validate_cli_file_last_arg($input, 'shelllint_', 'sh', [$sh, '-n'], 'Unable to run sh: ');
        if ($r['ok']) {
            return ['ok' => true, 'message' => 'Valid shell script syntax (sh -n).'];
        }

        return $r;
    }

    return ['ok' => false, 'message' => 'bash/sh CLI is not available on this host.'];
}
