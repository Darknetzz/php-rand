<?php

/**
 * Handler Registry - Functional approach
 * Maps actions to their handler functions
 */

function getHandlerRegistry(): array {
    return [
        'stringgen' => 'handle_stringgen',
        'hash' => 'handle_hash',
        'numgen' => 'handle_numgen',
        'base' => 'handle_base',
        'hex' => 'handle_hex',
        'rot' => 'handle_rot',
        'openssl' => 'handle_openssl',
        'datetime' => 'handle_datetime',
        'stringtools' => 'handle_stringtools',
        'ip' => 'handle_ip',
        'urlencode' => 'handle_urlencode',
        'htmlentities' => 'handle_htmlentities',
        'minify' => 'handle_minify',
        'metaphone' => 'handle_metaphone',
        'levenshtein' => 'handle_levenshtein',
        'diff' => 'handle_diff',
        'serialization' => 'handle_serialization',
        'spinwheel' => 'handle_spinwheel',
        'calc' => 'handle_calculator',
        'currency' => 'handle_currency',
    ];
}

/**
 * Execute handler for given action
 */
function executeHandler(array $request): ?string {
    $action = $request['action'] ?? '';
    $registry = getHandlerRegistry();
    
    // Check if handler exists
    if (isset($registry[$action]) && function_exists($registry[$action])) {
        return call_user_func($registry[$action], $request);
    }
    
    // Check for special cases (hash, numgen with different POST keys)
    if (isset($request['hash'])) {
        return handle_hash($request);
    }
    if (isset($request['numgenfrom']) && isset($request['numgento'])) {
        return handle_numgen($request);
    }
    if (isset($request['rot'])) {
        return handle_rot($request);
    }
    
    return null;
}

// ============================================================================
// Helper Functions
// ============================================================================

function req_get(array $request, string $key, $default = null) {
    return $request[$key] ?? $default;
}

function req_int(array $request, string $key, int $default = 0): int {
    return isset($request[$key]) ? intval($request[$key]) : $default;
}

function req_float(array $request, string $key, float $default = 0.0): float {
    return isset($request[$key]) ? floatval($request[$key]) : $default;
}

function req_bool(array $request, string $key): bool {
    return isset($request[$key]) && $request[$key] == 1;
}

function output_copyable(string $content, string $label = ""): string {
    return "<div style='margin-bottom: 15px;'>" . copyableOutput($content, $label) . "</div>";
}

// ============================================================================
// Handler Functions
// ============================================================================

function handle_stringgen(array $req): string {
    $length = req_int($req, 'digits');
    if ($length < 1 || $length > 1000000) {
        return formatOutput("Invalid number of characters.", type: "danger");
    }

    $strings = req_int($req, 'strings', 1);
    $charsets = '';
    foreach (['l', 'u', 'n', 's', 'e', 'c'] as $opt) {
        if (req_bool($req, $opt)) {
            $charsets .= $opt;
        }
    }
    $cchars = req_get($req, 'cchars', '');

    $results = [];
    $infoTables = [];

    for ($i = 0; $i < $strings; $i++) {
        $string = genStr($charsets, $length, $cchars);
        $results[] = $string;
        
        $table = "<table class='table table-default'>";
        $table .= "<tr><td>String</td><td><pre>$string</pre></td></tr>";
        $table .= "<tr><td>MD5</td><td><pre>" . hash('md5', $string) . "</pre></td></tr>";
        $table .= "<tr><td>SHA1</td><td><pre>" . hash('sha1', $string) . "</pre></td></tr>";
        $table .= "<tr><td>SHA256</td><td><pre>" . hash('sha256', $string) . "</pre></td></tr>";
        $table .= "<tr><td>SHA512</td><td><pre>" . hash('sha512', $string) . "</pre></td></tr>";
        $combinations = number_format(strlen($charsets)**$length);
        $table .= "<tr><td>Possible combinations</td><td><pre>$combinations (" . strlen($charsets) . "^$length)</pre></td></tr>";
        $table .= "</table>";
        $infoTables[] = $table;
    }

    $output = "<hr>";
    foreach ($results as $string) {
        $output .= output_copyable($string);
    }

    $infoContent = implode('', $infoTables);
    $output .= "
    <button class='btn btn-info' type='button' data-bs-toggle='collapse' data-bs-target='#additionalInfo' aria-expanded='false' aria-controls='additionalInfo'>" . icon('info-circle') . "</button>
    <div id='additionalInfo' class='collapse' style='margin:15px;'>
      <div class='card border-info'>
        <h4 class='card-header text-bg-info'>" . icon('info-circle') . " Info</h4>
        <div class='card-body'>$infoContent</div>
      </div>
    </div>";

    return $output;
}

