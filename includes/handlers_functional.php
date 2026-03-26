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
        'hasher' => 'handle_hash',
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
        'qrcode' => 'handle_qrcode',
        'regex' => 'handle_regex',
        'brainfuck' => 'handle_brainfuck',
        'genid' => 'handle_genid',
        'jwt' => 'handle_jwt',
        'ssh_keygen' => 'handle_ssh_keygen',
        'keypair_generate' => 'handle_keypair_generate',
        'csr_generate' => 'handle_csr_generate',
        'pem_openssh_convert' => 'handle_pem_openssh_convert',
        'crypto_diagnostics' => 'handle_crypto_diagnostics',
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
 * @param array|null $useAsInput Optional use-as-input button config for two-way converters
 * @return string HTML formatted copyable output element
 */
function output_copyable(string $content, string $label = "", ?array $useAsInput = null): string {
    return "<div style='margin-bottom: 15px;'>" . copyableOutput($content, $label, $useAsInput) . "</div>";
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

    $hashRounds = isset($req['hashrounds']) ? (int) $req['hashrounds'] : 1;
    $hashRounds = max(1, min(1000, $hashRounds));
    
    $hashalgo = req_get($req, 'hashalgo', 'all');
    
    // Validate algorithm if provided and not 'all'
    if ($hashalgo !== 'all' && !in_array($hashalgo, hash_algos())) {
        return formatOutput("Invalid hash algorithm selected.", type: "danger");
    }
    
    $types = ($hashalgo !== 'all' && in_array($hashalgo, hash_algos())) 
        ? [$hashalgo] 
        : hash_algos();

    $output = "";
    $useAsInput = ['inputName' => 'hash'];
    foreach ($types as $type) {
        $hashValue = (string) $input;
        for ($round = 0; $round < $hashRounds; $round++) {
            $hashValue = hash($type, $hashValue);
        }
        $label = $hashRounds > 1 ? "$type ({$hashRounds} rounds)" : $type;
        $output .= output_copyable($hashValue, $label, $useAsInput);
    }
    
    return formatOutput($output);
}

/**
 * Handle random number generation requests
 *
 * Generates a random integer between two values (or within a digit range) with
 * optional seed support. Supports filtering by type: any, prime, odd, even.
 *
 * @param array $req Request array: 'numgenfrom'/'numgento' or 'numgenrangemode'='digits' with 'numgenmindig'/'numgenmaxdig'
 * @return string Formatted HTML with generated number and seed info if provided
 */
