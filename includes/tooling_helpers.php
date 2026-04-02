<?php

function cli_find_binary(string $binary): string {
    if ($binary === '' || !preg_match('/^[A-Za-z0-9._-]+$/', $binary)) {
        return '';
    }

    if (!function_exists('shell_exec')) {
        return '';
    }

    $path = trim((string) @shell_exec('command -v ' . escapeshellarg($binary) . ' 2>/dev/null'));
    return is_file($path) ? $path : '';
}

function cli_run_command(array $command, ?string $stdin = null): array {
    if (!function_exists('proc_open')) {
        return [
            'ok' => false,
            'error' => 'proc_open() is disabled on this server.',
            'stdout' => '',
            'stderr' => '',
            'exit_code' => 127,
        ];
    }

    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $process = @proc_open($command, $descriptors, $pipes, null, null, ['bypass_shell' => true]);
    if (!is_resource($process)) {
        return [
            'ok' => false,
            'error' => 'Unable to start external process.',
            'stdout' => '',
            'stderr' => '',
            'exit_code' => 127,
        ];
    }

    if (isset($pipes[0])) {
        if ($stdin !== null) {
            fwrite($pipes[0], $stdin);
        }
        fclose($pipes[0]);
    }

    $stdout = isset($pipes[1]) ? (string) stream_get_contents($pipes[1]) : '';
    if (isset($pipes[1])) {
        fclose($pipes[1]);
    }

    $stderr = isset($pipes[2]) ? (string) stream_get_contents($pipes[2]) : '';
    if (isset($pipes[2])) {
        fclose($pipes[2]);
    }

    $exitCode = proc_close($process);

    return [
        'ok' => true,
        'error' => null,
        'stdout' => $stdout,
        'stderr' => $stderr,
        'exit_code' => is_int($exitCode) ? $exitCode : 1,
    ];
}

function cron_month_labels(): array {
    return [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];
}

function cron_weekday_labels(): array {
    return [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday',
    ];
}

function cron_ordinal(int $value): string {
    $abs = abs($value);
    $mod100 = $abs % 100;
    if ($mod100 >= 11 && $mod100 <= 13) {
        return $value . 'th';
    }

    return match ($abs % 10) {
        1 => $value . 'st',
        2 => $value . 'nd',
        3 => $value . 'rd',
        default => $value . 'th',
    };
}

function cron_join_human(array $items, string $glue = 'and'): string {
    $items = array_values(array_filter(array_map(static fn($item) => trim((string) $item), $items), static fn($item) => $item !== ''));
    $count = count($items);
    if ($count === 0) {
        return '';
    }
    if ($count === 1) {
        return $items[0];
    }
    if ($count === 2) {
        return $items[0] . ' ' . $glue . ' ' . $items[1];
    }

    $last = array_pop($items);
    return implode(', ', $items) . ', ' . $glue . ' ' . $last;
}

function cron_is_wildcard(string $expression): bool {
    $expression = trim($expression);
    return $expression === '*' || $expression === '?';
}

function cron_label_value(string $field, string $value): string {
    $value = trim($value);
    $upper = strtoupper($value);

    if ($field === 'month') {
        $months = cron_month_labels();
        $monthAliases = [
            'JAN' => 1,
            'FEB' => 2,
            'MAR' => 3,
            'APR' => 4,
            'MAY' => 5,
            'JUN' => 6,
            'JUL' => 7,
            'AUG' => 8,
            'SEP' => 9,
            'OCT' => 10,
            'NOV' => 11,
            'DEC' => 12,
        ];
        if (isset($monthAliases[$upper])) {
            return $months[$monthAliases[$upper]];
        }
        if (ctype_digit($value)) {
            $month = intval($value);
            return $months[$month] ?? (string) $month;
        }
    }

    if ($field === 'dow') {
        $weekdays = cron_weekday_labels();
        $dayAliases = [
            'SUN' => 0,
            'MON' => 1,
            'TUE' => 2,
            'WED' => 3,
            'THU' => 4,
            'FRI' => 5,
            'SAT' => 6,
        ];
        if (isset($dayAliases[$upper])) {
            return $weekdays[$dayAliases[$upper]];
        }
        if (ctype_digit($value)) {
            $day = intval($value);
            return $weekdays[$day] ?? (string) $day;
        }
    }

    if (ctype_digit($value)) {
        return (string) intval($value);
    }

    return $value;
}