function handle_hash(array $req): string {
    $input = req_get($req, 'hash');
    $hashalgo = req_get($req, 'hashalgo');
    
    $types = (!empty($hashalgo) && in_array($hashalgo, hash_algos())) 
        ? [$hashalgo] 
        : hash_algos();

    $output = "";
    foreach ($types as $type) {
        $hashValue = hash($type, $input);
        $output .= output_copyable($hashValue, $type);
    }
    
    return formatOutput($output);
}

function handle_numgen(array $req): string {
    $from = req_int($req, 'numgenfrom');
    $to = req_int($req, 'numgento');
    $seed = req_bool($req, 'seed') ? req_get($req, 'numgenseed') : null;

    $result = numGen($from, $to, $seed);
    $output = output_copyable($result);

    if ($seed) {
        $output .= "<div style='margin-top: 15px; opacity: 0.7;'><small><strong>Seed used:</strong> $seed</small></div>";
    }

    return $output;
}

function handle_base(array $req): string {
    $input = req_get($req, 'base');
    $from = req_get($req, 'from', 'text');
    $to = req_get($req, 'to', 64);

    try {
        $result = convert_any($input, $from, $to);
        $output = "<div style='margin-bottom: 20px;'>";
        $output .= "<div style='margin-bottom: 15px;'><strong>Base $from → Base $to</strong></div>";
        $output .= copyableOutput($result);
        $output .= "</div>";
        return $output;
    } catch (Exception $e) {
        return formatOutput($e->getMessage(), type: "danger");
    }
}

function handle_hex(array $req): string {
    $tool = req_get($req, 'tool');
    $input = trim(req_get($req, 'binhex', '') ?: req_get($req, 'iphex', ''));

    if (empty($input)) {
        return formatOutput("Empty input", type: "danger");
    }

    $split = req_bool($req, 'split');
    $delimiter = req_get($req, 'delimiter', ':');
    $chunkLength = req_int($req, 'chunklength', 2);

    $output = '';
    
    if ($tool == 'bin2hex') {
        $output = bin2hex($input);
        if ($split) {
            $output = chunk_split($output, $chunkLength, $delimiter);
            $output = rtrim($output, $delimiter);
        }
    } 
    elseif ($tool == 'hex2bin') {
        $input = preg_replace('/[^a-zA-Z0-9]/', '', $input);
        if (!ctype_xdigit($input) || (strlen($input) % 2) != 0) {
            return formatOutput("Input must only include hexadecimal and have an even length.", type: "danger");
        }
        $output = hex2bin($input);
    }
    elseif ($tool == 'ip2hex') {
        $input = str_replace(" ", "", $input);
        if (strpos($input, ",") !== false) {
            $ips = explode(",", $input);
            $output = implode("<br>", array_map(fn($ip) => ip2hex($ip, $split, $delimiter), $ips));
        } else {
            $output = ip2hex($input, $split, $delimiter);
        }
    }
    elseif ($tool == 'hex2ip') {
        $output = hex2ip($input);
    }

    return output_copyable($output);
}

function handle_rot(array $req): string {
    $input = req_get($req, 'rot');
    $bruteforce = req_bool($req, 'bruteforce');

    if ($bruteforce) {
        $output = "";
        for ($i = 0; $i < 26; $i++) {
            $rotated = str_rot($input, $i);
            $output .= output_copyable($rotated, "ROT" . $i);
        }
        return $output;
    }

    $rotations = req_int($req, 'rotations', 13);
    $result = str_rot($input, $rotations);
    return output_copyable($result);
}

function handle_openssl(array $req): string {
    $tool = req_get($req, 'tool');
    $string = req_get($req, 'openssl', '');
    $key = req_get($req, 'key', '');
    $cipher = req_get($req, 'cipher', 'aes-256-cbc');
    $iv = req_get($req, 'iv', '');

    if (!in_array($cipher, openssl_get_cipher_methods())) {
        return formatOutput("Cipher `$cipher` is not supported.", type: "danger");
    }

    $warnings = '';

    if (empty($iv)) {
        $ivlen = openssl_cipher_iv_length($cipher) / 2;
        $iv = bin2hex(openssl_random_pseudo_bytes($ivlen));
        $warnings .= formatOutput("No IV specified, using random IV: $iv", type: "warning");
    }

    if (empty($key)) {
        $warnings .= formatOutput("No key specified, <b>this is unsafe</b>.", type: "warning");
    }

    $result = match($tool) {
        'encrypt' => openssl_encrypt($string, $cipher, $key, iv: $iv),
        'decrypt' => openssl_decrypt($string, $cipher, $key, iv: $iv),
        default => ''
    };

    if (empty($result)) {
        $result = "[empty]";
    }

    $output = $warnings;
    $output .= output_copyable($result);
    $output .= "<div style='margin-top: 20px; padding: 15px; background-color: rgba(255, 193, 7, 0.1); border-radius: 0.5rem;'>
        <strong>Encryption Details:</strong><br>
        🔑 <strong>Cipher:</strong> <code>$cipher</code><br>
        🔓 <strong>Key:</strong> <code>" . htmlspecialchars($key) . "</code><br>
        📍 <strong>IV (Hex):</strong> <code>$iv</code>
    </div>";

    return $output;
}

