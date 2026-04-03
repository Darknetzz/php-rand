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
        'logo_generate' => 'handle_logo_generate',
        'regex' => 'handle_regex',
        'crontab' => 'handle_crontab',
        'brainfuck' => 'handle_brainfuck',
        'genid' => 'handle_genid',
        'jwt' => 'handle_jwt',
        'shellcheck' => 'handle_shellcheck',
        'syntax_validate' => 'handle_syntax_validate',
        'ssh_keygen' => 'handle_ssh_keygen',
        'keypair_generate' => 'handle_keypair_generate',
        'csr_generate' => 'handle_csr_generate',
        'pem_openssh_convert' => 'handle_pem_openssh_convert',
        'crypto_diagnostics' => 'handle_crypto_diagnostics',
        'ssh_key_verify' => 'handle_ssh_key_verify',
        'keypair_sign_verify' => 'handle_keypair_sign_verify',
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
function output_copyable(string $content, string $label = "", ?array $useAsInput = null, ?string $extraActionsHtml = null): string {
    return "<div style='margin-bottom: 15px;'>" . copyableOutput($content, $label, $useAsInput, $extraActionsHtml) . "</div>";
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
    $minDigits = null;
    $maxDigits = null;
    $useLargeDigitPath = false;

    if ($rangeMode === 'digits') {
        $digitBounds = resolve_numgen_digit_bounds($req);
        if ($digitBounds === null) {
            $maxDigitsAllowed = max_configurable_numgen_digits();
            return formatOutput("Invalid digit range. Use digits 1-{$maxDigitsAllowed} and ensure min <= max.", type: "danger");
        }
        [$minDigits, $maxDigits] = $digitBounds;
        $useLargeDigitPath = $maxDigits > max_supported_numgen_digits();

        if (!$useLargeDigitPath) {
            $range = digit_range_to_numeric($minDigits, $maxDigits);
            if ($range === null) {
                $maxDigitsAllowed = max_supported_numgen_digits();
                return formatOutput("Invalid digit range. Use digits 1-{$maxDigitsAllowed} and ensure min <= max.", type: "danger");
            }
            [$from, $to] = $range;
        }
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

    if ($useLargeDigitPath && !numgen_type_supports_large_values($type)) {
        $nativeDigits = max_supported_numgen_digits();
        $largeCap = max_configurable_numgen_digits();
        return formatOutput("Type '{$type}' is only supported up to {$nativeDigits} digits. For digit ranges above that (up to {$largeCap} digits), use any, odd, even, palindromic, prime, or composite (requires GMP).", type: "danger");
    }
    if ($useLargeDigitPath && !numgen_large_gmp_available()) {
        return formatOutput("Large digit generation requires the GMP PHP extension (including gmp_prob_prime).", type: "danger");
    }

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
        if ($useLargeDigitPath) {
            $result = random_large_numgen_value_by_digits($minDigits, $maxDigits, $type);
            if (is_string($result) && str_contains($result, "alert alert-")) {
                return $result;
            }
            $results[] = $result;
            continue;
        }

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
 * Normalize subnet field: dotted IPv4 mask or /prefix (e.g. /24).
 */
function handle_ip_normalize_subnet_mask(string $subnet): ?string {
    $subnet = trim($subnet);
    if ($subnet === '') {
        return null;
    }
    if (preg_match('/^\/?(\d{1,2})$/', $subnet, $m)) {
        $p = (int) $m[1];
        if ($p < 0 || $p > 32) {
            return null;
        }
        if ($p === 0) {
            return '0.0.0.0';
        }
        if ($p === 32) {
            return '255.255.255.255';
        }
        $mask = (-1 << (32 - $p)) & 0xFFFFFFFF;

        return long2ip($mask);
    }
    if (filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return $subnet;
    }

    return null;
}

/**
 * Render a key/value result table for networking tools.
 *
 * @param array<string, string> $rows
 */
function handle_ip_kv_table(array $rows): string {
    $out = '<div class="table-responsive"><table class="table table-dark table-striped table-hover align-middle mb-0" style="border: 1px solid #334155;"><tbody>';
    foreach ($rows as $k => $v) {
        $out .= '<tr><th class="text-nowrap" scope="row">' . htmlspecialchars((string) $k, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</th><td style="font-family: monospace;">' . htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</td></tr>';
    }
    $out .= '</tbody></table></div>';

    return $out;
}

/**
 * Handle DNS lookup, CIDR/range conversion, and subnet calculator (action ip).
 *
 * @param array $req Request with 'tool': dnslookup|cidr2range|range2cidr|subnetmask
 */
function handle_ip(array $req): string {
    $allowedTools = ['dnslookup', 'cidr2range', 'range2cidr', 'subnetmask'];
    $tool = req_get($req, 'tool');
    if (!in_array($tool, $allowedTools, true)) {
        return formatOutput('Invalid networking tool selected.', type: 'danger');
    }

    if ($tool === 'dnslookup') {
        $q = trim(req_get($req, 'hostname', ''));
        if ($q === '') {
            return formatOutput('Hostname or IP is required.', type: 'danger');
        }
        if (strlen($q) > 253 || preg_match('/\s/', $q)) {
            return formatOutput('Invalid input.', type: 'danger');
        }

        if (filter_var($q, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
            $ptr = @gethostbyaddr($q);
            $ptrLabel = ($ptr !== false && $ptr !== $q) ? $ptr : '—';

            return handle_ip_kv_table(['Query' => $q, 'PTR (reverse DNS)' => $ptrLabel]);
        }

        if (filter_var($q, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) {
            return formatOutput('Invalid hostname.', type: 'danger');
        }

        $ipv4 = [];
        $ipv6 = [];
        if (function_exists('dns_get_record')) {
            foreach (@dns_get_record($q, DNS_A) ?: [] as $rec) {
                if (!empty($rec['ip'])) {
                    $ipv4[] = $rec['ip'];
                }
            }
            foreach (@dns_get_record($q, DNS_AAAA) ?: [] as $rec) {
                if (!empty($rec['ipv6'])) {
                    $ipv6[] = $rec['ipv6'];
                }
            }
        }
        $gb = @gethostbyname($q);
        if ($gb !== $q && filter_var($gb, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            if (!in_array($gb, $ipv4, true)) {
                array_unshift($ipv4, $gb);
            }
        }

        return handle_ip_kv_table([
            'Query' => $q,
            'A (IPv4)' => $ipv4 ? implode(', ', array_unique($ipv4)) : '—',
            'AAAA (IPv6)' => $ipv6 ? implode(', ', array_unique($ipv6)) : '—',
        ]);
    }

    if ($tool === 'cidr2range') {
        $cidr = trim(req_get($req, 'cidr', ''));
        if ($cidr === '') {
            return formatOutput('CIDR is required.', type: 'danger');
        }
        if (strlen($cidr) > 128) {
            return formatOutput('CIDR input is too long.', type: 'danger');
        }
        $range = cidr2range($cidr);
        if ($range === false) {
            return formatOutput('Invalid CIDR notation.', type: 'danger');
        }

        return handle_ip_kv_table([
            'CIDR' => $range['cidr'],
            'Start' => $range['start'],
            'End' => $range['end'],
            'Total addresses' => (string) $range['total'],
        ]);
    }

    if ($tool === 'range2cidr') {
        $start = trim(req_get($req, 'startip', ''));
        $end = trim(req_get($req, 'endip', ''));
        if ($start === '' || $end === '') {
            return formatOutput('Start and end IP are required.', type: 'danger');
        }
        if (ip2long($start) !== false && ip2long($end) !== false && ip2long($start) > ip2long($end)) {
            [$start, $end] = [$end, $start];
        }
        $result = range2cidr($start, $end);
        if ($result === false || empty($result['cidrs'])) {
            return formatOutput('Invalid IPv4 range.', type: 'danger');
        }

        return handle_ip_kv_table([
            'Start' => $result['start'],
            'End' => $result['end'],
            'CIDR block(s)' => implode(', ', $result['cidrs']),
            'Block count' => (string) $result['total'],
            'IPs in range' => (string) $result['total_ips'],
        ]);
    }

    // subnetmask
    $ip = trim(req_get($req, 'ip', ''));
    $subnetRaw = trim(req_get($req, 'subnet', ''));
    if ($ip === '' || $subnetRaw === '') {
        return formatOutput('IP and subnet (mask or /prefix) are required.', type: 'danger');
    }
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
        return formatOutput('Invalid IPv4 address.', type: 'danger');
    }
    $subnet = handle_ip_normalize_subnet_mask($subnetRaw);
    if ($subnet === null) {
        return formatOutput('Invalid subnet mask or prefix (use dotted mask or /0–/32).', type: 'danger');
    }
    $info = subnetmask($ip, $subnet);
    if ($info === false) {
        return formatOutput('Could not compute subnet information.', type: 'danger');
    }

    return handle_ip_kv_table([
        'Address' => $ip,
        'Subnet mask' => $info['subnet'],
        'CIDR' => $info['cidr'],
        'Network' => $info['network'],
        'Broadcast' => $info['broadcast'],
        'First usable' => $info['start'],
        'Last usable' => $info['end'],
        'Usable hosts' => (string) $info['total_ips'],
        'Total addresses' => (string) $info['total'],
    ]);
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
 * Parse #RRGGBB color to RGB tuple.
 *
 * @return array{0:int,1:int,2:int}
 */
function logo_hex_to_rgb(string $hex, string $fallback = '#1f2937'): array {
    $value = trim($hex);
    if (!preg_match('/^#[0-9a-fA-F]{6}$/', $value)) {
        $value = $fallback;
    }
    return [
        hexdec(substr($value, 1, 2)),
        hexdec(substr($value, 3, 2)),
        hexdec(substr($value, 5, 2)),
    ];
}

function logo_get_fonts(): array {
    return logo_discover_font_files();
}

function logo_pick_font(string $fontFile): ?string {
    $fonts = logo_get_fonts();
    if (empty($fonts)) {
        return null;
    }
    foreach ($fonts as $fontPath) {
        if (basename($fontPath) === $fontFile) {
            return $fontPath;
        }
    }
    return $fonts[0];
}

function logo_build_png_data_uri($image): string {
    ob_start();
    imagepng($image);
    $binary = (string) ob_get_clean();
    return 'data:image/png;base64,' . base64_encode($binary);
}

function logo_draw_background($image, int $width, int $height, string $style, array $bgRgb, array $accentRgb): void {
    if ($style === 'gradient') {
        for ($y = 0; $y < $height; $y++) {
            $t = $height > 1 ? ($y / ($height - 1)) : 0;
            $r = (int) round($bgRgb[0] + ($accentRgb[0] - $bgRgb[0]) * $t);
            $g = (int) round($bgRgb[1] + ($accentRgb[1] - $bgRgb[1]) * $t);
            $b = (int) round($bgRgb[2] + ($accentRgb[2] - $bgRgb[2]) * $t);
            $line = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $y, $width, $y, $line);
        }
        return;
    }

    $bg = imagecolorallocate($image, $bgRgb[0], $bgRgb[1], $bgRgb[2]);
    imagefilledrectangle($image, 0, 0, $width, $height, $bg);
}

/**
 * Handle logo generator requests.
 *
 * @param array $req Request array containing logo configuration options
 * @return string HTML preview with download link
 */
function handle_logo_generate(array $req): string {
    if (!extension_loaded('gd')) {
        return formatOutput("GD extension is required for logo generation.", type: "danger");
    }

    $text = trim((string) req_get($req, 'logo_text', 'Rand'));
    if ($text === '') {
        $text = 'Rand';
    }
    $text = mb_substr($text, 0, 40);

    $width = max(128, min(1600, req_int($req, 'logo_width', 512)));
    $height = max(128, min(1600, req_int($req, 'logo_height', 512)));
    $fontSize = max(12, min(400, req_int($req, 'logo_font_size', 96)));
    $shape = (string) req_get($req, 'logo_shape', 'rounded');
    $style = (string) req_get($req, 'logo_style', 'gradient');
    $uppercase = req_bool($req, 'logo_uppercase');
    $useInitials = req_bool($req, 'logo_initials');
    $border = max(0, min(24, req_int($req, 'logo_border', 0)));
    $fontFile = (string) req_get($req, 'logo_font', '');

    $displayText = $uppercase ? mb_strtoupper($text) : $text;
    if ($useInitials) {
        $parts = preg_split('/\s+/', trim($displayText)) ?: [];
        $initials = '';
        foreach ($parts as $part) {
            if ($part !== '') {
                $initials .= mb_substr($part, 0, 1);
            }
        }
        $displayText = $initials !== '' ? mb_substr($initials, 0, 4) : mb_substr($displayText, 0, 3);
    }

    $bgRgb = logo_hex_to_rgb((string) req_get($req, 'logo_bg_color', '#111827'));
    $accentRgb = logo_hex_to_rgb((string) req_get($req, 'logo_accent_color', '#1d4ed8'));
    $textRgb = logo_hex_to_rgb((string) req_get($req, 'logo_text_color', '#ffffff'), '#ffffff');
    $borderRgb = logo_hex_to_rgb((string) req_get($req, 'logo_border_color', '#ffffff'), '#ffffff');

    $image = imagecreatetruecolor($width, $height);
    imagealphablending($image, true);
    imagesavealpha($image, true);

    logo_draw_background($image, $width, $height, $style, $bgRgb, $accentRgb);

    if ($shape === 'circle') {
        $mask = imagecreatetruecolor($width, $height);
        $transparent = imagecolorallocatealpha($mask, 0, 0, 0, 127);
        imagefill($mask, 0, 0, $transparent);
        imagealphablending($mask, true);
        $white = imagecolorallocate($mask, 255, 255, 255);
        imagefilledellipse($mask, (int) ($width / 2), (int) ($height / 2), $width, $height, $white);
        imagecolortransparent($mask, $transparent);
        imagecopymerge($image, $mask, 0, 0, 0, 0, $width, $height, 100);
    } elseif ($shape === 'rounded') {
        $radius = (int) max(12, min((int) floor(min($width, $height) * 0.15), 80));
        $overlay = imagecreatetruecolor($width, $height);
        imagesavealpha($overlay, true);
        $trans = imagecolorallocatealpha($overlay, 0, 0, 0, 127);
        imagefill($overlay, 0, 0, $trans);
        $fill = imagecolorallocate($overlay, 255, 255, 255);
        imagefilledrectangle($overlay, $radius, 0, $width - $radius, $height, $fill);
        imagefilledrectangle($overlay, 0, $radius, $width, $height - $radius, $fill);
        imagefilledellipse($overlay, $radius, $radius, $radius * 2, $radius * 2, $fill);
        imagefilledellipse($overlay, $width - $radius, $radius, $radius * 2, $radius * 2, $fill);
        imagefilledellipse($overlay, $radius, $height - $radius, $radius * 2, $radius * 2, $fill);
        imagefilledellipse($overlay, $width - $radius, $height - $radius, $radius * 2, $radius * 2, $fill);
        imagecolortransparent($overlay, $trans);
        imagecopymerge($image, $overlay, 0, 0, 0, 0, $width, $height, 100);
    }

    if ($border > 0) {
        $borderColor = imagecolorallocate($image, $borderRgb[0], $borderRgb[1], $borderRgb[2]);
        for ($i = 0; $i < $border; $i++) {
            imagerectangle($image, $i, $i, $width - 1 - $i, $height - 1 - $i, $borderColor);
        }
    }

    $fontColor = imagecolorallocate($image, $textRgb[0], $textRgb[1], $textRgb[2]);
    $fontPath = logo_pick_font($fontFile);
    if ($fontPath !== null && function_exists('imagettfbbox') && function_exists('imagettftext')) {
        $bbox = imagettfbbox($fontSize, 0, $fontPath, $displayText);
        $textWidth = (int) abs(($bbox[2] ?? 0) - ($bbox[0] ?? 0));
        $textHeight = (int) abs(($bbox[7] ?? 0) - ($bbox[1] ?? 0));
        $x = (int) floor(($width - $textWidth) / 2);
        $y = (int) floor(($height + $textHeight) / 2);
        imagettftext($image, $fontSize, 0, $x, $y, $fontColor, $fontPath, $displayText);
    } else {
        $font = 5;
        $textWidth = imagefontwidth($font) * strlen($displayText);
        $textHeight = imagefontheight($font);
        $x = (int) floor(($width - $textWidth) / 2);
        $y = (int) floor(($height - $textHeight) / 2);
        imagestring($image, $font, $x, $y, $displayText, $fontColor);
    }

    $dataUri = logo_build_png_data_uri($image);

    $safeDataUri = htmlspecialchars($dataUri, ENT_QUOTES, 'UTF-8');
    $safeAlt = htmlspecialchars($displayText, ENT_QUOTES, 'UTF-8');
    $filenameBase = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($displayText)) ?: 'logo';
    $filename = $filenameBase . '.png';

    $output = "<div style='text-align:center; padding:12px;'>";
    $output .= "<img src='{$safeDataUri}' alt='{$safeAlt}' style='max-width:100%; height:auto; border-radius:8px; border:1px solid #334155;' />";
    $output .= "<div style='margin-top:12px;'>";
    $output .= "<a class='btn btn-primary btn-sm' href='{$safeDataUri}' download='" . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . "'>" . icon('download') . " Download PNG</a>";
    $output .= "</div>";
    $output .= "</div>";
    return $output;
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

function crontab_human_summary_block(string $summary): string {
    return '<div class="crontab-human-summary border border-primary border-opacity-25 rounded-3 px-4 py-4 mb-4 text-center bg-primary bg-opacity-10">'
        . '<p class="mb-0 fs-3 fw-semibold lh-sm">' . htmlspecialchars($summary, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>'
        . '</div>';
}

function handle_crontab(array $req): string {
    $timezone = trim((string) req_get($req, 'cron_timezone', date_default_timezone_get() ?: 'UTC'));
    $runCount = req_int($req, 'cron_run_count', 8);
    $referenceRaw = trim((string) req_get($req, 'cron_reference_time', ''));
    $allowCurrent = req_bool($req, 'cron_include_current');

    $evaluation = cron_evaluate_schedule(
        (string) req_get($req, 'cron_expression', ''),
        $timezone,
        $referenceRaw,
        $allowCurrent,
        $runCount
    );
    if (($evaluation['ok'] ?? false) !== true) {
        return formatOutput(
            htmlspecialchars((string) ($evaluation['error'] ?? 'Unable to evaluate cron expression.'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            type: "danger"
        );
    }

    if (!empty($evaluation['reboot'])) {
        $summary = (string) $evaluation['summary'];
        $referenceTime = $evaluation['reference_time'];
        $timezone = (string) $evaluation['timezone'];

        $output = '';
        $output .= "<div class='card border-info mb-3'><h5 class='card-header'>Schedule Summary</h5><div class='card-body'>";
        $output .= crontab_human_summary_block($summary);
        $output .= "<div class='d-flex flex-wrap gap-2 mb-3'>";
        $output .= "<span class='badge bg-info text-white'>" . icon('arrow-repeat') . " One-shot at cron startup</span>";
        $output .= "<span class='badge bg-primary text-white'>" . icon('globe2') . ' ' . htmlspecialchars($timezone, ENT_QUOTES, 'UTF-8') . "</span>";
        $output .= "</div>";
        $output .= "<div class='alert alert-info mb-3'><strong>@reboot</strong> is a Vixie-style crontab extension: the job runs once when the cron daemon starts (often after a system reboot), not on a repeating calendar schedule.</div>";
        $output .= "<div class='mb-3'><strong>Reference time:</strong> <code>" . htmlspecialchars($referenceTime->format('Y-m-d H:i:s T'), ENT_QUOTES, 'UTF-8') . "</code></div>";
        $output .= copyableOutput('@reboot', 'Expression');
        $output .= "</div></div>";

        $output .= "<div class='card border-secondary mb-3'><h5 class='card-header'>Field Breakdown</h5><div class='card-body p-0'>";
        $output .= "<div class='table-responsive'><table class='table table-dark table-striped mb-0'><tbody>";
        $output .= "<tr><td colspan='3' class='text-muted'>"
            . htmlspecialchars('@reboot is not a five-field cron pattern; there are no minute/hour/day/month/day-of-week fields to expand.', ENT_QUOTES, 'UTF-8')
            . "</td></tr>";
        $output .= "</tbody></table></div></div>";

        $output .= "<div class='card border-success mb-3'><h5 class='card-header'>Upcoming Run Times</h5><div class='card-body p-0'>";
        $output .= "<div class='table-responsive'><table class='table table-dark table-striped mb-0'><tbody>";
        $output .= "<tr><td colspan='3' class='text-muted'>No repeating schedule — periodic next run times are not applicable.</td></tr>";
        $output .= "</tbody></table></div></div>";

        return $output;
    }

    $expression = (string) $evaluation['expression'];
    $parts = $evaluation['parts'];
    $normalizedExpression = (string) $evaluation['normalized_expression'];
    $summary = (string) $evaluation['summary'];
    $isDue = (bool) $evaluation['is_due'];
    $referenceTime = $evaluation['reference_time'];
    $previousRun = $evaluation['previous_run'];
    $nextRuns = $evaluation['next_runs'];
    $runCount = intval($evaluation['run_count']);
    $timezone = (string) $evaluation['timezone'];

    $fieldMap = [
        ['label' => 'Minute', 'expression' => $parts[0] ?? '*', 'field' => 'minute'],
        ['label' => 'Hour', 'expression' => $parts[1] ?? '*', 'field' => 'hour'],
        ['label' => 'Day of month', 'expression' => $parts[2] ?? '*', 'field' => 'dom'],
        ['label' => 'Month', 'expression' => $parts[3] ?? '*', 'field' => 'month'],
        ['label' => 'Day of week', 'expression' => $parts[4] ?? '*', 'field' => 'dow'],
    ];

    $fieldRows = '';
    foreach ($fieldMap as $field) {
        $fieldRows .= '<tr>'
            . '<td><code>' . htmlspecialchars($field['label'], ENT_QUOTES, 'UTF-8') . '</code></td>'
            . '<td><code>' . htmlspecialchars($field['expression'], ENT_QUOTES, 'UTF-8') . '</code></td>'
            . '<td>' . htmlspecialchars(cron_describe_field((string) $field['expression'], (string) $field['field']), ENT_QUOTES, 'UTF-8') . '</td>'
            . '</tr>';
    }

    $nextRunRows = '';
    foreach ($nextRuns as $index => $date) {
        $nextRunRows .= '<tr>'
            . '<td>' . ($index + 1) . '</td>'
            . '<td><code>' . htmlspecialchars($date->format('Y-m-d H:i:s T'), ENT_QUOTES, 'UTF-8') . '</code></td>'
            . '<td>' . htmlspecialchars($date->format('D, j M Y'), ENT_QUOTES, 'UTF-8') . '</td>'
            . '</tr>';
    }

    $dueBadge = $isDue
        ? "<span class='badge bg-success text-white'>" . icon('check-circle') . " Due at reference time</span>"
        : "<span class='badge bg-secondary text-white'>" . icon('clock') . " Not due at reference time</span>";

    $macroNotice = '';
    if (str_starts_with($expression, '@') && $normalizedExpression !== $expression) {
        $macroNotice = "<div class='alert alert-info mb-3'><strong>Macro expanded:</strong> <code>"
            . htmlspecialchars($expression, ENT_QUOTES, 'UTF-8')
            . "</code> becomes <code>"
            . htmlspecialchars($normalizedExpression, ENT_QUOTES, 'UTF-8')
            . "</code>.</div>";
    }

    $orSemanticsNotice = '';
    if (!cron_is_wildcard($parts[2] ?? '*') && !cron_is_wildcard($parts[4] ?? '*')) {
        $orSemanticsNotice = "<div class='alert alert-warning mb-0'><strong>Day-of-month vs day-of-week:</strong> standard cron treats these as an <strong>OR</strong> match, so the schedule runs when either field matches.</div>";
    }

    $output = '';
    $output .= "<div class='card border-info mb-3'><h5 class='card-header'>Schedule Summary</h5><div class='card-body'>";
    $output .= crontab_human_summary_block($summary);
    $output .= "<div class='d-flex flex-wrap gap-2 mb-3'>{$dueBadge}";
    $output .= "<span class='badge bg-primary text-white'>" . icon('globe2') . ' ' . htmlspecialchars($timezone, ENT_QUOTES, 'UTF-8') . "</span>";
    $output .= "<span class='badge bg-dark text-white'>" . icon('list-ol') . ' ' . intval($runCount) . " future runs</span>";
    $output .= "</div>";
    $output .= $macroNotice;
    $output .= "<div class='mb-3'><strong>Reference time:</strong> <code>" . htmlspecialchars($referenceTime->format('Y-m-d H:i:s T'), ENT_QUOTES, 'UTF-8') . "</code></div>";
    $output .= "<div class='mb-3'><strong>Previous matching run:</strong> <code>" . htmlspecialchars($previousRun->format('Y-m-d H:i:s T'), ENT_QUOTES, 'UTF-8') . "</code></div>";
    $output .= copyableOutput($normalizedExpression, 'Normalized expression');
    $output .= "</div></div>";

    $output .= "<div class='card border-secondary mb-3'><h5 class='card-header'>Field Breakdown</h5><div class='card-body p-0'>";
    $output .= "<div class='table-responsive'><table class='table table-dark table-striped mb-0'><thead><tr><th>Field</th><th>Value</th><th>Meaning</th></tr></thead><tbody>{$fieldRows}</tbody></table></div>";
    $output .= "</div></div>";

    $output .= "<div class='card border-success mb-3'><h5 class='card-header'>Upcoming Run Times</h5><div class='card-body p-0'>";
    $output .= "<div class='table-responsive'><table class='table table-dark table-striped mb-0'><thead><tr><th>#</th><th>Date/Time</th><th>Day</th></tr></thead><tbody>{$nextRunRows}</tbody></table></div>";
    $output .= "</div></div>";

    if ($orSemanticsNotice !== '') {
        $output .= $orSemanticsNotice;
    }

    return $output;
}

function shellcheck_level_badge_html(string $level): string {
    $level = strtolower(trim($level));
    return match ($level) {
        'error' => "<span class='badge bg-danger text-white'>error</span>",
        'warning' => "<span class='badge bg-warning text-dark'>warning</span>",
        'style' => "<span class='badge bg-info text-dark'>style</span>",
        default => "<span class='badge bg-secondary text-white'>" . htmlspecialchars($level !== '' ? $level : 'info', ENT_QUOTES, 'UTF-8') . "</span>",
    };
}

function shellcheck_excerpt_html(array $lines, array $comment): string {
    $lineNumber = max(1, intval($comment['line'] ?? 1));
    $column = max(1, intval($comment['column'] ?? 1));
    $endColumn = max($column, intval($comment['endColumn'] ?? $column + 1));

    $line = $lines[$lineNumber - 1] ?? '';
    $line = str_replace("\t", '    ', $line);
    $pointer = str_repeat(' ', max(0, $column - 1)) . str_repeat('^', max(1, $endColumn - $column));

    return "<pre style='margin: 0; background: #0f172a; color: #e9ecef; padding: 12px; border-radius: 0.5rem; overflow-x: auto;'><code>"
        . htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
        . "\n"
        . htmlspecialchars($pointer, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
        . "</code></pre>";
}

function handle_shellcheck(array $req): string {
    $script = (string) req_get($req, 'shellcheck_script', '');
    // CRLF / Mac CR: shellcheck SC1017 otherwise; normalize like tr -d '\r'
    $script = str_replace("\r", '', $script);
    if (trim($script) === '') {
        return formatOutput("Shell script input is required.", type: "danger");
    }
    if (strlen($script) > 200000) {
        return formatOutput("Shell script input is too large. Maximum 200,000 characters allowed.", type: "danger");
    }

    $severity = strtolower(trim((string) req_get($req, 'shellcheck_severity', 'info')));
    $allowedSeverities = ['style', 'info', 'warning', 'error'];
    if (!in_array($severity, $allowedSeverities, true)) {
        return formatOutput("Invalid severity selected.", type: "danger");
    }

    $shell = strtolower(trim((string) req_get($req, 'shellcheck_shell', 'auto')));
    $allowedShells = ['auto', 'bash', 'sh', 'dash', 'ksh'];
    if (!in_array($shell, $allowedShells, true)) {
        return formatOutput("Invalid shell dialect selected.", type: "danger");
    }

    $filenameInput = trim((string) req_get($req, 'shellcheck_filename', 'snippet.sh'));
    if (strlen($filenameInput) > 180) {
        return formatOutput("Filename hint is too long. Maximum 180 characters allowed.", type: "danger");
    }
    $displayFilename = basename(str_replace('\\', '/', $filenameInput));
    $displayFilename = preg_replace('/[^A-Za-z0-9._-]+/', '-', $displayFilename ?? '') ?: 'snippet.sh';

    $shellcheckPath = cli_find_binary('shellcheck');
    if ($shellcheckPath === '') {
        return formatOutput("shellcheck is not available on this host.", type: "danger");
    }

    $tempBase = tempnam(sys_get_temp_dir(), 'shellcheck_');
    if ($tempBase === false) {
        return formatOutput("Unable to create a temporary file for linting.", type: "danger");
    }

    $extension = pathinfo($displayFilename, PATHINFO_EXTENSION);
    $suffix = $extension !== '' ? '.' . $extension : ($shell === 'bash' ? '.bash' : '.sh');
    $tempPath = $tempBase . $suffix;
    if (!@rename($tempBase, $tempPath)) {
        $tempPath = $tempBase;
    }

    if (@file_put_contents($tempPath, $script) === false) {
        @unlink($tempPath);
        return formatOutput("Unable to write temporary shell script for linting.", type: "danger");
    }

    $command = [
        $shellcheckPath,
        '--format=json1',
        '--severity=' . $severity,
    ];
    if ($shell !== 'auto') {
        $command[] = '--shell=' . $shell;
    }
    $command[] = $tempPath;

    $result = cli_run_command($command);

    if (($result['ok'] ?? false) !== true) {
        @unlink($tempPath);
        return formatOutput((string) ($result['error'] ?? 'Unable to run shellcheck.'), type: "danger");
    }

    $exitCode = intval($result['exit_code'] ?? 1);
    $stdout = trim((string) ($result['stdout'] ?? ''));
    $stderr = trim((string) ($result['stderr'] ?? ''));

    if (!in_array($exitCode, [0, 1], true)) {
        @unlink($tempPath);
        $message = $stderr !== '' ? $stderr : ($stdout !== '' ? $stdout : 'shellcheck returned an unexpected error.');
        return formatOutput(htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), type: "danger");
    }

    $decoded = $stdout !== '' ? json_decode($stdout, true) : ['comments' => []];
    if (!is_array($decoded)) {
        @unlink($tempPath);
        return formatOutput("shellcheck produced unreadable output.", type: "danger");
    }

    $shellArgs = ['--severity=' . $severity];
    if ($shell !== 'auto') {
        $shellArgs[] = '--shell=' . $shell;
    }
    $fixedScript = shellcheck_apply_autofix($tempPath, $shellcheckPath, $shellArgs);
    @unlink($tempPath);

    $comments = $decoded['comments'] ?? [];
    if (!is_array($comments)) {
        $comments = [];
    }

    $counts = ['error' => 0, 'warning' => 0, 'info' => 0, 'style' => 0];
    foreach ($comments as $comment) {
        $level = strtolower((string) ($comment['level'] ?? 'info'));
        if (!isset($counts[$level])) {
            $counts[$level] = 0;
        }
        $counts[$level]++;
    }

    $lines = preg_split("/\r\n|\r|\n/", $script);
    if (!is_array($lines)) {
        $lines = [];
    }

    $output = '';
    $output .= "<div class='card border-info mb-3'><h5 class='card-header'>ShellCheck Summary</h5><div class='card-body'>";
    $output .= "<div class='d-flex flex-wrap gap-2 mb-3'>";
    $output .= "<span class='badge bg-primary text-white'>" . icon('terminal') . ' ' . htmlspecialchars($displayFilename, ENT_QUOTES, 'UTF-8') . "</span>";
    $output .= "<span class='badge bg-dark text-white'>" . icon('filter') . ' min severity: ' . htmlspecialchars($severity, ENT_QUOTES, 'UTF-8') . "</span>";
    $output .= "<span class='badge bg-secondary text-white'>" . icon('cpu') . ' shell: ' . htmlspecialchars($shell === 'auto' ? 'auto' : $shell, ENT_QUOTES, 'UTF-8') . "</span>";
    $output .= "<span class='badge bg-success text-white'>" . icon('check-circle') . ' binary: ' . htmlspecialchars($shellcheckPath, ENT_QUOTES, 'UTF-8') . "</span>";
    $output .= "</div>";
    $output .= "<div class='d-flex flex-wrap gap-2'>";
    foreach (['error', 'warning', 'info', 'style'] as $level) {
        $badgeClass = match ($level) {
            'error' => 'bg-danger text-white',
            'warning' => 'bg-warning text-dark',
            'info' => 'bg-secondary text-white',
            default => 'bg-info text-dark',
        };
        $output .= "<span class='badge {$badgeClass}'>" . strtoupper($level) . ': ' . intval($counts[$level] ?? 0) . "</span>";
    }
    $output .= "</div></div></div>";

    if ($fixedScript !== null && $fixedScript !== '' && $fixedScript !== $script) {
        $output .= "<div class='card border-success mb-3'><h5 class='card-header'>" . icon('magic') . " Auto-fixed script</h5><div class='card-body'>";
        $output .= "<p class='small text-muted mb-2'>ShellCheck suggested edits (for the same severity and shell options) were applied with GNU <code>patch</code>. Review the result before running it.</p>";
        $output .= copyableOutput($fixedScript, '', ['inputName' => 'shellcheck_script']);
        $output .= "</div></div>";
    }

    if ($comments === []) {
        $output .= "<div class='alert alert-success mb-0'><strong>No issues found.</strong> This script passed ShellCheck for the selected severity threshold.</div>";
        return $output;
    }

    foreach ($comments as $comment) {
        $code = intval($comment['code'] ?? 0);
        $level = strtolower((string) ($comment['level'] ?? 'info'));
        $lineNumber = max(1, intval($comment['line'] ?? 1));
        $endLine = max($lineNumber, intval($comment['endLine'] ?? $lineNumber));
        $column = max(1, intval($comment['column'] ?? 1));
        $endColumn = max($column, intval($comment['endColumn'] ?? ($column + 1)));
        $message = (string) ($comment['message'] ?? 'Unknown ShellCheck diagnostic.');
        $hasFix = !empty($comment['fix']);
        $wikiUrl = 'https://www.shellcheck.net/wiki/SC' . str_pad((string) $code, 4, '0', STR_PAD_LEFT);

        $output .= "<div class='card border-warning mb-3'><div class='card-header d-flex flex-wrap justify-content-between align-items-center gap-2'>";
        $output .= "<div><strong><code>SC" . str_pad((string) $code, 4, '0', STR_PAD_LEFT) . "</code></strong></div>";
        $output .= "<div class='d-flex gap-2 align-items-center'>" . shellcheck_level_badge_html($level);
        if ($hasFix) {
            $output .= "<span class='badge bg-success text-white'>autofix available</span>";
        }
        $output .= "</div></div><div class='card-body'>";
        $output .= "<div class='mb-2'>" . htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</div>";
        $output .= "<div class='small text-muted mb-3'>Line " . $lineNumber . ':' . $column;
        if ($endLine !== $lineNumber || $endColumn !== $column) {
            $output .= ' to ' . $endLine . ':' . $endColumn;
        }
        $output .= "</div>";
        $output .= shellcheck_excerpt_html($lines, $comment);
        $output .= "<div class='mt-3'><a href='" . htmlspecialchars($wikiUrl, ENT_QUOTES, 'UTF-8') . "' target='_blank' rel='noopener noreferrer' class='btn btn-sm btn-outline-light'>"
            . icon('box-arrow-up-right') . " Open SC" . str_pad((string) $code, 4, '0', STR_PAD_LEFT) . " docs</a></div>";
        $output .= "</div></div>";
    }

    return $output;
}

function handle_syntax_validate(array $req): string {
    require_once __DIR__ . '/syntax_validate.php';

    $kind = strtolower(trim((string) req_get($req, 'syntax_validate_kind', 'json')));
    $allowed = ['json', 'yaml', 'php', 'python'];
    if (!in_array($kind, $allowed, true)) {
        return formatOutput('Invalid language selected.', type: 'danger');
    }

    $input = (string) req_get($req, 'syntax_validate_input', '');
    $input = str_replace("\r", '', $input);
    if (strlen($input) > syntax_validate_max_len()) {
        return formatOutput('Input is too large (max ' . (string) syntax_validate_max_len() . ' characters).', type: 'danger');
    }

    $result = syntax_validate_dispatch($kind, $input);
    $msg = htmlspecialchars($result['message'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    if (!empty($result['detail'])) {
        $msg .= '<br><small class="text-muted">' . htmlspecialchars((string) $result['detail'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</small>';
    }

    return formatOutput($msg, type: $result['ok'] ? 'success' : 'danger');
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
    return "<a class='btn btn-outline-light btn-sm' href='{$safeHref}' download='{$safeFilename}' style='white-space: nowrap; border: 1px solid #e9ecef;'>" . icon('download') . " {$safeLabel}</a>";
}

function crypto_render_key_output(array $items, string $title): string {
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $output = "<div class='card border-info mb-3'><h5 class='card-header'>{$safeTitle}</h5><div class='card-body'>";

    foreach ($items as $item) {
        $rawLabel = (string) ($item['label'] ?? '');
        $label = htmlspecialchars($rawLabel, ENT_QUOTES, 'UTF-8');
        $content = (string) ($item['content'] ?? '');
        $filename = (string) ($item['filename'] ?? '');
        $extraDl = $filename !== '' ? crypto_data_download_link($filename, $content, 'Download ' . $rawLabel) : null;
        $output .= output_copyable($content, $label, null, $extraDl);
    }

    $output .= "</div></div>";
    return $output;
}

/**
 * SSH keygen: public PEM vs OpenSSH selectable; private PEM always shown below.
 *
 * @param list<array{label: string, content: string, filename?: string, ssh_output_slot: string}> $items
 */
function crypto_render_ssh_key_output(array $items, string $title): string {
    $publicSlots = ['pem-public', 'openssh-public'];
    $publicSlotLabels = [
        'pem-public' => 'PEM',
        'openssh-public' => 'OpenSSH (one-line)',
    ];
    $bySlot = [];
    foreach ($items as $item) {
        $slot = (string) ($item['ssh_output_slot'] ?? '');
        if ($slot === '') {
            continue;
        }
        $bySlot[$slot] = $item;
    }

    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $output = "<div class='card border-info mb-3 crypto-ssh-key-output-card' data-crypto-ssh-output><h5 class='card-header'>{$safeTitle}</h5><div class='card-body'>";

    $presentPublic = [];
    foreach ($publicSlots as $slot) {
        if (isset($bySlot[$slot])) {
            $presentPublic[] = $slot;
        }
    }
    $publicCount = count($presentPublic);

    if ($publicCount > 1) {
        $output .= '<div class="mb-3 crypto-ssh-public-format-row d-flex flex-wrap align-items-center gap-2">';
        $output .= '<label class="form-label mb-0 me-1"><strong>Public key output</strong></label>';
        $output .= '<select class="form-select form-select-lg crypto-ssh-output-format" style="max-width: 22rem;">';
        foreach ($presentPublic as $slot) {
            $sEsc = htmlspecialchars($slot, ENT_QUOTES, 'UTF-8');
            $lbl = htmlspecialchars((string) ($publicSlotLabels[$slot] ?? $slot), ENT_QUOTES, 'UTF-8');
            $output .= "<option value=\"{$sEsc}\">{$lbl}</option>";
        }
        $output .= '</select></div>';
    }

    $output .= '<div class="crypto-ssh-output-panels">';
    $first = true;
    foreach ($presentPublic as $slot) {
        $item = $bySlot[$slot];
        $rawLabel = (string) ($item['label'] ?? '');
        $label = htmlspecialchars($rawLabel, ENT_QUOTES, 'UTF-8');
        $content = (string) ($item['content'] ?? '');
        $filename = (string) ($item['filename'] ?? '');
        $sEsc = htmlspecialchars($slot, ENT_QUOTES, 'UTF-8');
        $panelClass = 'crypto-ssh-output-panel';
        if ($publicCount > 1 && !$first) {
            $panelClass .= ' d-none';
        }
        $first = false;

        $extraDl = $filename !== '' ? crypto_data_download_link($filename, $content, 'Download ' . $rawLabel) : null;
        $output .= "<div class=\"{$panelClass}\" data-format=\"{$sEsc}\">";
        $output .= output_copyable($content, $label, null, $extraDl);
        $output .= '</div>';
    }
    $output .= '</div>';

    if (isset($bySlot['private-pem'])) {
        $priv = $bySlot['private-pem'];
        $rawPl = (string) ($priv['label'] ?? '');
        $pl = htmlspecialchars($rawPl, ENT_QUOTES, 'UTF-8');
        $pc = (string) ($priv['content'] ?? '');
        $pf = (string) ($priv['filename'] ?? '');
        $extraPriv = $pf !== '' ? crypto_data_download_link($pf, $pc, 'Download ' . $rawPl) : null;
        $output .= "<div class='crypto-ssh-private-block mt-3 pt-3 border-top border-secondary'>";
        $output .= output_copyable($pc, $pl, null, $extraPriv);
        $output .= '</div>';
    }

    $output .= '</div></div>';

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

/** @param resource $key */
function crypto_signature_digest_for_key($key): int {
    $d = openssl_pkey_get_details($key);
    if (!is_array($d)) {
        return OPENSSL_ALGO_SHA256;
    }
    $type = $d['type'] ?? null;
    if ($type === OPENSSL_KEYTYPE_RSA) {
        return OPENSSL_ALGO_SHA256;
    }
    if ($type === OPENSSL_KEYTYPE_EC) {
        $curve = (string) ($d['ec']['curve_name'] ?? '');
        return match ($curve) {
            'secp384r1' => OPENSSL_ALGO_SHA384,
            'secp521r1' => OPENSSL_ALGO_SHA512,
            default => OPENSSL_ALGO_SHA256,
        };
    }
    if (defined('OPENSSL_KEYTYPE_ED25519') && $type === OPENSSL_KEYTYPE_ED25519) {
        return OPENSSL_ALGO_SHA512;
    }
    if (isset($d['ed25519'])) {
        return OPENSSL_ALGO_SHA512;
    }

    return OPENSSL_ALGO_SHA256;
}

/**
 * Algorithm argument for openssl_sign/openssl_verify. PHP 8.4+ expects 0 for pure Ed25519/Ed448 (not a digest OID).
 *
 * @param OpenSSLAsymmetricKey|resource $key
 */
function crypto_openssl_sign_verify_algorithm_for_key($key): int {
    if (PHP_VERSION_ID >= 80400) {
        $d = openssl_pkey_get_details($key);
        if (is_array($d)) {
            $type = $d['type'] ?? null;
            if (defined('OPENSSL_KEYTYPE_ED25519') && $type === OPENSSL_KEYTYPE_ED25519) {
                return 0;
            }
            if (defined('OPENSSL_KEYTYPE_ED448') && $type === OPENSSL_KEYTYPE_ED448) {
                return 0;
            }
            if (isset($d['ed25519']) || isset($d['ed448'])) {
                return 0;
            }
        }
    }

    return crypto_signature_digest_for_key($key);
}

function crypto_digest_label(int $algo): string {
    return match ($algo) {
        0 => 'Ed25519/Ed448 (native)',
        OPENSSL_ALGO_SHA384 => 'SHA-384',
        OPENSSL_ALGO_SHA512 => 'SHA-512',
        OPENSSL_ALGO_SHA256 => 'SHA-256',
        default => 'SHA-256',
    };
}

/** @param OpenSSLAsymmetricKey|resource $key */
function crypto_openssl_key_type_is_rsa($key): bool {
    $d = openssl_pkey_get_details($key);

    return is_array($d) && ($d['type'] ?? null) === OPENSSL_KEYTYPE_RSA;
}

/**
 * RSA / RSA-PSS keys: OpenSSL 3 RSA-PSS may surface as type -1 in openssl_pkey_get_details() until PHP maps EVP_PKEY_RSA_PSS.
 *
 * @param OpenSSLAsymmetricKey|resource $key
 */
function crypto_openssl_key_may_be_rsa_for_pss_retry($key): bool {
    $d = openssl_pkey_get_details($key);
    if (!is_array($d)) {
        return false;
    }
    $t = $d['type'] ?? null;
    if ($t === OPENSSL_KEYTYPE_RSA) {
        return true;
    }
    if (defined('OPENSSL_KEYTYPE_RSA_PSS') && $t === OPENSSL_KEYTYPE_RSA_PSS) {
        return true;
    }
    if ($t === OPENSSL_KEYTYPE_EC || $t === OPENSSL_KEYTYPE_DSA || $t === OPENSSL_KEYTYPE_DH) {
        return false;
    }
    if (defined('OPENSSL_KEYTYPE_ED25519') && ($t === OPENSSL_KEYTYPE_ED25519 || $t === OPENSSL_KEYTYPE_X25519)) {
        return false;
    }
    if (defined('OPENSSL_KEYTYPE_ED448') && ($t === OPENSSL_KEYTYPE_ED448 || $t === OPENSSL_KEYTYPE_X448)) {
        return false;
    }

    return $t === -1;
}

/**
 * PKCS#1 v1.5 RSASSA first; for RSA keys, retry with RSASSA-PSS when v1.5 is refused (PHP 8.5+).
 *
 * @param OpenSSLAsymmetricKey|resource $privateKey
 * @return array{ok: true, rsa_padding: 'pkcs1'|'pss'|null}|array{ok: false}
 */
function crypto_openssl_sign_rsa_padding_fallback(string $message, string &$signature, $privateKey, int $digestAlgorithm): array {
    $signature = '';
    if (@openssl_sign($message, $signature, $privateKey, $digestAlgorithm)) {
        return ['ok' => true, 'rsa_padding' => crypto_openssl_key_type_is_rsa($privateKey) ? 'pkcs1' : null];
    }
    if (!crypto_openssl_key_may_be_rsa_for_pss_retry($privateKey)) {
        return ['ok' => false];
    }
    if (PHP_VERSION_ID < 80500 || !defined('OPENSSL_PKCS1_PSS_PADDING')) {
        return ['ok' => false];
    }
    if (@openssl_sign($message, $signature, $privateKey, $digestAlgorithm, OPENSSL_PKCS1_PSS_PADDING)) {
        return ['ok' => true, 'rsa_padding' => 'pss'];
    }

    return ['ok' => false];
}

/**
 * Verify PKCS#1 v1.5 signature; if invalid (0) and RSA, try RSASSA-PSS (PHP 8.5+).
 *
 * @param OpenSSLAsymmetricKey|resource $publicKey
 */
function crypto_openssl_verify_rsa_padding_fallback(string $message, string $signature, $publicKey, int $digestAlgorithm): int {
    $r = openssl_verify($message, $signature, $publicKey, $digestAlgorithm);
    if ($r === 1 || $r === -1) {
        return $r;
    }
    if (!crypto_openssl_key_may_be_rsa_for_pss_retry($publicKey)) {
        return 0;
    }
    if (PHP_VERSION_ID < 80500 || !defined('OPENSSL_PKCS1_PSS_PADDING')) {
        return 0;
    }

    return openssl_verify($message, $signature, $publicKey, $digestAlgorithm, OPENSSL_PKCS1_PSS_PADDING);
}

function crypto_pem_public_from_private_pem(string $privatePem, string $passphrase = ''): ?string {
    $res = openssl_pkey_get_private($privatePem, $passphrase !== '' ? $passphrase : '');
    if ($res === false) {
        return null;
    }
    $d = openssl_pkey_get_details($res);
    if (!is_array($d) || empty($d['key'])) {
        return null;
    }

    return (string) $d['key'];
}

function crypto_pem_strings_equal(string $a, string $b): bool {
    $norm = static function (string $s): string {
        return trim(preg_replace('/\s+/', "\n", $s));
    };

    return $norm($a) === $norm($b);
}

/**
 * @return array{ok: bool, summary?: string, bits?: int, error?: string}
 */
function crypto_verify_pem_public(string $pem): array {
    $res = openssl_pkey_get_public($pem);
    if ($res === false) {
        return ['ok' => false, 'error' => 'Not a valid PEM public key (or unsupported format).'];
    }
    $d = openssl_pkey_get_details($res);
    if (!is_array($d)) {
        return ['ok' => false, 'error' => 'Unable to read public key details.'];
    }
    $type = $d['type'] ?? null;
    if ($type === OPENSSL_KEYTYPE_RSA) {
        $bits = (int) ($d['bits'] ?? 0);

        return ['ok' => true, 'summary' => 'RSA', 'bits' => $bits];
    }
    if ($type === OPENSSL_KEYTYPE_EC) {
        $curve = (string) ($d['ec']['curve_name'] ?? 'unknown');

        return ['ok' => true, 'summary' => 'ECDSA (' . $curve . ')', 'bits' => (int) ($d['bits'] ?? 0)];
    }
    if (defined('OPENSSL_KEYTYPE_ED25519') && $type === OPENSSL_KEYTYPE_ED25519) {
        return ['ok' => true, 'summary' => 'Ed25519', 'bits' => 256];
    }
    if (isset($d['ed25519'])) {
        return ['ok' => true, 'summary' => 'Ed25519', 'bits' => 256];
    }

    return ['ok' => true, 'summary' => 'Asymmetric key (type ' . (string) $type . ')', 'bits' => (int) ($d['bits'] ?? 0)];
}

/**
 * @return array{ok: bool, summary?: string, bits?: int, encrypted?: bool, error?: string}
 */
function crypto_verify_pem_private(string $pem, string $passphrase = ''): array {
    $res = openssl_pkey_get_private($pem, $passphrase !== '' ? $passphrase : '');
    if ($res === false) {
        return ['ok' => false, 'error' => 'Not a valid PEM private key, wrong passphrase, or unsupported format.'];
    }
    $d = openssl_pkey_get_details($res);
    if (!is_array($d)) {
        return ['ok' => false, 'error' => 'Unable to read private key details.'];
    }
    $encrypted = strpos($pem, 'ENCRYPTED') !== false || strpos($pem, 'Proc-Type: 4,ENCRYPTED') !== false;
    $type = $d['type'] ?? null;
    if ($type === OPENSSL_KEYTYPE_RSA) {
        $bits = (int) ($d['bits'] ?? 0);

        return ['ok' => true, 'summary' => 'RSA', 'bits' => $bits, 'encrypted' => $encrypted];
    }
    if ($type === OPENSSL_KEYTYPE_EC) {
        $curve = (string) ($d['ec']['curve_name'] ?? 'unknown');

        return ['ok' => true, 'summary' => 'ECDSA (' . $curve . ')', 'bits' => (int) ($d['bits'] ?? 0), 'encrypted' => $encrypted];
    }
    if (defined('OPENSSL_KEYTYPE_ED25519') && $type === OPENSSL_KEYTYPE_ED25519) {
        return ['ok' => true, 'summary' => 'Ed25519', 'bits' => 256, 'encrypted' => $encrypted];
    }
    if (isset($d['ed25519'])) {
        return ['ok' => true, 'summary' => 'Ed25519', 'bits' => 256, 'encrypted' => $encrypted];
    }

    return ['ok' => true, 'summary' => 'Private key (type ' . (string) $type . ')', 'bits' => (int) ($d['bits'] ?? 0), 'encrypted' => $encrypted];
}

/**
 * @return array{ok: bool, lines?: list<string>, error?: string}
 */
function crypto_ssh_keygen_inspect_openssh_line(string $opensshLine): array {
    if (!function_exists('shell_exec')) {
        return ['ok' => false, 'error' => 'shell_exec() is disabled; cannot run ssh-keygen.'];
    }
    $sshKeygenPath = trim((string) @shell_exec('command -v ssh-keygen 2>/dev/null'));
    if ($sshKeygenPath === '') {
        return ['ok' => false, 'error' => 'ssh-keygen is not available on this host.'];
    }
    $line = trim($opensshLine);
    if ($line === '' || strpos($line, "\n") !== false) {
        return ['ok' => false, 'error' => 'Provide a single-line OpenSSH public key (e.g. ssh-ed25519 AAAA...).'];
    }

    $tempBase = tempnam(sys_get_temp_dir(), 'sshchk_');
    if ($tempBase === false) {
        return ['ok' => false, 'error' => 'Unable to create temporary file.'];
    }
    $pubPath = $tempBase . '.pub';
    @rename($tempBase, $pubPath);
    if (@file_put_contents($pubPath, $line . PHP_EOL) === false) {
        @unlink($pubPath);

        return ['ok' => false, 'error' => 'Unable to write temporary key file.'];
    }

    $cmd = escapeshellarg($sshKeygenPath) . ' -l -E sha256 -f ' . escapeshellarg($pubPath) . ' 2>&1';
    $out = (string) @shell_exec($cmd);
    @unlink($pubPath);
    $out = trim($out);
    if ($out === '' || strpos(strtolower($out), 'error') !== false || strpos($out, 'not a public key') !== false) {
        return ['ok' => false, 'error' => $out !== '' ? $out : 'ssh-keygen could not read this public key.'];
    }

    $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\n|\r/', $out))));

    return ['ok' => true, 'lines' => $lines];
}

/**
 * Split a single "public key" paste into PEM vs OpenSSH, or return an error (auto mode only).
 *
 * @return array{pem: string, openssh: string, detected: ?string, error: ?string}
 */
function crypto_classify_verify_public_key(string $raw, string $mode): array {
    $raw = trim($raw);
    $empty = ['pem' => '', 'openssh' => '', 'detected' => null, 'error' => null];
    if ($raw === '') {
        return $empty;
    }

    $mode = strtolower($mode);
    if (!in_array($mode, ['auto', 'pem', 'openssh'], true)) {
        $mode = 'auto';
    }

    if ($mode === 'pem') {
        return ['pem' => $raw, 'openssh' => '', 'detected' => 'pem', 'error' => null];
    }
    if ($mode === 'openssh') {
        $lines = preg_split('/\r\n|\n|\r/', $raw);
        $first = '';
        foreach ($lines as $ln) {
            $ln = trim((string) $ln);
            if ($ln !== '') {
                $first = $ln;
                break;
            }
        }

        return ['pem' => '', 'openssh' => $first, 'detected' => 'openssh', 'error' => $first === '' ? 'OpenSSH public key line is empty.' : null];
    }

    if (preg_match('/-----BEGIN\s+(?:RSA\s+)?PUBLIC\s+KEY-----/i', $raw)
        || preg_match('/-----BEGIN\s+EC\s+PUBLIC\s+KEY-----/i', $raw)) {
        return ['pem' => $raw, 'openssh' => '', 'detected' => 'pem', 'error' => null];
    }

    $lines = preg_split('/\r\n|\n|\r/', $raw);
    $first = '';
    foreach ($lines as $ln) {
        $ln = trim((string) $ln);
        if ($ln !== '') {
            $first = $ln;
            break;
        }
    }
    if ($first !== '' && preg_match('/^(ssh-rsa|ssh-ed25519|ssh-dss|ecdsa-sha2-[a-z0-9]+)\s+[A-Za-z0-9+/]+=*(\s+.*)?$/', $first)) {
        return ['pem' => '', 'openssh' => $first, 'detected' => 'openssh', 'error' => null];
    }

    return [
        'pem' => '',
        'openssh' => '',
        'detected' => null,
        'error' => 'Could not tell if this is PEM or an OpenSSH public line. Choose <strong>PEM</strong> or <strong>OpenSSH one-line</strong> under Public key format, or check the paste.',
    ];
}

function handle_ssh_key_verify(array $req): string {
    $maxLen = 524288;
    $publicUnified = trim((string) req_get($req, 'verify_public_input', ''));
    $publicFormat = strtolower((string) req_get($req, 'verify_public_format', 'auto'));
    $publicPemLegacy = trim((string) req_get($req, 'verify_public_pem', ''));
    $publicOpensshLegacy = trim((string) req_get($req, 'verify_openssh_public', ''));
    $privatePem = trim((string) req_get($req, 'verify_private_pem', ''));
    $pass = trim((string) req_get($req, 'verify_private_passphrase', ''));

    $publicPem = '';
    $publicOpenssh = '';
    $detectNote = '';

    if ($publicUnified !== '') {
        if ($publicPemLegacy !== '' || $publicOpensshLegacy !== '') {
            return formatOutput('Use either the single <strong>Public key</strong> field or the legacy split fields, not both.', type: 'danger');
        }
        if (strlen($publicUnified) > $maxLen) {
            return formatOutput('Key material is too large.', type: 'danger');
        }
        $classified = crypto_classify_verify_public_key($publicUnified, $publicFormat);
        if (($classified['error'] ?? null) !== null && (string) $classified['error'] !== '') {
            return formatOutput((string) $classified['error'], type: 'danger');
        }
        $publicPem = (string) $classified['pem'];
        $publicOpenssh = (string) $classified['openssh'];
        if ($publicFormat === 'auto' && ($classified['detected'] ?? null) !== null) {
            $detectNote = $classified['detected'] === 'pem' ? 'PEM public' : 'OpenSSH one-line';
        }
    } else {
        $publicPem = $publicPemLegacy;
        $publicOpenssh = $publicOpensshLegacy;
    }

    if (strlen($publicPem) > $maxLen || strlen($publicOpenssh) > $maxLen || strlen($privatePem) > $maxLen) {
        return formatOutput('Key material is too large.', type: 'danger');
    }

    if ($publicPem === '' && $publicOpenssh === '' && $privatePem === '') {
        return formatOutput('Paste at least one of: a public key (PEM or OpenSSH), or a PEM private key.', type: 'danger');
    }

    $intro = 'Validation uses OpenSSL on the server and <code>ssh-keygen</code> for OpenSSH fingerprints when available. Private keys are not stored. Results follow the same order as the form (public checks, then private).';
    if ($detectNote !== '') {
        $intro .= ' <strong>Auto-detected public format:</strong> ' . htmlspecialchars($detectNote, ENT_QUOTES, 'UTF-8') . '.';
    }
    $output = formatOutput($intro, type: 'info');

    $derivedPublic = null;
    $privateSection = '';
    if ($privatePem !== '') {
        $pv = crypto_verify_pem_private($privatePem, $pass);
        if (!$pv['ok']) {
            $privateSection = formatOutput('<strong>PEM private key:</strong> ' . htmlspecialchars((string) $pv['error'], ENT_QUOTES, 'UTF-8'), type: 'danger');
        } else {
            $enc = !empty($pv['encrypted']) ? 'yes' : 'no';
            $bits = (int) ($pv['bits'] ?? 0);
            $privateSection = formatOutput(
                '<strong>PEM private key:</strong> valid — ' . htmlspecialchars((string) $pv['summary'], ENT_QUOTES, 'UTF-8')
                . ($bits > 0 ? ' — ' . $bits . ' bits' : '')
                . ' — passphrase protected: ' . $enc,
                type: 'success'
            );
            $derivedPublic = crypto_pem_public_from_private_pem($privatePem, $pass);
        }
    }

    $publicPemSection = '';
    if ($publicPem !== '') {
        $pub = crypto_verify_pem_public($publicPem);
        if (!$pub['ok']) {
            $publicPemSection = formatOutput('<strong>PEM public key:</strong> ' . htmlspecialchars((string) $pub['error'], ENT_QUOTES, 'UTF-8'), type: 'danger');
        } else {
            $bits = (int) ($pub['bits'] ?? 0);
            $publicPemSection = formatOutput(
                '<strong>PEM public key:</strong> valid — ' . htmlspecialchars((string) $pub['summary'], ENT_QUOTES, 'UTF-8')
                . ($bits > 0 ? ' — ' . $bits . ' bits' : ''),
                type: 'success'
            );
        }
    }

    $opensshSection = '';
    if ($publicOpenssh !== '') {
        $insp = crypto_ssh_keygen_inspect_openssh_line($publicOpenssh);
        if (!$insp['ok']) {
            $opensshSection = formatOutput('<strong>OpenSSH public key:</strong> ' . htmlspecialchars((string) $insp['error'], ENT_QUOTES, 'UTF-8'), type: 'warning');
        } else {
            $joined = htmlspecialchars(implode("\n", $insp['lines'] ?? []), ENT_QUOTES, 'UTF-8');
            $opensshSection = formatOutput('<strong>OpenSSH public key (ssh-keygen -l):</strong><pre class="mb-0" style="white-space:pre-wrap;">' . $joined . '</pre>', type: 'success');
        }
    }

    $output .= $publicPemSection . $opensshSection . $privateSection;

    if ($derivedPublic !== null && $publicPem !== '') {
        $match = crypto_pem_strings_equal($derivedPublic, $publicPem);
        $output .= formatOutput(
            '<strong>PEM public vs private:</strong> ' . ($match ? 'public key matches the private key.' : 'public key does <em>not</em> match the private key.'),
            type: $match ? 'success' : 'danger'
        );
    }

    if ($derivedPublic !== null && $publicOpenssh !== '') {
        $conv = crypto_openssh_to_pem_with_ssh_keygen($publicOpenssh);
        if (($conv['ok'] ?? false) !== true) {
            $output .= formatOutput(
                '<strong>OpenSSH public vs private:</strong> could not convert OpenSSH line to PEM to compare — '
                . htmlspecialchars((string) ($conv['error'] ?? 'unknown error'), ENT_QUOTES, 'UTF-8'),
                type: 'warning'
            );
        } else {
            $pemFromSsh = (string) $conv['pem'];
            $match = crypto_pem_strings_equal($derivedPublic, $pemFromSsh);
            $output .= formatOutput(
                '<strong>OpenSSH public vs private:</strong> ' . ($match ? 'OpenSSH public key matches the private key.' : 'OpenSSH public key does <em>not</em> match the private key.'),
                type: $match ? 'success' : 'danger'
            );
        }
    }

    return $output;
}

function handle_keypair_sign_verify(array $req): string {
    $mode = strtolower((string) req_get($req, 'keypair_sign_mode', 'sign'));
    $message = (string) req_get($req, 'keypair_message', '');
    $maxMsg = 262144;
    if (strlen($message) > $maxMsg) {
        return formatOutput('Message is too large (max ' . $maxMsg . ' bytes).', type: 'danger');
    }

    if ($mode === 'sign') {
        $privPem = trim((string) req_get($req, 'keypair_private_pem', ''));
        $pass = trim((string) req_get($req, 'keypair_private_passphrase', ''));
        if ($privPem === '') {
            return formatOutput('Private key PEM is required to sign.', type: 'danger');
        }

        $res = openssl_pkey_get_private($privPem, $pass !== '' ? $pass : '');
        if ($res === false) {
            return formatOutput('Could not load private key (check PEM and passphrase).', type: 'danger');
        }

        $digest = crypto_openssl_sign_verify_algorithm_for_key($res);
        $digestLabel = crypto_digest_label($digest);
        $sig = '';
        $signed = crypto_openssl_sign_rsa_padding_fallback($message, $sig, $res, $digest);
        if (!$signed['ok']) {
            $hint = (crypto_openssl_key_may_be_rsa_for_pss_retry($res) && PHP_VERSION_ID < 80500)
                ? ' RSA PSS-only keys need PHP 8.5+ for RSASSA-PSS signing here.'
                : '';

            return formatOutput(
                'Signing failed for this key type (RSA/EC/Ed25519 required), or the key cannot use the default RSA padding. RSA-PSS-only keys need RSASSA-PSS (PHP 8.5+).' . $hint,
                type: 'danger'
            );
        }

        $b64 = base64_encode($sig);
        $paddingHtml = '';
        if (($signed['rsa_padding'] ?? null) === 'pss') {
            $paddingHtml = ' <strong>RSA RSASSA-PSS</strong> padding — verify on PHP 8.5+ with the same message and key.';
        } elseif (($signed['rsa_padding'] ?? null) === 'pkcs1') {
            $paddingHtml = ' RSA PKCS#1 v1.5 padding.';
        }
        $info = formatOutput(
            'Signed with OpenSSL using <strong>' . htmlspecialchars($digestLabel, ENT_QUOTES, 'UTF-8') . '</strong> (chosen for this key type).' . $paddingHtml . ' Verify with the matching public key and the same message.',
            type: 'info'
        );

        return $info . crypto_render_key_output([
            [
                'label' => 'Signature (base64)',
                'content' => $b64,
                'filename' => 'signature.b64.txt',
            ],
        ], 'Sign result');
    }

    if ($mode === 'verify') {
        $pubPem = trim((string) req_get($req, 'keypair_public_pem', ''));
        $sigB64 = trim((string) req_get($req, 'keypair_signature_b64', ''));
        if ($pubPem === '') {
            return formatOutput('Public key PEM is required to verify.', type: 'danger');
        }
        if ($sigB64 === '') {
            return formatOutput('Signature (base64) is required.', type: 'danger');
        }

        $sig = base64_decode($sigB64, true);
        if ($sig === false || $sig === '') {
            return formatOutput('Signature is not valid base64.', type: 'danger');
        }

        $pub = openssl_pkey_get_public($pubPem);
        if ($pub === false) {
            return formatOutput('Could not load public key PEM.', type: 'danger');
        }

        $digest = crypto_openssl_sign_verify_algorithm_for_key($pub);
        $ok = crypto_openssl_verify_rsa_padding_fallback($message, $sig, $pub, $digest);
        if ($ok === 1) {
            return formatOutput(
                '<strong>Signature valid.</strong> Digest: ' . htmlspecialchars(crypto_digest_label($digest), ENT_QUOTES, 'UTF-8'),
                type: 'success'
            );
        }
        if ($ok === 0) {
            return formatOutput('Signature does <strong>not</strong> verify (wrong key, message, or signature).', type: 'danger');
        }

        return formatOutput('OpenSSL could not verify (error).', type: 'danger');
    }

    return formatOutput('Invalid mode. Use sign or verify.', type: 'danger');
}

function handle_keypair_generate(array $req): string {
    $mode = (string) req_get($req, 'generation_mode', 'server');
    $mode = strtolower($mode) ?: 'server';

    $algorithmRequest = req_get($req, 'algorithm', 'ed25519');
    $algorithm = is_string($algorithmRequest) ? $algorithmRequest : 'ed25519';
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

    $modeLabel = $mode === 'client'
        ? 'client-only (WebCrypto)'
        : ($mode === 'auto' ? 'auto (resolved to server-side OpenSSL for this run)' : 'server-side (OpenSSL)');
    $output = formatOutput(
        "Keypair generation mode: " . htmlspecialchars($modeLabel, ENT_QUOTES, 'UTF-8'),
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
                'label' => strtoupper($algo) . ' Public Key (PEM)',
                'content' => (string) $result['public_pem'],
                'filename' => "public-{$algo}{$suffix}.pem",
            ],
            [
                'label' => strtoupper($algo) . ' Private Key (PEM)',
                'content' => (string) $result['private_pem'],
                'filename' => "private-{$algo}{$suffix}.pem",
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

    $output = "<div class='card border-info mb-3'><h5 class='card-header'>Crypto Runtime Diagnostics (Server)</h5><div class='card-body'>";
    $output .= "<div class='mb-3'><strong>Available key algorithms:</strong> " . htmlspecialchars(implode(', ', $algorithms), ENT_QUOTES, 'UTF-8') . "</div>";
    $output .= "<div class='mb-3'><strong>ssh-keygen binary:</strong> {$sshKeygenStatus}</div>";
    $output .= "<div class='table-responsive'><table class='table table-dark table-striped'><thead><tr><th>Algorithm</th><th>OpenSSL Keygen</th><th>OpenSSH Export</th></tr></thead><tbody>" . implode('', $rows) . "</tbody></table></div>";
    $output .= "</div></div>";

    // Placeholder container for client-side diagnostics; populated by JS on the crypto diagnostics page.
    $output .= "<div id='clientCryptoDiagnosticsRoot' class='mt-3'></div>";

    return $output;
}

function handle_ssh_keygen(array $req): string {
    $mode = (string) req_get($req, 'generation_mode', 'server');
    $mode = strtolower($mode) ?: 'server';
    $comment = trim((string) req_get($req, 'ssh_comment', 'generated-by-phprand'));
    if ($comment !== '' && strlen($comment) > 200) {
        return formatOutput("SSH comment must be at most 200 characters.", type: "danger");
    }

    $algorithmRequest = req_get($req, 'algorithm', 'ed25519');
    $algorithm = is_string($algorithmRequest) ? $algorithmRequest : 'ed25519';
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

    $modeLabel = $mode === 'client'
        ? 'client-only (WebCrypto)'
        : ($mode === 'auto' ? 'auto (server-preferred for SSH / OpenSSH output)' : 'server-side (OpenSSL)');

    $output = '';
    $output .= formatOutput(
        "SSH generation mode: " . htmlspecialchars($modeLabel, ENT_QUOTES, 'UTF-8'),
        type: "info"
    );
    $output .= formatOutput(
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
        $publicPem = (string) $result['public_pem'];
        $items = [
            [
                'label' => strtoupper($algo) . ' Public Key (PEM)',
                'content' => $publicPem,
                'filename' => "public-{$algo}{$suffix}.pem",
                'ssh_output_slot' => 'pem-public',
            ],
        ];

        $sshLine = crypto_public_pem_to_openssh($publicPem, $comment);
        if (($sshLine['ok'] ?? false) === true) {
            $items[] = [
                'label' => strtoupper($algo) . ' Public Key (OpenSSH)',
                'content' => (string) $sshLine['line'],
                'filename' => "public-{$algo}{$suffix}.pub",
                'ssh_output_slot' => 'openssh-public',
            ];
        } else {
            $output .= formatOutput(
                strtoupper($algo) . ': ' . (string) ($sshLine['error'] ?? 'OpenSSH export unavailable.'),
                type: "warning"
            );
        }

        $items[] = [
            'label' => strtoupper($algo) . ' Private Key (PEM)',
            'content' => (string) $result['private_pem'],
            'filename' => "private-{$algo}{$suffix}.pem",
            'ssh_output_slot' => 'private-pem',
        ];

        $output .= crypto_render_ssh_key_output($items, strtoupper($algo) . ' SSH Key Material');
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
            'label' => strtoupper($algorithm) . " Public Key (PEM)",
            'content' => (string) $keyResult['public_pem'],
            'filename' => "public-{$algorithm}{$suffix}.pem",
        ],
        [
            'label' => strtoupper($algorithm) . " Private Key (PEM)",
            'content' => (string) $keyResult['private_pem'],
            'filename' => "private-{$algorithm}{$suffix}.pem",
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