function cron_describe_token(string $token, string $field): string {
    $token = trim($token);

    $plurals = [
        'minute' => 'minutes',
        'hour' => 'hours',
        'dom' => 'days',
        'month' => 'months',
        'dow' => 'days of the week',
    ];

    if (cron_is_wildcard($token)) {
        return 'every ' . $plurals[$field];
    }

    if ($field === 'dom' && strtoupper($token) === 'L') {
        return 'the last day of the month';
    }

    if ($field === 'dom' && strtoupper($token) === 'LW') {
        return 'the last weekday of the month';
    }

    if ($field === 'dom' && preg_match('/^(\d{1,2})W$/i', $token, $matches)) {
        return 'the weekday nearest to day ' . intval($matches[1]);
    }

    if ($field === 'dow' && preg_match('/^([0-7A-Z]{1,3})L$/i', $token, $matches)) {
        return 'the last ' . cron_label_value('dow', $matches[1]) . ' of the month';
    }

    if ($field === 'dow' && preg_match('/^([0-7A-Z]{1,3})#([1-5])$/i', $token, $matches)) {
        return 'the ' . cron_ordinal((int) $matches[2]) . ' ' . cron_label_value('dow', $matches[1]) . ' of the month';
    }

    if (preg_match('/^\*\/(\d+)$/', $token, $matches)) {
        return 'every ' . intval($matches[1]) . ' ' . $plurals[$field];
    }

    if (preg_match('/^([0-9A-Z]+)-([0-9A-Z]+)\/(\d+)$/i', $token, $matches)) {
        return 'every ' . intval($matches[3]) . ' ' . $plurals[$field] . ' from '
            . cron_label_value($field, $matches[1]) . ' through ' . cron_label_value($field, $matches[2]);
    }

    if (preg_match('/^([0-9A-Z]+)-([0-9A-Z]+)$/i', $token, $matches)) {
        $start = cron_label_value($field, $matches[1]);
        $end = cron_label_value($field, $matches[2]);
        return match ($field) {
            'minute' => 'minutes ' . $start . ' through ' . $end,
            'hour' => 'hours ' . $start . ' through ' . $end,
            'dom' => 'days ' . $start . ' through ' . $end,
            default => $start . ' through ' . $end,
        };
    }

    $label = cron_label_value($field, $token);

    return match ($field) {
        'minute' => 'at minute ' . $label,
        'hour' => 'at hour ' . str_pad((string) intval((string) $label), 2, '0', STR_PAD_LEFT),
        'dom' => 'day ' . $label . ' of the month',
        default => $label,
    };
}

function cron_describe_field(string $expression, string $field): string {
    $tokens = preg_split('/\s*,\s*/', trim($expression));
    if (!is_array($tokens) || $tokens === []) {
        return trim($expression);
    }

    $descriptions = array_map(static fn($token) => cron_describe_token($token, $field), $tokens);
    return cron_join_human($descriptions);
}

function cron_is_simple_value(string $expression): bool {
    return (bool) preg_match('/^[0-9A-Z]+$/i', trim($expression));
}

function cron_time_summary(string $minuteExpression, string $hourExpression): string {
    $minuteExpression = trim($minuteExpression);
    $hourExpression = trim($hourExpression);

    if ($minuteExpression === '*' && $hourExpression === '*') {
        return 'Every minute';
    }

    if (preg_match('/^\*\/(\d+)$/', $minuteExpression, $step) && $hourExpression === '*') {
        return 'Every ' . intval($step[1]) . ' minutes';
    }

    if (cron_is_simple_value($minuteExpression) && $hourExpression === '*') {
        return 'At minute ' . intval($minuteExpression) . ' past every hour';
    }

    if (cron_is_simple_value($minuteExpression) && preg_match('/^\*\/(\d+)$/', $hourExpression, $step)) {
        return 'At minute ' . intval($minuteExpression) . ' past every ' . intval($step[1]) . ' hours';
    }

    if (cron_is_simple_value($minuteExpression) && cron_is_simple_value($hourExpression) && ctype_digit($minuteExpression) && ctype_digit($hourExpression)) {
        return 'At ' . str_pad((string) intval($hourExpression), 2, '0', STR_PAD_LEFT)
            . ':' . str_pad((string) intval($minuteExpression), 2, '0', STR_PAD_LEFT);
    }

    return 'Runs on a custom schedule';
}