function handle_datetime(array $req): string {
    $fromUnit = req_get($req, 'timefrom_unit');
    $toUnit = req_get($req, 'timeto_unit');
    $time = req_get($req, 'time');

    if (empty($time) || empty($fromUnit) || empty($toUnit)) {
        return formatOutput("You must enter a value and select units.", type: "danger");
    }

    $time = intval($time);
    $units = [
        "s" => ["seconds", 1],
        "i" => ["minutes", 60],
        "h" => ["hours", 3600],
        "d" => ["days", 86400],
        "w" => ["weeks", 604800],
        "M" => ["months", 2628000],
        "y" => ["years", 31536000]
    ];

    $converted = round(($time * $units[$fromUnit][1]) / $units[$toUnit][1], 6);
    return output_copyable($converted . " " . $units[$toUnit][0], "$time " . $units[$fromUnit][0]);
}

function handle_stringtools(array $req): string {
    $string = req_get($req, 'string', '');
    $tool = req_get($req, 'tool', '');

    if (empty($string)) {
        return formatOutput("You must enter a string.", type: "danger");
    }
    if (empty($tool)) {
        return formatOutput("You must select a tool.", type: "danger");
    }

    // Apply string transformations
    $string = match($tool) {
        'trim' => trim($string),
        'removewhitespace' => preg_replace('/\s+/', '', $string),
        'reverse' => strrev($string),
        'repeat' => str_repeat($string, 2),
        'shuffle' => str_shuffle($string),
        'uppercase' => strtoupper($string),
        'lowercase' => strtolower($string),
        'titlecase' => ucwords($string),
        'camelcase' => lcfirst(str_replace(' ', '', ucwords($string))),
        'slugify' => strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace([" ", "-"], "_", $string))),
        'kebabcase' => strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace([" ", "_"], "-", $string))),
        'randomcase' => implode('', array_map(fn($c) => (mt_rand(0, 100) >= 50) ? strtoupper($c) : strtolower($c), str_split($string))),
        'invertedcase' => strtr($string, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'),
        'l33t5p34k' => str_replace(['a','e','o','t','l','s','b','A','E','O','T','L','S','B'], ['4','3','0','7','1','5','6','4','3','0','7','1','5','6'], $string),
        'crlf2lf' => str_replace(["\r", "\n"], "", $string),
        'lf2crlf' => str_replace(["\r", "\n"], "\r\n", $string),
        'formatlineendings' => str_replace(["\\r", "\\n"], "\n", $string),
        'removehtmltags' => strip_tags($string),
        'removepunctuation' => preg_replace('/[^\w\s]/', '', $string),
        'removenewlines' => str_replace(["\r\n", "\r", "\n"], '', $string),
        'removetabs' => str_replace("\t", '', $string),
        'removespaces' => str_replace(" ", '', $string),
        'removeslashes' => str_replace(["/", "\\"], '', $string),
        'removebackslashes' => str_replace("\\", "", $string),
        'removenonascii' => preg_replace('/[^\x00-\x7F]/', '', $string),
        'removenonprintable' => preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $string),
        'removewhitespaceext' => preg_replace('/\s+/', '', $string),
        'removenumbers' => preg_replace('/\d/', '', $string),
        'removeletters' => preg_replace('/[a-zA-Z]/', '', $string),
        'removesymbols' => preg_replace('/[^a-zA-Z0-9\s]/', '', $string),
        'removeextendedsymbols' => preg_replace('/[^a-zA-Z0-9\s\-_.]/', '', $string),
        default => $string
    };

    if (req_bool($req, 'outputToTextbox')) {
        return $string;
    }

    return formatOutput(nl2br($string));
}

// Additional handlers can be added here following the same pattern...
// handle_ip, handle_urlencode, handle_htmlentities, handle_minify, etc.