function handle_numgen(array $req): string {
    $rangeMode = isset($req['numgenrangemode']) && $req['numgenrangemode'] === 'digits' ? 'digits' : 'numeric';

    if ($rangeMode === 'digits') {
        $range = resolve_numgen_digit_range($req);
        if ($range === null) {
            return formatOutput("Invalid digit range. Use 1–20 for fixed length, or min ≤ max for range.", type: "danger");
        }
        [$from, $to] = $range;
    } else {
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
    }

    // Validate number type
    $allowedTypes = ['any', 'prime', 'composite', 'odd', 'even', 'square', 'palindromic', 'fibonacci'];
    $type = isset($req['numgentype']) && in_array($req['numgentype'], $allowedTypes, true)
        ? $req['numgentype']
        : 'any';

    // Resolve seed: validate custom seed if provided; invalid → use generated seed + warning
    $seed = null;
    $seedWarning = null;
    if (req_bool($req, 'numgenuseseed')) {
        $seedValidation = req_string($req, 'numgenseed', 1, 100);
        if (!$seedValidation['valid']) {
            return formatOutput($seedValidation['error'], type: "danger");
        }
        $resolved = resolve_numgen_seed($seedValidation['value']);
        $seed = $resolved['seed'];
        $seedWarning = $resolved['warning'];
    }

    // Validate quantity (1–500)
    $qty = isset($req['numgenqty']) ? (int) $req['numgenqty'] : 1;
    $qty = max(1, min(500, $qty));

    // Separator for multiple numbers (preset or custom, max 20 chars)
    $sepPresets = [ 'comma' => ', ', 'newline' => "\n", 'tab' => "\t", 'space' => ' ', 'pipe' => ' | ' ];
    $preset = isset($req['numgensep_preset']) ? $req['numgensep_preset'] : '';
    $separator = isset($sepPresets[$preset]) ? $sepPresets[$preset] : (isset($req['numgenseparator']) ? (string)$req['numgenseparator'] : ', ');
    $separator = mb_substr($separator, 0, 20);
    if ($separator === '') {
        $separator = ', ';
    }

    // Apply seed once so the same seed gives a reproducible sequence for multiple numbers
    if ($seed !== null) {
        mt_srand((int) $seed);
    }

    $results = [];
    for ($i = 0; $i < $qty; $i++) {
        $result = numGen($from, $to, null, $type);
        if (is_string($result)) {
            return $result;
        }
        $results[] = $result;
    }

    $joined = joinNumGenResults($results, $separator);
    $output = output_copyable($joined);

    if ($seedWarning) {
        $output .= formatOutput($seedWarning, 6, "warning");
    }
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
        $output .= copyableOutput($result, '', ['inputName' => 'base', 'swapNames' => ['from', 'to']]);
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

    $inputName = !empty($req['binhex']) ? 'binhex' : 'iphex';
    return output_copyable($output, '', ['inputName' => $inputName]);
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
            $output .= output_copyable($rotated, "ROT" . $i, ['inputName' => 'rot']);
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

    return $note . output_copyable($result, '', ['inputName' => 'rot']);
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
 * Converts time values from the selected source unit to all other units
 * (seconds, minutes, hours, days, weeks, months, years).
 *
 * @param array $req Request array containing: 'time' (value), 'timefrom_unit' (source unit)
 * @return string Formatted HTML with conversion results or error message
 */
function handle_datetime(array $req): string {
    // Validate time value is numeric
    $timeValidation = req_int_validated($req, 'time', -2147483648, 2147483647);
    if (!$timeValidation['valid']) {
        return formatOutput("Time value must be a valid number.", type: "danger");
    }
    $time = $timeValidation['value'];

    $validUnits = ['s', 'i', 'h', 'd', 'w', 'M', 'y'];
    $fromUnit = req_get($req, 'timefrom_unit');

    if (empty($fromUnit)) {
        return formatOutput("You must select a unit.", type: "danger");
    }

    if (!in_array($fromUnit, $validUnits)) {
        return formatOutput("Invalid source time unit.", type: "danger");
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

    $fromSeconds = $time * $units[$fromUnit][1];
    $fromLabel = "$time " . $units[$fromUnit][0];

    $output = '<div class="table-responsive"><table class="table table-dark table-striped table-hover align-middle mb-0" style="border: 1px solid #334155;">';
    $output .= '<caption class="text-start fw-bold" style="caption-side: top; color: var(--bs-body-color);">' . htmlspecialchars($fromLabel) . '</caption>';
    $output .= '<thead><tr><th>Unit</th><th>Value</th></tr></thead><tbody>';

    foreach ($units as $key => [$name, $factor]) {
        if ($key === $fromUnit) {
            continue;
        }
        $converted = round($fromSeconds / $factor, 6);
        $value = $converted . " " . $name;
        $output .= '<tr><td>' . htmlspecialchars($name) . '</td><td style="max-width: 280px;">' . copyableOutput($value, "") . '</td></tr>';
    }

    $output .= '</tbody></table></div>';
    return $output;
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

/**
 * Handle QR code generation requests
 *
 * Generates QR codes locally using the bundled QR library so the module
 * does not depend on a third-party API.
 *
 * @param array $req Request array containing: 'qrcode', 'size', 'ecc', 'margin', 'fg', 'bg'
 * @return string Formatted HTML with QR code image and download option
 */
function handle_qrcode(array $req): string {
    $data = trim(req_get($req, 'qrcode', ''));

    if ($data === '') {
        return formatOutput("Input data is required to generate a QR code.", type: "danger");
    }

    if (strlen($data) > 4000) {
        return formatOutput("Input data is too long. Maximum 4000 characters allowed.", type: "danger");
    }

    $size = req_int($req, 'size', 300);
    if (!in_array($size, [200, 300, 400, 500], true)) {
        $size = 300;
    }

    $ecc = strtoupper(req_get($req, 'ecc', 'M'));
    if (!in_array($ecc, ['L', 'M', 'Q', 'H'], true)) {
        $ecc = 'M';
    }

    $margin = req_int($req, 'margin', 4);
    $margin = max(0, min(10, $margin));

    $foreground = qrcode_normalize_hex(req_get($req, 'fg', '#000000'), '#000000');
    $background = qrcode_normalize_hex(req_get($req, 'bg', '#ffffff'), '#ffffff');

    try {
        $png = qrcode_generate_png($data, $size, $ecc, $margin, $foreground, $background);
    } catch (Throwable) {
        return formatOutput("Unable to generate the QR code locally right now.", type: "danger");
    }

    $qrDataUri = qrcode_png_data_uri($png);
    $escapedData = htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $escapedQrDataUri = htmlspecialchars($qrDataUri, ENT_QUOTES, 'UTF-8');

    $output = "<div style='text-align: center; padding: 20px;'>";
    $output .= "<img src='{$escapedQrDataUri}' alt='QR Code' width='{$size}' height='{$size}' style='max-width: 100%; height: auto; border: 2px solid #495057; border-radius: 0.5rem; background: {$background}; padding: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);' />";
    $output .= "<div style='margin-top: 20px; display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;'>";
    $output .= "<a href='{$escapedQrDataUri}' download='qrcode.png' class='btn btn-primary'>";
    $output .= icon('download') . " Download PNG";
    $output .= "</a>";
    $output .= "</div>";
    $output .= "<div style='margin-top: 20px; text-align: left; padding: 15px; background: rgba(255,255,255,0.04); border-radius: 0.5rem; border: 1px solid #495057;'>";
    $output .= "<div style='margin-bottom: 10px;'><strong>Encoded Data</strong></div>";
    $output .= "<code style='display: block; word-break: break-all; white-space: pre-wrap;'>{$escapedData}</code>";
    $output .= "<div style='margin-top: 15px; color: #adb5bd;'>";
    $output .= "<strong>Settings:</strong> {$size}x{$size}px, ECC {$ecc}, margin {$margin}, foreground {$foreground}, background {$background}";
    $output .= "</div>";
    $output .= "</div>";
    $output .= "</div>";

    return $output;
}

/**
 * Handle regex testing requests
 *
 * Tests regular expressions against input text, showing matches, groups,
 * and optional replacements. Supports common regex flags.
 *
 * @param array $req Request array containing: 'pattern', 'teststring', 'replacement', flags
 * @return string Formatted HTML with regex test results
 */
function handle_regex(array $req): string {
    // Get and validate input
    $pattern = req_get($req, 'pattern', '');
    $testString = req_get($req, 'teststring', '');
    $replacement = req_get($req, 'replacement', '');
    
    if (empty($pattern)) {
        return formatOutput("Regular expression pattern is required.", type: "danger");
    }
    
    if (empty($testString)) {
        return formatOutput("Test string is required.", type: "danger");
    }
    
    // Validate pattern length
    if (strlen($pattern) > 5000) {
        return formatOutput("Pattern is too long. Maximum 5000 characters allowed.", type: "danger");
    }
    
    // Validate test string length
    if (strlen($testString) > 100000) {
        return formatOutput("Test string is too long. Maximum 100,000 characters allowed.", type: "danger");
    }
    
    // Build regex flags
    $flags = '';
    if (req_bool($req, 'caseless')) {
        $flags .= 'i';
    }
    if (req_bool($req, 'multiline')) {
        $flags .= 'm';
    }
    
    // Remove delimiters if present (e.g., /pattern/ or #pattern#)
    $pattern = trim($pattern);
    $delimiter = '';
    if (preg_match('/^([^\w\s\\\\])(.*)\\1([gimsxADSUXu]*)$/', $pattern, $matches)) {
        $delimiter = $matches[1];
        $pattern = $matches[2];
        // Merge flags if provided in pattern
        if (!empty($matches[3])) {
            $existingFlags = str_replace('g', '', $matches[3]); // Remove 'g' as PHP doesn't use it
            $flags = implode('', array_unique(str_split($flags . $existingFlags)));
        }
    }
    
    // Add flags to pattern (PHP style: add flags at the end)
    $delim = $delimiter ?: '/';
    // Escape delimiter in pattern if it matches
    if (strpos($pattern, $delim) !== false && $delim !== '/') {
        $pattern = str_replace($delim, '\\' . $delim, $pattern);
    }
    $fullPattern = $delim . $pattern . $delim . $flags;
    
    $output = "<div style='font-family: monospace; font-size: 0.9rem;'>";
    
    // Test the regex
    $matchCount = 0;
    $allMatches = [];
    
    try {
        // Use preg_match_all for global matching (always get all matches)
        $matchCount = preg_match_all($fullPattern, $testString, $allMatches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
        
        if ($matchCount === false) {
            // Invalid regex
            $error = error_get_last();
            return formatOutput("Invalid regular expression pattern. Error: " . htmlspecialchars($error['message'] ?? 'Unknown error'), type: "danger");
        }
        
        // Display results
        $output .= "<div style='margin-bottom: 20px; padding: 15px; background: rgba(32, 201, 151, 0.1); border-radius: 0.5rem; border-left: 4px solid #20c997;'>";
        $output .= "<strong>" . icon('check-circle', 1, '#20c997') . " Pattern Valid</strong><br>";
        $output .= "<code style='word-break: break-all;'>" . htmlspecialchars($fullPattern) . "</code><br>";
        $output .= "<strong>Matches Found:</strong> " . ($matchCount > 0 ? "<span style='color: #20c997; font-weight: bold;'>{$matchCount}</span>" : "<span style='color: #dc3545;'>0</span>");
        $output .= "</div>";
        
        if ($matchCount > 0) {
            // Display matches
            $output .= "<div style='margin-bottom: 20px;'>";
            $output .= "<h5 style='margin-bottom: 15px;'>Match Details:</h5>";
            
            foreach ($allMatches as $matchIndex => $matchSet) {
                $output .= "<div style='margin-bottom: 15px; padding: 15px; background: rgba(13, 110, 253, 0.1); border-radius: 0.5rem; border-left: 4px solid #0d6efd;'>";
                $output .= "<strong>Match #" . ($matchIndex + 1) . ":</strong><br>";
                
                if (isset($matchSet[0]) && is_array($matchSet[0])) {
                    $fullMatch = $matchSet[0][0];
                    $offset = $matchSet[0][1];
                    $output .= "<div style='margin: 10px 0; padding: 10px; background: #0f172a; color: #e9ecef; border-radius: 0.25rem;'>";
                    $output .= "<strong>Full Match:</strong> <code style='color: #51cf66;'>" . htmlspecialchars($fullMatch) . "</code><br>";
                    $output .= "<strong>Position:</strong> {$offset}";
                    $output .= "</div>";
                }
                
                // Display capture groups
                if (count($matchSet) > 1) {
                    $output .= "<strong>Capture Groups:</strong><br>";
                    for ($i = 1; $i < count($matchSet); $i++) {
                        if (isset($matchSet[$i]) && is_array($matchSet[$i])) {
                            $group = $matchSet[$i][0];
                            $groupOffset = $matchSet[$i][1];
                            $output .= "<div style='margin: 5px 0; padding: 8px; background: rgba(255,255,255,0.1); border-radius: 0.25rem;'>";
                            $output .= "<strong>Group {$i}:</strong> <code style='color: #ffc107;'>" . htmlspecialchars($group) . "</code> (position: {$groupOffset})";
                            $output .= "</div>";
                        }
                    }
                }
                
                $output .= "</div>";
            }
            
            $output .= "</div>";
            
            // Show replacement if provided
            if (!empty($replacement)) {
                try {
                    $replaced = preg_replace($fullPattern, $replacement, $testString);
                    if ($replaced !== null) {
                        $output .= "<div style='margin-bottom: 20px; padding: 15px; background: rgba(255, 193, 7, 0.1); border-radius: 0.5rem; border-left: 4px solid #ffc107;'>";
                        $output .= "<h5 style='margin-bottom: 10px;'>Replacement Result:</h5>";
                        $output .= "<div style='padding: 10px; background: #0f172a; color: #e9ecef; border-radius: 0.25rem; white-space: pre-wrap; word-break: break-word;'>";
                        $output .= htmlspecialchars($replaced);
                        $output .= "</div>";
                        $output .= "</div>";
                    }
                } catch (Throwable $e) {
                    $output .= formatOutput("Error during replacement: " . htmlspecialchars($e->getMessage()), type: "warning");
                }
            }
        } else {
            $output .= "<div style='padding: 15px; background: rgba(220, 53, 69, 0.1); border-radius: 0.5rem; border-left: 4px solid #dc3545;'>";
            $output .= "<strong>" . icon('x-circle', 1, '#dc3545') . " No matches found</strong><br>";
            $output .= "The pattern does not match the test string.";
            $output .= "</div>";
        }
        
    } catch (Throwable $e) {
        return formatOutput("Error testing regex: " . htmlspecialchars($e->getMessage()), type: "danger");
    }
    
    $output .= "</div>";
    
    return $output;
}

/**
 * Handle Brainfuck conversion requests
 *
 * Converts text to Brainfuck code or executes Brainfuck code to produce text output.
 * Supports two modes:
 * - text2bf: Converts text to Brainfuck code that outputs that text
 * - bf2text: Executes Brainfuck code and captures the output
 *
 * @param array $req Request array containing: 'brainfuck' (input), 'mode' (text2bf|bf2text)
 * @return string Formatted HTML with conversion result or error message
 */
function handle_brainfuck(array $req): string {
    $input = req_get($req, 'brainfuck', '');
    $mode = req_get($req, 'mode', 'text2bf');
    
    if (empty($input)) {
        return formatOutput("Input cannot be empty.", type: "danger");
    }
    
    // Validate input length
    if (strlen($input) > 100000) {
        return formatOutput("Input must be at most 100,000 characters.", type: "danger");
    }
    
    try {
        if ($mode === 'text2bf') {
            // Convert text to Brainfuck code
            $bfCode = textToBrainfuck($input);
            $output = "<div style='margin-bottom: 20px;'>";
            $output .= "<div style='margin-bottom: 15px;'><strong>Text → Brainfuck</strong></div>";
            $output .= copyableOutput($bfCode, '', ['inputName' => 'brainfuck', 'setSelectName' => 'mode', 'setSelectValue' => 'bf2text', 'setSelectValueUndo' => 'text2bf']);
            $output .= "<div style='margin-top: 15px; padding: 12px; background-color: rgba(255, 193, 7, 0.15); border-radius: 0.5rem;'>";
            $output .= "<strong>📊 Stats:</strong><br>";
            $output .= "Input length: <code>" . strlen($input) . " characters</code><br>";
            $output .= "Brainfuck code length: <code>" . strlen($bfCode) . " characters</code><br>";
            $output .= "Ratio: <code>" . number_format(strlen($bfCode) / strlen($input), 2) . "x</code>";
            $output .= "</div>";
            $output .= "</div>";
            return $output;
        } elseif ($mode === 'bf2text') {
            // Execute Brainfuck code
            $result = executeBrainfuck($input);
            if ($result['success']) {
                $output = "<div style='margin-bottom: 20px;'>";
                $output .= "<div style='margin-bottom: 15px;'><strong>Brainfuck → Text</strong></div>";
                $output .= copyableOutput($result['output'], '', ['inputName' => 'brainfuck', 'setSelectName' => 'mode', 'setSelectValue' => 'text2bf', 'setSelectValueUndo' => 'bf2text']);
                if (!empty($result['warnings'])) {
                    $output .= "<div style='margin-top: 15px; padding: 12px; background-color: rgba(255, 193, 7, 0.15); border-radius: 0.5rem;'>";
                    $output .= "<strong>⚠️ Warnings:</strong><br>";
                    $output .= htmlspecialchars($result['warnings']);
                    $output .= "</div>";
                }
                $output .= "</div>";
                return $output;
            } else {
                return formatOutput("Brainfuck execution error: " . htmlspecialchars($result['error']), type: "danger");
            }
        } else {
            return formatOutput("Invalid mode specified.", type: "danger");
        }
    } catch (Exception $e) {
        return formatOutput("Error: " . htmlspecialchars($e->getMessage()), type: "danger");
    }
}

/**
 * Convert text to Brainfuck code
 *
 * Generates Brainfuck code that outputs the given text by setting each cell
 * to the ASCII value of each character and outputting it.
 *
 * @param string $text The text to convert
 * @return string Brainfuck code that outputs the text
 */
function textToBrainfuck(string $text): string {
    $bfCode = '';
    $currentValue = 0;
    
    for ($i = 0; $i < strlen($text); $i++) {
        $targetValue = ord($text[$i]);
        $diff = $targetValue - $currentValue;
        
        if ($diff == 0) {
            // Already at target value, just output
            $bfCode .= '.';
        } else {
            // Calculate the most efficient way to reach target
            // Use absolute value and determine direction
            if (abs($diff) <= 10) {
                // Small difference: just increment/decrement
                if ($diff > 0) {
                    $bfCode .= str_repeat('+', $diff);
                } else {
                    $bfCode .= str_repeat('-', -$diff);
                }
            } else {
                // Large difference: reset to 0 and build up
                // Reset current value to 0
                if ($currentValue > 0) {
                    $bfCode .= str_repeat('-', $currentValue);
                }
                $currentValue = 0;
                
                // Build up to target
                $bfCode .= str_repeat('+', $targetValue);
            }
            
            $bfCode .= '.';
            $currentValue = $targetValue;
        }
        
        // Move to next cell for next character (optional optimization)
        // For simplicity, we'll reuse the same cell
    }
    
    return $bfCode;
}

/**
 * Execute Brainfuck code and capture output
 *
 * Implements a Brainfuck interpreter with:
 * - 30,000 cell tape (standard Brainfuck)
 * - Cell values 0-255 (wrapping)
 * - Input support (reads from empty string if not provided)
 * - Loop support with bracket matching
 *
 * @param string $code The Brainfuck code to execute
 * @param string $input Optional input string for ',' commands
 * @return array ['success' => bool, 'output' => string, 'error' => string|null, 'warnings' => string]
 */
function executeBrainfuck(string $code, string $input = ''): array {
    $tape = array_fill(0, 30000, 0);
    $pointer = 0;
    $output = '';
    $inputIndex = 0;
    $codeIndex = 0;
    $codeLength = strlen($code);
    $maxSteps = 10000000; // Safety limit to prevent infinite loops
    $stepCount = 0;
    $warnings = '';
    
    // Validate brackets are balanced
    $bracketCount = 0;
    for ($i = 0; $i < $codeLength; $i++) {
        if ($code[$i] === '[') $bracketCount++;
        if ($code[$i] === ']') $bracketCount--;
        if ($bracketCount < 0) {
            return ['success' => false, 'output' => '', 'error' => 'Unmatched closing bracket at position ' . $i, 'warnings' => ''];
        }
    }
    if ($bracketCount !== 0) {
        return ['success' => false, 'output' => '', 'error' => 'Unmatched opening brackets', 'warnings' => ''];
    }
    
    // Precompute bracket pairs for efficient loop handling
    $bracketPairs = [];
    $stack = [];
    for ($i = 0; $i < $codeLength; $i++) {
        if ($code[$i] === '[') {
            $stack[] = $i;
        } elseif ($code[$i] === ']') {
            if (empty($stack)) {
                return ['success' => false, 'output' => '', 'error' => 'Unmatched closing bracket at position ' . $i, 'warnings' => ''];
            }
            $start = array_pop($stack);
            $bracketPairs[$start] = $i;
            $bracketPairs[$i] = $start;
        }
    }
    
    // Execute the code
    while ($codeIndex < $codeLength && $stepCount < $maxSteps) {
        $stepCount++;
        $command = $code[$codeIndex];
        
        switch ($command) {
            case '>':
                $pointer++;
                if ($pointer >= 30000) {
                    $pointer = 0; // Wrap around
                }
                break;
                
            case '<':
                $pointer--;
                if ($pointer < 0) {
                    $pointer = 29999; // Wrap around
                }
                break;
                
            case '+':
                $tape[$pointer] = ($tape[$pointer] + 1) % 256;
                break;
                
            case '-':
                $tape[$pointer] = ($tape[$pointer] - 1 + 256) % 256;
                break;
                
            case '.':
                $output .= chr($tape[$pointer]);
                break;
                
            case ',':
                if ($inputIndex < strlen($input)) {
                    $tape[$pointer] = ord($input[$inputIndex]);
                    $inputIndex++;
                } else {
                    $tape[$pointer] = 0; // EOF: set to 0
                }
                break;
                
            case '[':
                if ($tape[$pointer] == 0) {
                    // Jump to matching ']'
                    $codeIndex = $bracketPairs[$codeIndex];
                }
                break;
                
            case ']':
                if ($tape[$pointer] != 0) {
                    // Jump back to matching '['
                    $codeIndex = $bracketPairs[$codeIndex];
                }
                break;
        }
        
        $codeIndex++;
    }
    
    if ($stepCount >= $maxSteps) {
        $warnings = "Execution stopped after {$maxSteps} steps (possible infinite loop).";
    }
    
    return [
        'success' => true,
        'output' => $output,
        'error' => null,
        'warnings' => $warnings
    ];
}

/**
 * Handle ID generator requests (UUIDv4, ULID, NanoID)
 *
 * @param array $req Request array containing: 'idtype', 'idqty', 'nanoid_length', 'id_uppercase'
 * @return string Formatted HTML with generated IDs
 */
function handle_genid(array $req): string {
    $idType = req_get($req, 'idtype', 'uuid4');
    $allowedTypes = ['uuid4', 'ulid', 'nanoid'];
    if (!in_array($idType, $allowedTypes, true)) {
        return formatOutput("Invalid ID type selected.", type: "danger");
    }

    $qty = req_int($req, 'idqty', 1);
    $qty = max(1, min(500, $qty));

    $nanoLength = req_int($req, 'nanoid_length', 21);
    $nanoLength = max(6, min(128, $nanoLength));

    $uppercase = req_bool($req, 'id_uppercase');
    $ids = [];
    for ($i = 0; $i < $qty; $i++) {
        $id = match ($idType) {
            'uuid4' => gen_uuid4(),
            'ulid' => gen_ulid(),
            'nanoid' => gen_nanoid($nanoLength),
            default => '',
        };
        $ids[] = $uppercase ? strtoupper($id) : $id;
    }

    $label = strtoupper($idType) . ($idType === 'nanoid' ? " ({$nanoLength} chars)" : "");
    $output = output_copyable(implode("\n", $ids), $label);
    $output .= "<div style='margin-top: 8px; font-size: 0.85rem; opacity: 0.75;'>Generated <strong>{$qty}</strong> ID" . ($qty > 1 ? "s" : "") . ".</div>";

    return $output;
}

function crypto_available_key_algorithms(): array {
    $algorithms = ['rsa', 'ecdsa'];
    if (defined('OPENSSL_KEYTYPE_ED25519')) {
        $algorithms[] = 'ed25519';
    }
    return $algorithms;
}

function crypto_resolve_requested_algorithms(string $selected): array {
    $available = crypto_available_key_algorithms();
    if ($selected === 'all-available') {
        return $available;
    }
    if (in_array($selected, $available, true)) {
        return [$selected];
    }
    return [];
}

function crypto_make_key_config(string $algorithm, int $rsaBits = 4096, string $curve = 'prime256v1'): ?array {
    if ($algorithm === 'rsa') {
        $bits = in_array($rsaBits, [2048, 3072, 4096], true) ? $rsaBits : 4096;
        return [
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'private_key_bits' => $bits,
        ];
    }

    if ($algorithm === 'ecdsa') {
        $allowedCurves = ['prime256v1', 'secp384r1', 'secp521r1'];
        $resolvedCurve = in_array($curve, $allowedCurves, true) ? $curve : 'prime256v1';
        return [
            'private_key_type' => OPENSSL_KEYTYPE_EC,
            'curve_name' => $resolvedCurve,
        ];
    }

    if ($algorithm === 'ed25519') {
        if (!defined('OPENSSL_KEYTYPE_ED25519')) {
            return null;
        }
        return [
            'private_key_type' => OPENSSL_KEYTYPE_ED25519,
        ];
    }

    return null;
}

function crypto_generate_keypair(string $algorithm, int $rsaBits = 4096, string $curve = 'prime256v1', string $passphrase = ''): array {
    $config = crypto_make_key_config($algorithm, $rsaBits, $curve);
    if ($config === null) {
        return ['ok' => false, 'error' => "Algorithm {$algorithm} is not available on this host."];
    }

    $privateKey = openssl_pkey_new($config);
    if ($privateKey === false) {
        return ['ok' => false, 'error' => "Unable to generate {$algorithm} keypair."];
    }

    $exportOptions = [];
    if ($passphrase !== '') {
        $exportOptions = ['cipher' => 'aes-256-cbc'];
    }

    $privatePem = '';
    $privateExported = openssl_pkey_export($privateKey, $privatePem, $passphrase, $exportOptions);
    if ($privateExported === false || $privatePem === '') {
        return ['ok' => false, 'error' => "Unable to export private key for {$algorithm}."];
    }

    $details = openssl_pkey_get_details($privateKey);
    if (!is_array($details) || empty($details['key'])) {
        return ['ok' => false, 'error' => "Unable to export public key for {$algorithm}."];
    }

    return [
        'ok' => true,
        'algorithm' => $algorithm,
        'private_pem' => $privatePem,
        'public_pem' => $details['key'],
    ];
}

function crypto_data_download_link(string $filename, string $content, string $label): string {
    $href = 'data:text/plain;charset=utf-8,' . rawurlencode($content);
    $safeHref = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
    $safeFilename = htmlspecialchars($filename, ENT_QUOTES, 'UTF-8');
    $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    return "<a class='btn btn-outline-light btn-sm me-2 mb-2' href='{$safeHref}' download='{$safeFilename}'>" . icon('download') . " {$safeLabel}</a>";
}

function crypto_render_key_output(array $items, string $title): string {
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $output = "<div class='card border-info mb-3'><h5 class='card-header'>{$safeTitle}</h5><div class='card-body'>";

    foreach ($items as $item) {
        $label = htmlspecialchars((string) ($item['label'] ?? ''), ENT_QUOTES, 'UTF-8');
        $content = (string) ($item['content'] ?? '');
        $filename = (string) ($item['filename'] ?? '');
        $output .= output_copyable($content, $label);
        if ($filename !== '') {
            $output .= "<div style='margin-bottom: 16px;'>" . crypto_data_download_link($filename, $content, "Download {$label}") . "</div>";
        }
    }

    $output .= "</div></div>";
    return $output;
}

function crypto_ssh_pack_string(string $value): string {
    return pack('N', strlen($value)) . $value;
}

function crypto_ssh_pack_mpint(string $value): string {
    $value = ltrim($value, "\x00");
    if ($value === '') {
        return pack('N', 0);
    }
    if ((ord($value[0]) & 0x80) !== 0) {
        $value = "\x00" . $value;
    }
    return pack('N', strlen($value)) . $value;
}

function crypto_openssh_curve_name(string $curve): ?string {
    return match ($curve) {
        'prime256v1' => 'nistp256',
        'secp384r1' => 'nistp384',
        'secp521r1' => 'nistp521',
        default => null,
    };
}

function crypto_public_pem_to_openssh(string $publicPem, string $comment = ''): array {
    $publicHandle = openssl_pkey_get_public($publicPem);
    if ($publicHandle === false) {
        return ['ok' => false, 'error' => 'Unable to parse public key PEM for OpenSSH conversion.'];
    }

    $details = openssl_pkey_get_details($publicHandle);
    if (!is_array($details)) {
        return ['ok' => false, 'error' => 'Unable to read public key details for OpenSSH conversion.'];
    }

    if (($details['type'] ?? null) === OPENSSL_KEYTYPE_RSA && isset($details['rsa']['e'], $details['rsa']['n'])) {
        $blob = crypto_ssh_pack_string('ssh-rsa')
            . crypto_ssh_pack_mpint($details['rsa']['e'])
            . crypto_ssh_pack_mpint($details['rsa']['n']);
        $line = 'ssh-rsa ' . base64_encode($blob);
        if ($comment !== '') {
            $line .= ' ' . $comment;
        }
        return ['ok' => true, 'line' => $line, 'type' => 'ssh-rsa'];
    }

    if (($details['type'] ?? null) === OPENSSL_KEYTYPE_EC && isset($details['ec']['curve_name'], $details['ec']['x'], $details['ec']['y'])) {
        $sshCurve = crypto_openssh_curve_name((string) $details['ec']['curve_name']);
        if ($sshCurve === null) {
            return ['ok' => false, 'error' => 'Unsupported ECDSA curve for OpenSSH export.'];
        }
        $point = "\x04" . $details['ec']['x'] . $details['ec']['y'];
        $algo = 'ecdsa-sha2-' . $sshCurve;
        $blob = crypto_ssh_pack_string($algo)
            . crypto_ssh_pack_string($sshCurve)
            . crypto_ssh_pack_string($point);
        $line = $algo . ' ' . base64_encode($blob);
        if ($comment !== '') {
            $line .= ' ' . $comment;
        }
        return ['ok' => true, 'line' => $line, 'type' => $algo];
    }

    if (isset($details['ed25519']['pub_key']) && is_string($details['ed25519']['pub_key'])) {
        $raw = $details['ed25519']['pub_key'];
        if (strlen($raw) === 32) {
            $blob = crypto_ssh_pack_string('ssh-ed25519') . crypto_ssh_pack_string($raw);
            $line = 'ssh-ed25519 ' . base64_encode($blob);
            if ($comment !== '') {
                $line .= ' ' . $comment;
            }
            return ['ok' => true, 'line' => $line, 'type' => 'ssh-ed25519'];
        }
    }

    return ['ok' => false, 'error' => 'OpenSSH export is unavailable for this key type on current OpenSSL/PHP build.'];
}

function crypto_openssh_to_pem_with_ssh_keygen(string $opensshLine): array {
    if (!function_exists('shell_exec')) {
        return ['ok' => false, 'error' => 'shell_exec() is disabled on this server.'];
    }

    $sshKeygenPath = trim((string) @shell_exec('command -v ssh-keygen 2>/dev/null'));
    if ($sshKeygenPath === '') {
        return ['ok' => false, 'error' => 'ssh-keygen is not available on this host.'];
    }

    $tempBase = tempnam(sys_get_temp_dir(), 'sshpub_');
    if ($tempBase === false) {
        return ['ok' => false, 'error' => 'Unable to create temporary file for conversion.'];
    }
    $inputPath = $tempBase . '.pub';
    @rename($tempBase, $inputPath);

    if (@file_put_contents($inputPath, trim($opensshLine) . PHP_EOL) === false) {
        @unlink($inputPath);
        return ['ok' => false, 'error' => 'Unable to write temporary OpenSSH key file.'];
    }

    $cmd = escapeshellarg($sshKeygenPath) . ' -f ' . escapeshellarg($inputPath) . ' -e -m PKCS8 2>&1';
    $output = (string) @shell_exec($cmd);
    @unlink($inputPath);

    if (strpos($output, 'BEGIN PUBLIC KEY') === false) {
        $trimmed = trim($output);
        return ['ok' => false, 'error' => $trimmed !== '' ? $trimmed : 'Unable to convert OpenSSH key to PEM via ssh-keygen.'];
    }

    return ['ok' => true, 'pem' => trim($output) . PHP_EOL];
}

function handle_keypair_generate(array $req): string {
    $algorithmRequest = req_get($req, 'algorithm', 'all-available');
    $algorithm = is_string($algorithmRequest) ? $algorithmRequest : 'all-available';
    $algorithms = crypto_resolve_requested_algorithms($algorithm);
    if (empty($algorithms)) {
        return formatOutput("No supported algorithm selected for key generation.", type: "danger");
    }

    $passphrase = trim((string) req_get($req, 'passphrase', ''));
    if (strlen($passphrase) > 256) {
        return formatOutput("Passphrase must be at most 256 characters.", type: "danger");
    }

    $rsaBits = req_int($req, 'rsa_bits', 4096);
    $curve = (string) req_get($req, 'ecdsa_curve', 'prime256v1');

    $output = '';
    foreach ($algorithms as $algo) {
        $result = crypto_generate_keypair($algo, $rsaBits, $curve, $passphrase);
        if (!$result['ok']) {
            $output .= formatOutput((string) $result['error'], type: "warning");
            continue;
        }

        $suffix = $algo === 'rsa' ? "-{$rsaBits}" : ($algo === 'ecdsa' ? "-{$curve}" : '');
        $items = [
            [
                'label' => strtoupper($algo) . ' Private Key (PEM)',
                'content' => (string) $result['private_pem'],
                'filename' => "private-{$algo}{$suffix}.pem",
            ],
            [
                'label' => strtoupper($algo) . ' Public Key (PEM)',
                'content' => (string) $result['public_pem'],
                'filename' => "public-{$algo}{$suffix}.pem",
            ],
        ];
        $output .= crypto_render_key_output($items, strtoupper($algo) . " Keypair");
    }

    return $output === '' ? formatOutput("Unable to generate any keypairs.", type: "danger") : $output;
}

function handle_pem_openssh_convert(array $req): string {
    $mode = (string) req_get($req, 'convert_mode', 'pem_to_openssh');
    $comment = trim((string) req_get($req, 'ssh_comment', 'generated-by-phprand'));
    if (strlen($comment) > 200) {
        return formatOutput("SSH comment must be at most 200 characters.", type: "danger");
    }

    if ($mode === 'pem_to_openssh') {
        $pem = trim((string) req_get($req, 'public_pem', ''));
        if ($pem === '') {
            return formatOutput("Public PEM input is required.", type: "danger");
        }
        $converted = crypto_public_pem_to_openssh($pem, $comment);
        if (($converted['ok'] ?? false) !== true) {
            return formatOutput((string) ($converted['error'] ?? 'Conversion failed.'), type: "danger");
        }
        return crypto_render_key_output([
            [
                'label' => 'OpenSSH Public Key',
                'content' => (string) $converted['line'],
                'filename' => 'converted.pub',
            ],
        ], 'PEM -> OpenSSH');
    }

    if ($mode === 'openssh_to_pem') {
        $openssh = trim((string) req_get($req, 'openssh_public', ''));
        if ($openssh === '') {
            return formatOutput("OpenSSH public key input is required.", type: "danger");
        }
        $converted = crypto_openssh_to_pem_with_ssh_keygen($openssh);
        if (($converted['ok'] ?? false) !== true) {
            return formatOutput((string) ($converted['error'] ?? 'Conversion failed.'), type: "danger");
        }
        return crypto_render_key_output([
            [
                'label' => 'PEM Public Key',
                'content' => (string) $converted['pem'],
                'filename' => 'converted-public.pem',
            ],
        ], 'OpenSSH -> PEM');
    }

    return formatOutput("Invalid converter mode selected.", type: "danger");
}

function handle_crypto_diagnostics(array $req): string {
    $algorithms = crypto_available_key_algorithms();
    $rows = [];
    $okBadge = "<span class='badge bg-success text-white'>" . icon('check-circle') . " OK</span>";
    $warnBadge = "<span class='badge bg-warning text-dark'>" . icon('exclamation-triangle') . " Warning</span>";
    $errBadge = "<span class='badge bg-danger text-white'>" . icon('x-circle') . " Error</span>";

    foreach (['rsa', 'ecdsa', 'ed25519'] as $algo) {
        if (!in_array($algo, $algorithms, true)) {
            $rows[] = "<tr><td>" . strtoupper($algo) . "</td><td>{$warnBadge}</td><td>{$warnBadge}</td></tr>";
            continue;
        }
        $generated = crypto_generate_keypair($algo);
        if (($generated['ok'] ?? false) !== true) {
            $rows[] = "<tr><td>" . strtoupper($algo) . "</td><td>{$errBadge}</td><td>{$warnBadge}</td></tr>";
            continue;
        }
        $openssh = crypto_public_pem_to_openssh((string) $generated['public_pem']);
        $opensshStatus = (($openssh['ok'] ?? false) === true) ? $okBadge : $warnBadge;
        $rows[] = "<tr><td>" . strtoupper($algo) . "</td><td>{$okBadge}</td><td>{$opensshStatus}</td></tr>";
    }

    $sshKeygenPath = function_exists('shell_exec')
        ? trim((string) @shell_exec('command -v ssh-keygen 2>/dev/null'))
        : '';
    $sshKeygenStatus = $sshKeygenPath !== ''
        ? "<span class='badge bg-success text-white me-2'>" . icon('check-circle') . " OK</span><code>" . htmlspecialchars($sshKeygenPath, ENT_QUOTES, 'UTF-8') . "</code>"
        : "<span class='badge bg-warning text-dark'>" . icon('exclamation-triangle') . " Not found</span>";

    $output = "<div class='card border-info mb-3'><h5 class='card-header'>Crypto Runtime Diagnostics</h5><div class='card-body'>";
    $output .= "<div class='mb-3'><strong>Available key algorithms:</strong> " . htmlspecialchars(implode(', ', $algorithms), ENT_QUOTES, 'UTF-8') . "</div>";
    $output .= "<div class='mb-3'><strong>ssh-keygen binary:</strong> {$sshKeygenStatus}</div>";
    $output .= "<div class='table-responsive'><table class='table table-dark table-striped'><thead><tr><th>Algorithm</th><th>OpenSSL Keygen</th><th>OpenSSH Export</th></tr></thead><tbody>" . implode('', $rows) . "</tbody></table></div>";
    $output .= "</div></div>";

    return $output;
}

function handle_ssh_keygen(array $req): string {
    $comment = trim((string) req_get($req, 'ssh_comment', 'generated-by-phprand'));
    if ($comment !== '' && strlen($comment) > 200) {
        return formatOutput("SSH comment must be at most 200 characters.", type: "danger");
    }

    $algorithmRequest = req_get($req, 'algorithm', 'all-available');
    $algorithm = is_string($algorithmRequest) ? $algorithmRequest : 'all-available';
    $algorithms = crypto_resolve_requested_algorithms($algorithm);
    if (empty($algorithms)) {
        return formatOutput("No supported algorithm selected for key generation.", type: "danger");
    }

    $passphrase = trim((string) req_get($req, 'passphrase', ''));
    if (strlen($passphrase) > 256) {
        return formatOutput("Passphrase must be at most 256 characters.", type: "danger");
    }

    $rsaBits = req_int($req, 'rsa_bits', 4096);
    $curve = (string) req_get($req, 'ecdsa_curve', 'prime256v1');

    $output = formatOutput(
        "Generated PEM key material and OpenSSH public keys when supported. Comment: " . htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'),
        type: "info"
    );

    foreach ($algorithms as $algo) {
        $result = crypto_generate_keypair($algo, $rsaBits, $curve, $passphrase);
        if (!$result['ok']) {
            $output .= formatOutput((string) $result['error'], type: "warning");
            continue;
        }

        $suffix = $algo === 'rsa' ? "-{$rsaBits}" : ($algo === 'ecdsa' ? "-{$curve}" : '');
        $items = [
            [
                'label' => strtoupper($algo) . ' Private Key (PEM)',
                'content' => (string) $result['private_pem'],
                'filename' => "private-{$algo}{$suffix}.pem",
            ],
            [
                'label' => strtoupper($algo) . ' Public Key (PEM)',
                'content' => (string) $result['public_pem'],
                'filename' => "public-{$algo}{$suffix}.pem",
            ],
        ];

        $sshLine = crypto_public_pem_to_openssh((string) $result['public_pem'], $comment);
        if (($sshLine['ok'] ?? false) === true) {
            $items[] = [
                'label' => strtoupper($algo) . ' Public Key (OpenSSH)',
                'content' => (string) $sshLine['line'],
                'filename' => "public-{$algo}{$suffix}.pub",
            ];
        } else {
            $output .= formatOutput(
                strtoupper($algo) . ': ' . (string) ($sshLine['error'] ?? 'OpenSSH export unavailable.'),
                type: "warning"
            );
        }

        $output .= crypto_render_key_output($items, strtoupper($algo) . " SSH Key Material");
    }

    return $output;
}

function handle_csr_generate(array $req): string {
    $algorithm = (string) req_get($req, 'algorithm', 'rsa');
    if ($algorithm === 'all-available') {
        $algorithm = 'rsa';
    }
    $resolved = crypto_resolve_requested_algorithms($algorithm);
    if (empty($resolved)) {
        return formatOutput("Selected algorithm is not supported for CSR generation.", type: "danger");
    }
    $algorithm = $resolved[0];

    $passphrase = trim((string) req_get($req, 'passphrase', ''));
    if (strlen($passphrase) > 256) {
        return formatOutput("Passphrase must be at most 256 characters.", type: "danger");
    }

    $rsaBits = req_int($req, 'rsa_bits', 4096);
    $curve = (string) req_get($req, 'ecdsa_curve', 'prime256v1');
    $keyResult = crypto_generate_keypair($algorithm, $rsaBits, $curve, $passphrase);
    if (!$keyResult['ok']) {
        return formatOutput((string) $keyResult['error'], type: "danger");
    }

    $cn = trim((string) req_get($req, 'csr_cn', ''));
    if ($cn === '') {
        return formatOutput("Common Name (CN) is required for CSR generation.", type: "danger");
    }

    $dn = [
        'commonName' => $cn,
        'organizationName' => trim((string) req_get($req, 'csr_o', '')),
        'organizationalUnitName' => trim((string) req_get($req, 'csr_ou', '')),
        'countryName' => trim((string) req_get($req, 'csr_c', '')),
        'stateOrProvinceName' => trim((string) req_get($req, 'csr_st', '')),
        'localityName' => trim((string) req_get($req, 'csr_l', '')),
        'emailAddress' => trim((string) req_get($req, 'csr_email', '')),
    ];
    $dn = array_filter($dn, fn($v) => $v !== '');

    $privateHandle = openssl_pkey_get_private((string) $keyResult['private_pem'], $passphrase);
    if ($privateHandle === false) {
        return formatOutput("Unable to load generated private key for CSR creation.", type: "danger");
    }

    $csr = openssl_csr_new($dn, $privateHandle, ['digest_alg' => 'sha256']);
    if ($csr === false) {
        return formatOutput("Unable to generate CSR with provided subject fields.", type: "danger");
    }

    $csrPem = '';
    if (!openssl_csr_export($csr, $csrPem) || $csrPem === '') {
        return formatOutput("Unable to export CSR in PEM format.", type: "danger");
    }

    $suffix = $algorithm === 'rsa' ? "-{$rsaBits}" : ($algorithm === 'ecdsa' ? "-{$curve}" : '');
    $items = [
        [
            'label' => "CSR (PEM)",
            'content' => $csrPem,
            'filename' => "request-{$algorithm}{$suffix}.csr.pem",
        ],
        [
            'label' => strtoupper($algorithm) . " Private Key (PEM)",
            'content' => (string) $keyResult['private_pem'],
            'filename' => "private-{$algorithm}{$suffix}.pem",
        ],
        [
            'label' => strtoupper($algorithm) . " Public Key (PEM)",
            'content' => (string) $keyResult['public_pem'],
            'filename' => "public-{$algorithm}{$suffix}.pem",
        ],
    ];

    return crypto_render_key_output($items, "CSR + Key Material");
}

/**
 * Handle JWT requests: decode, verify, and sign (HMAC algorithms)
 *
 * @param array $req Request array containing JWT inputs
 * @return string Formatted HTML with JWT results
 */
function handle_jwt(array $req): string {
    $mode = req_get($req, 'jwt_mode', 'decode');
    $allowedModes = ['decode', 'verify', 'sign'];
    if (!in_array($mode, $allowedModes, true)) {
        return formatOutput("Invalid JWT mode selected.", type: "danger");
    }

    if ($mode === 'sign') {
        $secret = (string) req_get($req, 'jwt_secret', '');
        if ($secret === '') {
            return formatOutput("Secret is required for signing.", type: "danger");
        }

        $alg = req_get($req, 'jwt_alg', 'HS256');
        if (!in_array($alg, ['HS256', 'HS384', 'HS512'], true)) {
            return formatOutput("Only HS256/HS384/HS512 are supported for signing.", type: "danger");
        }

        $payloadJson = trim((string) req_get($req, 'jwt_payload', ''));
        if ($payloadJson === '') {
            return formatOutput("Payload JSON is required for signing.", type: "danger");
        }
        $payload = json_decode($payloadJson, true);
        if (!is_array($payload)) {
            return formatOutput("Payload must be valid JSON object.", type: "danger");
        }

        $headerJson = trim((string) req_get($req, 'jwt_header', ''));
        $header = ['typ' => 'JWT', 'alg' => $alg];
        if ($headerJson !== '') {
            $customHeader = json_decode($headerJson, true);
            if (!is_array($customHeader)) {
                return formatOutput("Header must be valid JSON object.", type: "danger");
            }
            $header = array_merge($header, $customHeader);
            $header['alg'] = $alg;
        }

        $token = jwt_build_hmac($header, $payload, $secret, $alg);
        return output_copyable($token, "Signed JWT", ['inputName' => 'jwt_token']);
    }

    $token = trim((string) req_get($req, 'jwt_token', ''));
    if ($token === '') {
        return formatOutput("JWT token is required.", type: "danger");
    }

    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return formatOutput("Invalid JWT format. Expected header.payload.signature", type: "danger");
    }

    [$headerB64, $payloadB64, $sigB64] = $parts;
    $headerJson = jwt_b64url_decode($headerB64);
    $payloadJson = jwt_b64url_decode($payloadB64);
    if ($headerJson === false || $payloadJson === false) {
        return formatOutput("Invalid base64url section in token.", type: "danger");
    }

    $header = json_decode($headerJson, true);
    $payload = json_decode($payloadJson, true);
    if (!is_array($header) || !is_array($payload)) {
        return formatOutput("JWT header/payload must be valid JSON.", type: "danger");
    }

    $output = output_copyable(json_encode($header, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "Header");
    $output .= output_copyable(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "Payload", ['inputName' => 'jwt_payload']);
    $output .= output_copyable($sigB64, "Signature (base64url)");

    if ($mode === 'verify') {
        $secret = (string) req_get($req, 'jwt_secret', '');
        if ($secret === '') {
            return $output . formatOutput("Secret is required to verify signature.", type: "warning");
        }
        $alg = (string) ($header['alg'] ?? '');
        if (!in_array($alg, ['HS256', 'HS384', 'HS512'], true)) {
            return $output . formatOutput("Verify currently supports HMAC JWTs only (HS256/HS384/HS512).", type: "warning");
        }
        $isValid = jwt_verify_hmac($token, $secret, $alg);
        $output .= formatOutput(
            $isValid ? "Signature is valid ({$alg})." : "Signature verification failed ({$alg}).",
            type: $isValid ? "success" : "danger"
        );

        if (isset($payload['exp']) && is_numeric($payload['exp'])) {
            $expired = time() >= (int) $payload['exp'];
            $output .= formatOutput(
                $expired ? "Token is expired (exp claim)." : "Token is not expired (exp claim).",
                type: $expired ? "warning" : "info"
            );
        }
    }

    return $output;
}

function jwt_build_hmac(array $header, array $payload, string $secret, string $alg): string {
    $headerEncoded = jwt_b64url_encode(json_encode($header, JSON_UNESCAPED_SLASHES));
    $payloadEncoded = jwt_b64url_encode(json_encode($payload, JSON_UNESCAPED_SLASHES));
    $signingInput = $headerEncoded . "." . $payloadEncoded;
    $signature = jwt_sign_hmac($signingInput, $secret, $alg);
    return $signingInput . "." . jwt_b64url_encode($signature);
}

function jwt_verify_hmac(string $token, string $secret, string $alg): bool {
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return false;
    }
    [$headerB64, $payloadB64, $signatureB64] = $parts;
    $expected = jwt_sign_hmac($headerB64 . "." . $payloadB64, $secret, $alg);
    $provided = jwt_b64url_decode($signatureB64);
    if ($provided === false) {
        return false;
    }
    return hash_equals($expected, $provided);
}

function jwt_sign_hmac(string $data, string $secret, string $alg): string {
    $hashAlgo = match ($alg) {
        'HS256' => 'sha256',
        'HS384' => 'sha384',
        'HS512' => 'sha512',
        default => 'sha256',
    };
    return hash_hmac($hashAlgo, $data, $secret, true);
}

function jwt_b64url_encode(string $input): string {
    return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
}

function jwt_b64url_decode(string $input): string|false {
    $padding = strlen($input) % 4;
    if ($padding > 0) {
        $input .= str_repeat('=', 4 - $padding);
    }
    return base64_decode(strtr($input, '-_', '+/'), true);
}

function gen_uuid4(): string {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function gen_ulid(): string {
    $timeMs = (int) floor(microtime(true) * 1000);
    $timeBytes = '';
    for ($i = 5; $i >= 0; $i--) {
        $timeBytes .= chr(($timeMs >> ($i * 8)) & 0xff);
    }
    $randomBytes = random_bytes(10);
    return ulid_encode_crockford($timeBytes . $randomBytes);
}

function ulid_encode_crockford(string $bytes): string {
    $alphabet = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
    $bits = '';
    for ($i = 0; $i < strlen($bytes); $i++) {
        $bits .= str_pad(decbin(ord($bytes[$i])), 8, '0', STR_PAD_LEFT);
    }
    $bits = str_pad($bits, 130, '0', STR_PAD_LEFT);
    $encoded = '';
    for ($i = 0; $i < 130; $i += 5) {
        $chunk = substr($bits, $i, 5);
        $encoded .= $alphabet[bindec($chunk)];
    }
    return substr($encoded, 0, 26);
}

function gen_nanoid(int $length = 21): string {
    $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
    $alphabetLength = strlen($alphabet);
    $mask = (2 << (int) floor(log($alphabetLength - 1, 2))) - 1;
    $step = (int) ceil(1.6 * $mask * $length / $alphabetLength);
    $id = '';

    while (strlen($id) < $length) {
        $bytes = random_bytes($step);
        for ($i = 0; $i < $step; $i++) {
            $index = ord($bytes[$i]) & $mask;
            if ($index < $alphabetLength) {
                $id .= $alphabet[$index];
                if (strlen($id) === $length) {
                    break;
                }
            }
        }
    }
    return $id;
}

// Additional handlers can be added here following the same pattern...
