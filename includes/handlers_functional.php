<?php

/**
 * Handler Registry - Functional approach to mapping actions to handler functions
 * 
 * Maintains a centralized registry of all available tool handlers.
 * Each action key maps to a handler function name that processes that specific tool.
 * Simplifies adding new tools by just registering them here.
 *
 * @return array Associative array where keys are action names and values are handler function names
 * 
 * @example
 * $registry = getHandlerRegistry();
 * $handler = $registry['hash']; // Returns: 'handle_hash'
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
 * Execute a handler function based on the request action
 * 
 * Looks up and executes the appropriate handler function for a given action.
 * Falls back to special case checks for handlers that use non-standard request keys.
 * Returns the handler's output or null if no matching handler found.
 *
 * @param array $request The request data, typically from $_POST
 *                       Should contain an 'action' key for normal routing
 * @return string|null The handler's output HTML/text, or null if no handler matched
 * 
 * @example
 * $request = ['action' => 'hash', 'hash' => 'md5', 'input' => 'hello'];
 * echo executeHandler($request); // Calls handle_hash() and returns its output
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
// Request Helper Functions
// ============================================================================

/**
 * Get a value from the request array with a default fallback
 *
 * @param array $request The request array (typically $_POST)
 * @param string $key The key to retrieve
 * @param mixed $default The value to return if key doesn't exist. Default: null
 * @return mixed The requested value or default
 */
function req_get(array $request, string $key, $default = null) {
    return $request[$key] ?? $default;
}

/**
 * Get and cast an integer value from the request array
 *
 * @param array $request The request array (typically $_POST)
 * @param string $key The key to retrieve
 * @param int $default The value to return if key doesn't exist. Default: 0
 * @return int The integer value or default
 */
function req_int(array $request, string $key, int $default = 0): int {
    return isset($request[$key]) ? intval($request[$key]) : $default;
}

/**
 * Get and cast a float value from the request array
 *
 * @param array $request The request array (typically $_POST)
 * @param string $key The key to retrieve
 * @param float $default The value to return if key doesn't exist. Default: 0.0
 * @return float The float value or default
 */
function req_float(array $request, string $key, float $default = 0.0): float {
    return isset($request[$key]) ? floatval($request[$key]) : $default;
}

/**
 * Check if a boolean value exists and equals 1 in the request array
 *
 * @param array $request The request array (typically $_POST)
 * @param string $key The key to check
 * @return bool True if key exists and equals 1, false otherwise
 */
function req_bool(array $request, string $key): bool {
    return isset($request[$key]) && $request[$key] == 1;
}

/**
 * Validate a request value with specified rules
 *
 * @param array $request The request array (typically $_POST)
 * @param string $key The key to validate
 * @param array $rules Validation rules (see validateInput() for format)
 * @return array ['valid' => bool, 'error' => string|null, 'value' => mixed]
 */
function req_validate(array $request, string $key, array $rules = []): array {
    $value = $request[$key] ?? null;
    return validateInput($value, $rules);
}

/**
 * Get and validate a string from request with rules
 *
 * @param array $request The request array
 * @param string $key The key to retrieve
 * @param int|null $minLength Minimum string length
 * @param int|null $maxLength Maximum string length
 * @return array ['valid' => bool, 'error' => string|null, 'value' => string]
 */
function req_string(array $request, string $key, ?int $minLength = null, ?int $maxLength = null): array {
    $rules = ['type' => 'string', 'required' => true];
    if ($minLength !== null) $rules['minLength'] = $minLength;
    if ($maxLength !== null) $rules['maxLength'] = $maxLength;
    return req_validate($request, $key, $rules);
}

/**
 * Get and validate an integer from request with range
 *
 * @param array $request The request array
 * @param string $key The key to retrieve
 * @param int|null $min Minimum value
 * @param int|null $max Maximum value
 * @return array ['valid' => bool, 'error' => string|null, 'value' => int]
 */
function req_int_validated(array $request, string $key, ?int $min = null, ?int $max = null): array {
    $value = req_int($request, $key, 0);
    
    if ($min !== null && $value < $min) {
        return ['valid' => false, 'error' => "Value must be at least {$min}", 'value' => $value];
    }
    
    if ($max !== null && $value > $max) {
        return ['valid' => false, 'error' => "Value must be at most {$max}", 'value' => $value];
    }
    
    return ['valid' => true, 'error' => null, 'value' => $value];
}