function cron_build_summary(array $parts): string {
    $minute = $parts[0] ?? '*';
    $hour = $parts[1] ?? '*';
    $dayOfMonth = $parts[2] ?? '*';
    $month = $parts[3] ?? '*';
    $dayOfWeek = $parts[4] ?? '*';

    $segments = [cron_time_summary($minute, $hour)];

    if (!cron_is_wildcard($month)) {
        $segments[] = 'in ' . cron_describe_field($month, 'month');
    }

    if (cron_is_wildcard($dayOfMonth) && cron_is_wildcard($dayOfWeek)) {
        $segments[] = 'every day';
    } elseif (!cron_is_wildcard($dayOfMonth) && cron_is_wildcard($dayOfWeek)) {
        $segments[] = 'on ' . cron_describe_field($dayOfMonth, 'dom');
    } elseif (cron_is_wildcard($dayOfMonth) && !cron_is_wildcard($dayOfWeek)) {
        $segments[] = 'on ' . cron_describe_field($dayOfWeek, 'dow');
    } else {
        $segments[] = 'when either ' . cron_describe_field($dayOfMonth, 'dom')
            . ' or ' . cron_describe_field($dayOfWeek, 'dow') . ' matches';
    }

    return rtrim(implode(' ', array_filter($segments)), '.') . '.';
}

function cron_evaluate_schedule(
    string $expression,
    string $timezone,
    string $referenceRaw = '',
    bool $allowCurrent = false,
    int $runCount = 8
): array {
    $expression = trim($expression);
    if ($expression === '') {
        return ['ok' => false, 'error' => 'Cron expression is required.'];
    }
    if (strlen($expression) > 120) {
        return ['ok' => false, 'error' => 'Cron expression is too long. Maximum 120 characters allowed.'];
    }

    if (!in_array($timezone, DateTimeZone::listIdentifiers(), true)) {
        return ['ok' => false, 'error' => 'Invalid timezone selected.'];
    }

    $runCount = max(1, min(20, $runCount));

    try {
        $referenceTime = $referenceRaw !== ''
            ? new DateTime($referenceRaw, new DateTimeZone($timezone))
            : new DateTime('now', new DateTimeZone($timezone));
    } catch (Throwable) {
        return ['ok' => false, 'error' => 'Reference time is invalid. Use a valid local date/time.'];
    }

    try {
        $cron = new \Cron\CronExpression($expression);
    } catch (Throwable $e) {
        return ['ok' => false, 'error' => 'Invalid cron expression: ' . $e->getMessage()];
    }

    try {
        $parts = $cron->getParts();
        $normalizedExpression = (string) $cron->getExpression();
        $summary = cron_build_summary($parts);
        $isDue = $cron->isDue(clone $referenceTime, $timezone);
        $previousRun = $cron->getPreviousRunDate(clone $referenceTime, 0, $allowCurrent, $timezone);
        $nextRuns = $cron->getMultipleRunDates($runCount, clone $referenceTime, false, $allowCurrent, $timezone);
    } catch (Throwable $e) {
        return ['ok' => false, 'error' => 'Unable to evaluate this cron expression right now: ' . $e->getMessage()];
    }

    return [
        'ok' => true,
        'expression' => $expression,
        'timezone' => $timezone,
        'reference_time' => $referenceTime,
        'run_count' => $runCount,
        'allow_current' => $allowCurrent,
        'cron' => $cron,
        'parts' => $parts,
        'normalized_expression' => $normalizedExpression,
        'summary' => $summary,
        'is_due' => $isDue,
        'previous_run' => $previousRun,
        'next_runs' => $nextRuns,
    ];
}