/**
 * Wrap content in a copyable output container
 *
 * @param string $content The content to display and copy
 * @param string $label Optional label for the output section. Default: ""
 * @return string HTML formatted copyable output element
 */
function output_copyable(string $content, string $label = ""): string {
    return "<div style='margin-bottom: 15px;'>" . copyableOutput($content, $label) . "</div>";
}

// ============================================================================
// Handler Functions - Tool-specific request processors
// ============================================================================

/**
 * Handle random string generation requests
 *
 * Processes requests to generate random strings with specified character sets
 * and options. Returns formatted output with generated strings and statistics.
 *
 * @param array $req Request array containing: 'digits', 'strings', 'l', 'u', 'n', 's', 'e', 'c', 'cchars'
 * @return string Formatted HTML output with generated strings or error message
 */
function handle_stringgen(array $req): string {
    // Validate digits input
    $digitValidation = req_int_validated($req, 'digits', 1, 1000000);
    if (!$digitValidation['valid']) {
        return formatOutput($digitValidation['error'], type: "danger");
    }
    $length = $digitValidation['value'];

    // Validate strings count
    $stringsValidation = req_int_validated($req, 'strings', 1, 10000);
    if (!$stringsValidation['valid']) {
        return formatOutput($stringsValidation['error'], type: "danger");
    }
    $strings = $stringsValidation['value'];
    
    $charsets = '';
    foreach (['l', 'u', 'n', 's', 'e', 'c'] as $opt) {
        if (req_bool($req, $opt)) {
            $charsets .= $opt;
        }
    }
    
    if (empty($charsets)) {
        return formatOutput("You must select at least one character set.", type: "danger");
    }
    
    $cchars = req_get($req, 'cchars', '');
    $cryptoSafe = req_bool($req, 'cryptoSafe');

    $results = [];
    $infoTables = [];

    for ($i = 0; $i < $strings; $i++) {
        if ($cryptoSafe) {
            $string = genStrCrypto($charsets, $length, $cchars);
        } else {
            $string = genStr($charsets, $length, $cchars);
        }
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
    if ($cryptoSafe) {
        $output .= "<div class='alert alert-success mb-3'>" . icon('shield-check') . " <strong>Cryptographically Secure Random</strong> - Generated using random_bytes()</div>";
    }
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

/**
 * Handle cryptographic hashing requests
 *
 * Generates hash values for input text using specified or all available
 * algorithms (MD5, SHA1, SHA256, SHA512, etc.).
 *
 * @param array $req Request array containing: 'hash' (input), 'hashalgo' (optional algorithm)
 * @return string Formatted HTML with hash values for each algorithm
 */
function handle_hash(array $req): string {
    // Validate input exists
    $input = req_get($req, 'hash', '');
    
    if (empty($input)) {
        return formatOutput("Input text is required for hashing.", type: "danger");
    }
    
    // Validate input length
    if (strlen($input) > 100000) {
        return formatOutput("Input text must be at most 100,000 characters.", type: "danger");
    }
    
    $hashalgo = req_get($req, 'hashalgo', 'all');
    
    // Validate algorithm if provided and not 'all'
    if ($hashalgo !== 'all' && !in_array($hashalgo, hash_algos())) {
        return formatOutput("Invalid hash algorithm selected.", type: "danger");
    }
    
    $types = ($hashalgo !== 'all' && in_array($hashalgo, hash_algos())) 
        ? [$hashalgo] 
        : hash_algos();

    $output = "";
    foreach ($types as $type) {
        $hashValue = hash($type, $input);
        $output .= output_copyable($hashValue, $type);
    }
    
    return formatOutput($output);
}

/**
 * Handle random number generation requests
 *
 * Generates a random integer between two values with optional seed support
 * for reproducible results.
 *
 * @param array $req Request array containing: 'numgenfrom', 'numgento', 'seed', 'numgenseed'
 * @return string Formatted HTML with generated number and seed info if provided
 */
function handle_numgen(array $req): string {
    // Validate 'from' parameter
    $fromValidation = req_int_validated($req, 'numgenfrom', -1000000000, 1000000000);
    if (!$fromValidation['valid']) {
        return formatOutput($fromValidation['error'], type: "danger");
    }
    $from = $fromValidation['value'];

    // Validate 'to' parameter
    $toValidation = req_int_validated($req, 'numgento', -1000000000, 1000000000);
    if (!$toValidation['valid']) {
        return formatOutput($toValidation['error'], type: "danger");
    }
    $to = $toValidation['value'];

    // Ensure 'from' is not greater than 'to'
    if ($from > $to) {
        return formatOutput("'From' value must be less than or equal to 'To' value.", type: "danger");
    }

    // Validate seed if provided
    $seed = null;
    if (req_bool($req, 'seed')) {
        $seedValidation = req_string($req, 'numgenseed', 1, 100);
        if (!$seedValidation['valid']) {
            return formatOutput($seedValidation['error'], type: "danger");
        }
        $seed = $seedValidation['value'];
    }

    $result = numGen($from, $to, $seed);
    $output = output_copyable($result);

    if ($seed) {
        $output .= "<div style='margin-top: 15px; opacity: 0.7;'><small><strong>Seed used:</strong> " . htmlspecialchars($seed) . "</small></div>";
    }

    return $output;
}

/**
 * Handle base conversion requests
 *
 * Converts data between different encoding formats (text, base64, base32, hex, etc.)
 * Supports bidirectional conversion between any supported base formats.
 *
 * @param array $req Request array containing: 'base' (input), 'from' (source format), 'to' (target format)
 * @return string Formatted HTML with conversion result or error message
 */
function handle_base(array $req): string {
    // Get and validate input
    $input = req_get($req, 'base', '');
    
    // Validate input length (up to 1MB encoded)
    if (strlen($input) > 1000000) {
        return formatOutput("Input must be at most 1,000,000 characters.", type: "danger");
    }

    // Validate source and target formats (whitelist allowed formats)
    $allowedFormats = ['text', 'base64', 'base32', 'hex', '64', '32', '16', '2', 64, 32, 16, 2];
    $from = req_get($req, 'from', 'text');
    $to = req_get($req, 'to', 64);

    if (!in_array($from, $allowedFormats, true)) {
        return formatOutput("Invalid source format specified.", type: "danger");
    }
    if (!in_array($to, $allowedFormats, true)) {
        return formatOutput("Invalid target format specified.", type: "danger");
    }

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

/**
 * Handle binary/hex conversion and IP/hex conversion requests
 *
 * Processes requests for bin2hex, hex2bin, ip2hex, and hex2ip conversions.
 * Handles multiple IPs with comma separation for ip2hex conversion.
 *
 * @param array $req Request array containing: 'tool' (bin2hex|hex2bin|ip2hex|hex2ip), 'binhex'|'iphex' (input),
 *                   'split' (optional), 'delimiter', 'chunklength'
 * @return string Formatted HTML with conversion result or error message
 */
function handle_hex(array $req): string {
    // Validate tool selection
    $allowedTools = ['bin2hex', 'hex2bin', 'ip2hex', 'hex2ip'];
    $tool = req_get($req, 'tool');
    if (!in_array($tool, $allowedTools)) {
        return formatOutput("Invalid tool selected.", type: "danger");
    }

    // Get input - try binhex first, then iphex
    $input = trim(req_get($req, 'binhex', '') ?: req_get($req, 'iphex', ''));
    
    if (empty($input)) {
        return formatOutput("Input is required.", type: "danger");
    }
    
    // Validate input length
    if (strlen($input) > 100000) {
        return formatOutput("Input must be at most 100,000 characters.", type: "danger");
    }

    // Validate chunk length for split output
    $chunkLength = req_int($req, 'chunklength', 2);
    if ($chunkLength < 1 || $chunkLength > 100) {
        return formatOutput("Chunk length must be between 1 and 100.", type: "danger");
    }

    $split = req_bool($req, 'split');
    $delimiter = req_get($req, 'delimiter', ':');

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

/**
 * Handle ROT cipher requests
 *
 * Performs ROT (rotate cipher) transformation on text. Can apply a single
 * rotation amount or brute force all 26 possible rotations.
 *
 * @param array $req Request array containing: 'rot' (input), 'rotations' (shift amount),
 *                   'bruteforce' (optional, shows all 26 rotations)
 * @return string Formatted HTML with rotated text or all brute force options
 */
function handle_rot(array $req): string {
    // Get and validate input
    $input = req_get($req, 'rot', '');
    
    if (empty($input)) {
        return formatOutput("Input text is required.", type: "danger");
    }
    
    if (strlen($input) > 100000) {
        return formatOutput("Input must be at most 100,000 characters.", type: "danger");
    }

    $bruteforce = req_bool($req, 'bruteforce');

    if ($bruteforce) {
        $output = "";
        for ($i = 0; $i < 26; $i++) {
            $rotated = str_rot($input, $i);
            $output .= output_copyable($rotated, "ROT" . $i);
        }
        return $output;
    }

    // Normalize rotation amount to 0-25 so larger/negative inputs still work
    $rotationsRaw = req_int($req, 'rotations', 13);
    $rotations = (($rotationsRaw % 26) + 26) % 26;

    $result = str_rot($input, $rotations);

    $note = '';
    if ($rotationsRaw !== $rotations) {
        $note = formatOutput("Rotation normalized to $rotations (input: $rotationsRaw).", type: "info");
    }

    return $note . output_copyable($result);
}

/**
 * Handle OpenSSL encryption/decryption requests
 *
 * Encrypts or decrypts data using OpenSSL ciphers. Supports automatic IV generation
 * if not provided, with warning about security implications.
 *
 * @param array $req Request array containing: 'tool' (encrypt|decrypt), 'openssl' (input data),
 *                   'cipher' (algorithm), 'key', 'iv' (initialization vector)
 * @return string Formatted HTML with result, warnings, and encryption details
 */
function handle_openssl(array $req): string {
    // Validate tool selection
    $allowedTools = ['encrypt', 'decrypt'];
    $tool = req_get($req, 'tool');
    if (!in_array($tool, $allowedTools)) {
        return formatOutput("Invalid tool selected.", type: "danger");
    }

    // Get and validate input data
    $string = req_get($req, 'openssl', '');
    if (strlen($string) > 1000000) {
        return formatOutput("Input must be at most 1,000,000 characters.", type: "danger");
    }

    // Get and validate key
    $key = req_get($req, 'key', '');
    if (strlen($key) > 1000) {
        return formatOutput("Key must be at most 1,000 characters.", type: "danger");
    }

    // Validate cipher and IV
    $cipher = req_get($req, 'cipher', 'aes-256-cbc');
    $iv = req_get($req, 'iv', '');

    // Ensure cipher is not empty or null
    if (empty($cipher) || $cipher === 'null' || $cipher === null) {
        $cipher = 'aes-256-cbc';
    }

    $validCiphers = openssl_get_cipher_methods();
    if (!in_array($cipher, $validCiphers)) {
        return formatOutput("Cipher `" . htmlspecialchars($cipher ?? 'null') . "` is not supported.", type: "danger");
    }

    $warnings = '';

    // Generate IV if not provided (store as hex for display)
    $ivHex = $iv;
    if (empty($iv)) {
        $ivlen = openssl_cipher_iv_length($cipher);
        if ($ivlen === false || $ivlen <= 0) {
            return formatOutput("Failed to determine IV length for cipher `" . htmlspecialchars($cipher) . "`.", type: "danger");
        }
        $ivHex = bin2hex(openssl_random_pseudo_bytes($ivlen));
        $warnings .= formatOutput("No IV specified, using random IV: $ivHex", type: "warning");
    } else {
        // Validate that provided IV is a valid hex string
        if (!ctype_xdigit($ivHex)) {
            return formatOutput("Invalid IV format. IV must be a valid hexadecimal string (0-9, a-f, A-F).", type: "danger");
        }
        // Validate IV length matches cipher requirement
        $ivlen = openssl_cipher_iv_length($cipher);
        if ($ivlen !== false && strlen($ivHex) !== ($ivlen * 2)) {
            return formatOutput("Invalid IV length. For cipher '$cipher', IV must be exactly " . ($ivlen * 2) . " hexadecimal characters (" . $ivlen . " bytes).", type: "danger");
        }
    }

    // Convert hex IV to binary for openssl functions
    $ivBinary = hex2bin($ivHex);
    if ($ivBinary === false) {
        return formatOutput("Invalid IV format. IV must be a valid hex string.", type: "danger");
    }

    if (empty($key)) {
        $warnings .= formatOutput("No key specified, <b>this is unsafe</b>.", type: "warning");
    }

    $result = match($tool) {
        'encrypt' => openssl_encrypt($string, $cipher, $key, iv: $ivBinary),
        'decrypt' => openssl_decrypt($string, $cipher, $key, iv: $ivBinary),
        default => ''
    };

    if (empty($result)) {
        $result = "[empty]";
    }

    $output = $warnings;
    $output .= output_copyable($result);
    $output .= "<div style='margin-top: 20px; padding: 15px; background-color: rgba(255, 193, 7, 0.1); border-radius: 0.5rem;'>
        <strong>Encryption Details:</strong><br>
        🔑 <strong>Cipher:</strong> <code>" . htmlspecialchars($cipher ?? '') . "</code><br>
        🔓 <strong>Key:</strong> <code>" . htmlspecialchars($key ?? '') . "</code><br>
        📍 <strong>IV (Hex):</strong> <code>" . htmlspecialchars($ivHex ?? '') . "</code>
    </div>";

    return $output;
}

/**
 * Handle datetime unit conversion requests
 *
 * Converts time values between different units (seconds, minutes, hours, days, weeks, etc.)
 * Displays both decimal and integer results.
 *
 * @param array $req Request array containing: 'time' (value), 'timefrom_unit' (source unit),
 *                   'timeto_unit' (target unit)
 * @return string Formatted HTML with conversion results or error message
 */
function handle_datetime(array $req): string {
    // Validate time value is numeric
    $timeValidation = req_int_validated($req, 'time', -2147483648, 2147483647);
    if (!$timeValidation['valid']) {
        return formatOutput("Time value must be a valid number.", type: "danger");
    }
    $time = $timeValidation['value'];

    // Validate source and target units
    $validUnits = ['s', 'i', 'h', 'd', 'w', 'M', 'y'];
    $fromUnit = req_get($req, 'timefrom_unit');
    $toUnit = req_get($req, 'timeto_unit');

    if (empty($fromUnit) || empty($toUnit)) {
        return formatOutput("You must select both source and target units.", type: "danger");
    }

    if (!in_array($fromUnit, $validUnits)) {
        return formatOutput("Invalid source time unit.", type: "danger");
    }

    if (!in_array($toUnit, $validUnits)) {
        return formatOutput("Invalid target time unit.", type: "danger");
    }

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

/**
 * Handle string transformation requests
 *
 * Applies various text transformations such as case conversion, whitespace handling,
 * URL encoding, etc. Supports 30+ different string manipulation tools.
 *
 * @param array $req Request array containing: 'string' (input text), 'tool' (transformation type),
 *                   'outputToTextbox' (optional, for direct output)
 * @return string Formatted HTML with transformed string or plain text
 */
function handle_stringtools(array $req): string {
    // Get and validate input string
    $string = req_get($req, 'string', '');
    
    if (strlen($string) > 1000000) {
        return formatOutput("Input must be at most 1,000,000 characters.", type: "danger");
    }

    // Validate tool selection
    $allowedTools = [
        'trim', 'removewhitespace', 'reverse', 'repeat', 'shuffle', 'uppercase', 'lowercase',
        'titlecase', 'camelcase', 'slugify', 'kebabcase', 'randomcase', 'invertedcase',
        'l33t5p34k', 'crlf2lf', 'lf2crlf', 'formatlineendings', 'removehtmltags',
        'removepunctuation', 'removenewlines', 'removetabs', 'removespaces', 'removeslashes',
        'removebackslashes', 'removenonascii', 'removenonprintable', 'removewhitespaceext',
        'removenumbers', 'removeletters', 'removesymbols', 'removeextendedsymbols'
    ];
    $tool = req_get($req, 'tool', '');

    if (empty($tool) || !in_array($tool, $allowedTools)) {
        return formatOutput("Invalid tool selected.", type: "danger");
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
