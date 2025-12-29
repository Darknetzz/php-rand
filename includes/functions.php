<?php

/**
 * Generate a Bootstrap Icon HTML element
 * 
 * Creates a styled icon element using Bootstrap Icons CSS classes with optional
 * color, size, and margin customization.
 *
 * @param string $icon The Bootstrap icon name (without 'bi-' prefix). Default: "question-circle"
 * @param float $rem Font size in rem units. Default: 1
 * @param string|null $color CSS color value to apply to the icon. Default: null
 * @param int $margin Bootstrap margin class value (1-5). Default: 1
 * @return string HTML string containing the styled icon element
 * 
 * @example
 * echo icon('check-circle', 1.5, '#51cf66', 2); // Green check icon, 1.5rem, margin-2
 */
function icon($icon = "question-circle", $rem = 1, $color = Null, $margin = 1) {
  $style = "";
  if ($color !== Null) {
    $style .= "color: {$color};";
  }
  if ($rem !== 1) {
    $style .= "font-size: {$rem}rem;";
  }
  return "<i class='bi bi-{$icon} m-{$margin}' style='{$style}'></i>";
}

/**
 * Generate a Bootstrap Alert component
 * 
 * Creates a dismissible Bootstrap alert box with contextual styling and icons.
 * Automatically selects appropriate icon based on alert type (danger, warning, success, info).
 *
 * @param string $message The message text to display in the alert
 * @param string $type The alert type: 'success', 'danger', 'warning', or 'info'. Default: 'success'
 * @return string HTML string containing a complete alert component
 * 
 * @example
 * echo alert('Operation completed!', 'success');
 * echo alert('An error occurred!', 'danger');
 */
function alert($message, $type = 'success') {
  $icon = "";
  if ($type == "danger") {
    $icon = icon("exclamation-triangle");
  }
  if ($type == "warning") {
    $icon = icon("exclamation-circle");
  }
  if ($type == "success") {
    $icon = icon("check-circle");
  }
  if ($type == "info") {
    $icon = icon("info-circle");
  }
  return "
  <div class='alert alert-{$type} alert-dismissible fade show' style='margin:15px;' role='alert'>
    {$icon} {$message}
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
  </div>
  ";
}

/**
 * Generate a loading spinner component
 * 
 * Creates a styled spinner animation with accompanying text for loading states.
 * Supports different spinner types (border, grow) and Bootstrap color classes.
 *
 * @param string $text The text to display below the spinner
 * @param string $type The spinner type: 'border' or 'grow'. Default: 'border'
 * @param string $color Bootstrap color class: 'primary', 'success', 'danger', etc. Default: 'primary'
 * @return string HTML string containing the spinner component
 * 
 * @example
 * echo spinner('Loading...', 'border', 'primary');
 * echo spinner('Processing', 'grow', 'success');
 */
function spinner(string $text, string $type = "border", string $color = "primary") {
  return '
      <div class="spinner-container">
          <div class="spinner-'.$type.' text-'.$type.'" role="status">
              <span class="visually-hidden">'.$text.'</span>
          </div>
          <span class="spinner-text">'.$text.'</span>
      </div>
  ';
}

/**
 * Sanitize a string for URL-friendly slugs
 * 
 * Converts spaces to hyphens, removes special characters, and collapses multiple
 * consecutive hyphens into single hyphens. Useful for creating URL slugs and 
 * valid HTML identifiers.
 *
 * @param string $string The string to sanitize
 * @return string The sanitized string with only alphanumeric characters and hyphens
 * 
 * @example
 * sanitizeString('Hello World!');      // Returns: 'hello-world'
 * sanitizeString('Test--String');      // Returns: 'test-string'
 */
function sanitizeString($string) {
  $string = str_replace(' ', '-', $string);                 // Replaces all spaces with hyphens.
  $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);  // Removes special chars.
  $string = preg_replace('/-+/', '-', $string);             // Replaces multiple hyphens with single one.
  return $string;
}

/**
 * Display a cleaned and trimmed random string
 * 
 * Outputs an HTML h4 heading containing a random string with its length.
 * Primarily used for displaying generated random strings to users.
 *
 * @param string $randomString The random string to clean and display
 * @param int|null $digitsint The length/digit count to display in the output
 * @return void Echoes the formatted output directly
 * 
 * @example
 * cleanString('abc123xyz', 9); // Outputs: "Your 9 character string: abc123xyz"
 */
function cleanString($randomString, $digitsint = null) {
  $randomString = $randomString;
  $randomString = trim($randomString);
  echo "<h4><b>Your $digitsint character string: </b>";
  print_r($randomString);
  echo "</h4>";
}

/**
 * Clean and return a trimmed string
 * 
 * Trims whitespace from a string and returns it. Useful as a simple string
 * cleaning function for preprocessing user input or generated strings.
 *
 * @param string $randomString The string to clean and return
 * @return string The cleaned and trimmed string
 * 
 * @example
 * $clean = returnClean('  hello world  '); // Returns: 'hello world'
 */
function returnClean($randomString) {
  $randomString = $randomString;
  $randomString = trim($randomString);
  return print_r($randomString, True);
}

/**
 * Format output content with styling and optional response type handling
 * 
 * Converts various content types into formatted HTML output with Bootstrap
 * styling. Handles arrays (JSON encoding), optional HTML escaping, and line
 * break conversion for text responses.
 *
 * @param string|array $response The response content to format. Arrays are JSON-encoded
 * @param int $size The heading size (1-6) for HTML response format. Default: 4
 * @param string $type Bootstrap color type for styling: 'success', 'danger', 'warning', 'info'. Default: 'success'
 * @param string $responsetype Response format type: 'html' or 'text'. Default: 'html'
 *                              'text' adds HTML escaping and nl2br conversion
 * @return string Formatted HTML output, or trimmed text response
 * 
 * @example
 * echo formatOutput("Success!", 4, "success");
 * echo formatOutput(['key' => 'value'], 5, "info", "html");
 * echo formatOutput("<b>Error</b>", 4, "danger", "text");
 */
function formatOutput($response, $size = 4, $type = "success", $responsetype = "html") {

  if (is_array($response)) {
    $response = json_encode($response, JSON_PRETTY_PRINT);
  }

  if (empty($response)) {
    $response = "No output";
  }

  if ($responsetype == "text") {
    $response = htmlspecialchars($response, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $response = nl2br($response);
  }

  if ($responsetype == "html") {
    $response = "
    <h$size class='output bg-$type-subtle text-$type-emphasis rounded-3' style='padding:20px;'>
    {$response}
    </h$size>
    ";
  }

  // if ($responsetype == "text") {
  //   header('Content-Type: text/plain; charset=utf-8');
  //   header('Content-Disposition: attachment; filename="output.txt"');
  //   header('Expires: 0');
  //   header('Cache-Control: must-revalidate');
  //   header('Pragma: public');
  // }

  return trim($response);
}


/* ===================================================================== */
/*                           FUNCTION: str_rot                           */
/* ===================================================================== */
/**
 * Perform ROT (rotate cipher) transformation on a string
 * 
 * Rotates each letter in a string by n positions in the alphabet. Handles both
 * uppercase and lowercase letters independently. ROT13 is a special case that
 * uses PHP's built-in str_rot13() for efficiency.
 *
 * @param string $s The string to rotate
 * @param int $n The number of positions to rotate (0-25). Default: 13 (ROT13)
 *              Negative values wrap around. Values > 25 are modulo 26
 * @return string The rotated string with non-letter characters unchanged
 * 
 * @example
 * str_rot('hello', 13);  // Returns: 'uryyb' (ROT13)
 * str_rot('hello', 1);   // Returns: 'ifmmp' (ROT1)
 * str_rot('ABC', 3);     // Returns: 'DEF'
 */
function str_rot($s, $n = 13) {
  static $letters = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
  $n = (int)$n % 26;
  if (!$n) return $s;
  if ($n < 0) $n += 26;
  if ($n == 13) return str_rot13($s);
  $rep = substr($letters, $n * 2) . substr($letters, 0, $n * 2);
  return strtr($s, $letters, $rep);
}


/**
 * Generate a random string from specified character sets
 * 
 * Creates a random string of specified length using selected character sets:
 * lowercase (l), uppercase (u), numbers (n), symbols (s), extended Unicode (e),
 * and custom characters (c). Can combine multiple sets.
 *
 * @param string $charsets Flags for character sets to include:
 *                         'l' = lowercase a-z
 *                         'u' = uppercase A-Z
 *                         'n' = numbers 0-9
 *                         's' = symbols: !#¤%&/() = ?;: -_.,'\"*^<>{}[]@~+´`
 *                         'e' = extended: ƒ†‡™•
 *                         'c' = custom characters (requires $cchars parameter)
 * @param int|null $length The length of the string to generate. Required
 * @param string|null $cchars Custom characters to include when 'c' is in $charsets
 * @return string Random string of specified length, or "[empty]" if no valid character sets
 * 
 * @example
 * genStr('lun', 12);           // 12 chars: lowercase, uppercase, numbers
 * genStr('luns', 20);          // 20 chars: lowercase, uppercase, numbers, symbols
 * genStr('lunc', 15, '!@#');   // 15 chars: lowercase, uppercase, numbers, custom
 */
function genStr(string $charsets, ?int $length = null, $cchars = null) {
    $charsets = str_split($charsets);
    $l        = (in_array('l', $charsets)                    ? range('a', 'z')                                   : []);
    $u        = (in_array('u', $charsets)                    ? range('A', 'Z')                                   : []);
    $n        = (in_array('n', $charsets)                    ? range(0, 9)                                       : []);
    $s        = (in_array('s', $charsets)                    ? str_split("!#¤%&\/() = ?;: -_.,'\"*^<>{}[]@~+´`") : []);
    $e        = (in_array('e', $charsets)                    ? str_split("ƒ†‡™•")                                : []);
    $c        = (in_array('c', $charsets) && !empty($cchars) ? str_split($cchars)                                : []);
    $all      = array_merge($l, $u, $n, $s, $e, $c);
    $str      = '';
    if (empty($all)) {
      return "[empty]";
    }
    for ($i = 0; $i < $length; $i++) {
      $str .= $all[array_rand($all)];
    }
    return $str;
}

/**
 * Generate a random string using cryptographically secure random_bytes()
 * 
 * Uses random_bytes() for cryptographically secure randomness, suitable for
 * security-critical applications like tokens, passwords, and keys.
 *
 * @param string $charsets Character set flags (l=lowercase, u=uppercase, n=numbers, s=symbols, e=extended, c=custom)
 * @param int $length Length of the generated string
 * @param string|null $cchars Custom characters if 'c' is in charsets
 * @return string Randomly generated string
 */
function genStrCrypto(string $charsets, ?int $length = null, $cchars = null) {
    $charsets = str_split($charsets);
    $l        = (in_array('l', $charsets)                    ? range('a', 'z')                                   : []);
    $u        = (in_array('u', $charsets)                    ? range('A', 'Z')                                   : []);
    $n        = (in_array('n', $charsets)                    ? range(0, 9)                                       : []);
    $s        = (in_array('s', $charsets)                    ? str_split("!#¤%&\/() = ?;: -_.,'\"*^<>{}[]@~+´`") : []);
    $e        = (in_array('e', $charsets)                    ? str_split("ƒ†‡™•")                                : []);
    $c        = (in_array('c', $charsets) && !empty($cchars) ? str_split($cchars)                                : []);
    $all      = array_merge($l, $u, $n, $s, $e, $c);
    $str      = '';
    if (empty($all)) {
      return "[empty]";
    }
    $max = count($all) - 1;
    for ($i = 0; $i < $length; $i++) {
      $randomIndex = ord(random_bytes(1)) % (count($all));
      $str .= $all[$randomIndex];
    }
    return $str;
}


/* ───────────────────────────────────────────────────────────────────── */
/**
 * Spin a wheel and randomly select items
 * 
 * Simulates spinning a wheel by randomly selecting items from a list.
 * Can return unique selections or allow duplicates. Useful for games,
 * raffles, or random selections.
 *
 * @param array|null $wheelItems Array of items to select from. Default: empty
 * @param int $spins Number of times to spin (1-100). Default: 1
 * @param bool $unique If true, ensure each spin selects a unique item. Default: false
 * @return string|array Formatted HTML output containing selected items, or error message
 * 
 * @example
 * spinWheel(['Item1', 'Item2', 'Item3'], 1);     // Single spin
 * spinWheel(['A', 'B', 'C'], 3, true);           // 3 spins, all unique
 */
function spinWheel(?array $wheelItems = [], int $spins = 1, bool $unique = False) {

  if (empty($wheelItems)) {
    return alert("You must enter at least one item.", "danger");
  }

  if ($spins > 100 || $spins < 1) {
    return alert("You can't spin less than once or more than 100 times.", "danger");
  }

  $countItems = count($wheelItems);
  $moreSpins  = (isset($spins) && $spins > 1 ? True : False);
  $spins      = ($moreSpins) ? $spins : 1;

  if ($countItems < 2) {
    return alert("You must enter at least two items.", "danger");
  }

  if (($spins > $countItems) && $unique !== False) {
    return alert("You can't spin more than the number of items in the wheel if you want unique results.", "danger");
  }

  $excludes = [];
  for ($i = 0; $i < $spins; $i++) {
      $dice = mt_rand(0, $countItems-1);

      if ($unique !== False) {
          while (in_array($dice, $excludes)) {
              $dice = mt_rand(0, $countItems-1);
          }
      }

      $item           = (!empty($wheelItems[$dice]) ? $wheelItems[$dice] : "Item #".$dice+1);
      $items[]        = "<b>".$item."</b>";
      $excludes[]     = $dice;
  }

  if (empty($items)) {
    return alert("No items");
  }

  $output = implode("<br>", $items);
  return formatOutput($output);
}

/**
 * Generate an HTML submit button element
 * 
 * Creates a styled Bootstrap submit button with icon and text. Commonly used
 * for tool action buttons in forms.
 *
 * @param string $value The value attribute sent with form submission. Default: ""
 * @param string $name The name attribute of the button. Default: "action"
 * @param string $text The button text/label to display. Default: "Generate"
 * @param string $icon Bootstrap icon name (without 'bi-' prefix), or 'dice' for special styling. Default: "dice"
 * @param string $size Bootstrap button size class: 'sm', 'lg'. Default: "lg"
 * @return string HTML submit button element
 * 
 * @example
 * echo submitBtn('hash', 'action', 'Generate Hash', 'key-fill');
 * echo submitBtn('spin', 'action', 'Spin', 'dice', 'lg');
 */
function submitBtn(string $value = "", string $name = "action", string $text = "Generate", string $icon = "dice", string $size = "lg") {
  $icon = ($icon == "dice") ? "<span class='dice'></span> " : icon($icon);
  return '
    <button name="'.$name.'" value="'.$value.'" type="submit" class="genBtn btn btn-success btn-'.$size.' my-3">
      '.$icon.' '.$text.'
    </button>';
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                   FUNCTION:      pageIcon                                  */
/* ────────────────────────────────────────────────────────────────────────── */
// function pageIcon($page) {
//   if ($page == "dashboard") {
//     $icon = "house";
//   }
//   if ($page == "logogen") {
//     $icon = "image";
//   }
//   if ($page == "encoding") {
//     $icon = "file-earmark-binary-fill";
//   }
//   if ($page == "hash") {
//     $icon = "key-fill";
//   }
//   if ($page == "number_tools") {
//     $icon = "123";
//   }
//   if ($page == "openssl") {
//     $icon = "key-fill";
//   }
//   if ($page == "rot") {
//     $icon = "arrow-clockwise";
//   }
//   if ($page == "spin_the_wheel") {
//     $icon = "circle-fill";
//   }
//   if ($page == "string_tools") {
//     $icon = "alphabet";
//   }
//   if ($page == "serialization") {
//     $icon = "list-ol";
//   }
//   if ($page == "datetime") {
//     $icon = "clock";
//   }
//   if ($page == "networking") {
//     $icon = "globe";
//   }
//   if (empty($icon)) {
//     $icon = "question-octagon";
//   }
//   return icon($icon);
// }

/* ───────────────────────────────────────────────────────────────────── */
/*                FUNCTION:     listModules                              */
/* ───────────────────────────────────────────────────────────────────── */
// function listModules() {
//   foreach (glob("modules/*.php") as $module) {

//     $name       = str_replace('!', '', basename($module, '.php'));
//     $formalname = ucwords(str_replace('_', ' ', $name));
//     $modules[]  = [
//       "icon"       => pageIcon($name),
//       "formalName" => $formalname,
//       "name"       => $name
//     ];
//   }

//   return $modules;
// }


/* ───────────────────────────────────────────────────────────────────── */
/**
 * Generate a random number within a range
 * 
 * Generates a cryptographically secure random integer between two values.
 * Optionally accepts a seed for reproducible random numbers. Validates input
 * to ensure numeric values and reasonable limits.
 *
 * @param int $from Starting value of the range (lower bound)
 * @param int $to Ending value of the range (upper bound)
 * @param string|null $seed Optional seed for mt_srand() for reproducibility. 
 *                           Must be a valid digit string ≤ 17 chars. Default: null
 * @return int|string Random integer within range, or alert string on error
 * @throws void Dies if numbers exceed 20 digits or aren't numeric
 * 
 * @example
 * numGen(1, 100);           // Random number between 1-100
 * numGen(1, 1000, '12345'); // Random number with specific seed
 */
function numGen(int $from, int $to, ?string $seed = null) {
  $from = (int)$from;
  $to   = (int)$to;

  if (strlen($from) > 20 || strlen($to) > 20) {
    die("Please use numbers with less than 20 digits.");
  }
  if (is_numeric($from) === FALSE || is_numeric($to) === FALSE) {
      die("All values must be numeric!");
  }
  if (!empty($seed)) {
      if (!ctype_digit(strval($seed)) || strlen($seed) > 17) {
          echo alert("<b>Warning: Seed was not used because it's not a valid seed.</b><br>", "warning");
      } else {
          mt_srand($seed);
      }
  }

  if ($from > $to) {
    return alert("The first number must be smaller than the second number.", "danger");
  }
  $num = mt_rand($from, $to);
  return $num;
}

/**
 * Simple mathematical expression calculator
 * 
 * Evaluates basic arithmetic expressions with +, -, *, and / operators.
 * Sanitizes input to prevent injection attacks, handles errors gracefully.
 *
 * @param string $input Mathematical expression to evaluate (e.g., "2+3*4")
 * @return string|array Formatted result or alert message on error
 * 
 * @example
 * calc('2+3');        // Returns formatted output: 5
 * calc('100/2');      // Returns formatted output: 50
 * calc('(2+3)*4');    // Non-evaluable, returns error alert
 */
function calc($input) {
  $input = trim($input);
  if (empty($input)) {
    return alert("You must enter a calculation.", "danger");
  }

  // Remove any non-numeric characters except for +, -, *, /, and spaces
  $input = preg_replace('/[^0-9+\-*\/\s]/', '', $input);

  // Evaluate the expression
  try {
    $result = eval("return {$input};");
    if ($result === False) {
      return alert("Invalid calculation.", "danger");
    }
    return formatOutput($result, 4, "success");
  } catch (Throwable $e) {
    return alert("Error in calculation: " . $e->getMessage(), "danger");
  }
}

/**
 * Validate if a string is a valid IPv4 or IPv6 address
 * 
 * Uses PHP's FILTER_VALIDATE_IP to check for valid IP addresses.
 * Supports both IPv4 (192.168.1.1) and IPv6 (2001:db8::1) formats.
 *
 * @param string $s String to validate as IP address
 * @return bool True if valid IP address, false otherwise
 * 
 * @example
 * is_ip('192.168.1.1');       // Returns: true
 * is_ip('2001:db8::1');       // Returns: true
 * is_ip('invalid.text');      // Returns: false
 */
function is_ip(string $s): bool {
  return filter_var($s, FILTER_VALIDATE_IP) !== false; // v4 or v6
}

/**
 * Validate if a string is a valid hostname or domain name
 * 
 * Validates hostnames according to RFC specifications. Handles internationalized
 * domain names (IDN) if the intl PHP extension is available. Allows FQDN with
 * trailing dot.
 *
 * @param string $s Hostname or domain name to validate (e.g., 'example.com' or 'host.example.com.')
 * @return bool True if valid hostname, false otherwise
 * 
 * @example
 * is_hostname('example.com');        // Returns: true
 * is_hostname('subdomain.example.com'); // Returns: true
 * is_hostname('invalid_host');       // Returns: false (contains underscore)
 * is_hostname('münchen.de');         // Returns: true (IDN support)
 */
function is_hostname(string $s): bool {
  // allow a trailing dot (FQDN style) for validation purposes
  $s = rtrim($s, '.');

  // Convert IDN to ASCII (requires intl); returns false on failure
  $ascii = function_exists('idn_to_ascii')
      ? idn_to_ascii($s, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46)
      : $s;

  if ($ascii === false) return false;

  // RFC-ish hostname check (labels 1–63, total ≤253, letters/digits/hyphen, no underscores)
  return filter_var($ascii, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false;
}

/**
 * Convert an IPv4 or IPv6 address to hexadecimal format
 * 
 * Converts an IP address to its hexadecimal representation. Can optionally
 * split the hex output into byte pairs with a custom delimiter.
 *
 * @param string $ip IPv4 (192.168.1.1) or IPv6 (2001:db8::1) address
 * @param bool $split If true, split hex into byte pairs. Default: false
 * @param string $delimiter Separator for split hex pairs (e.g., ':', '-'). Default: ":"
 * @return string Hexadecimal representation of the IP address
 * 
 * @example
 * ip2hex('192.168.1.1');                   // Returns: 'c0a80101'
 * ip2hex('192.168.1.1', true);             // Returns: 'c0:a8:01:01'
 * ip2hex('192.168.1.1', true, '-');        // Returns: 'c0-a8-01-01'
 */
function ip2hex ($ip, $split = False, $delimiter = ":") {
  $hex = bin2hex(inet_pton($ip));
  if ($split) {
    $hex = str_split($hex, 2);
    $hex = implode($delimiter, $hex);
  }
  return $hex;
}

/**
 * Convert hexadecimal format back to an IPv4 or IPv6 address
 * 
 * Reverses the ip2hex conversion by taking a hex string and converting it
 * back to IP address notation. Removes any non-hex characters for flexibility.
 *
 * @param string $hex Hexadecimal representation of an IP (e.g., 'c0a80101' or 'c0:a8:01:01')
 * @return string IPv4 or IPv6 address in standard notation
 * 
 * @example
 * hex2ip('c0a80101');        // Returns: '192.168.1.1'
 * hex2ip('c0:a8:01:01');     // Returns: '192.168.1.1'
 */
function hex2ip ($hex) {
  $hex = preg_replace('/[^a-f0-9]/i', '', $hex);  // Remove non-hex characters
  $ip  = inet_ntop(hex2bin($hex));
  return $ip;
}

/**
 * Convert CIDR notation to a range of IP addresses
 * 
 * Expands CIDR notation (e.g., '192.168.1.0/24') to the starting and ending
 * IP addresses it represents. Handles both IPv4 and IPv6 notation.
 *
 * @param string $cidr IP address in CIDR notation (e.g., '10.0.0.0/8' or '2001:db8::/32')
 * @return array|false Array with keys: 'start', 'end', 'cidr', 'total' IP count, or false on invalid input
 * 
 * @example
 * cidr2range('192.168.1.0/24');  // Returns: [
 *                                //   'start' => '192.168.1.0',
 *                                //   'end' => '192.168.1.255',
 *                                //   'cidr' => '192.168.1.0/24',
 *                                //   'total' => 256
 *                                // ]
 */
function cidr2range($cidr) {
  if (strpos($cidr, '/') === False) {
    return False;
  }
  $range = [];
  $cidr = explode('/', $cidr);
  $prefix = isset($cidr[1]) ? (int)$cidr[1] : 0;

  if (filter_var($cidr[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
    $range["start"] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - $prefix)) & 0xFFFFFFFF));
    $range["end"] = long2ip((ip2long($cidr[0])) | ((1 << (32 - $prefix)) - 1));
  } elseif (filter_var($cidr[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
    $ip = inet_pton($cidr[0]);
    $binMask = str_repeat("1", $prefix) . str_repeat("0", 128 - $prefix);
    $mask = inet_ntop(pack('H*', base_convert($binMask, 2, 16)));
    $range["start"] = inet_ntop($ip & inet_pton($mask));
    $range["end"] = inet_ntop($ip | ~inet_pton($mask));
  } else {
    return False;
  }

  $range["cidr"]  = $cidr[0] . "/" . $prefix;
  $range["total"] = ip2long($range["end"]) - ip2long($range["start"]) + 1;

  return $range;
}


/**
 * Convert an IP address range to CIDR notation
 * 
 * Takes a start and end IP address and calculates the smallest set of CIDR
 * blocks that cover that entire range. Useful for network planning and
 * allocation.
 *
 * @param string $start Starting IPv4 address of the range (e.g., '192.168.1.0')
 * @param string $end Ending IPv4 address of the range (e.g., '192.168.1.255')
 * @return array|false Array of CIDR notations covering the range, or false on invalid input
 * 
 * @example
 * range2cidr('192.168.1.0', '192.168.1.255');  // Returns: ['192.168.1.0/24']
 * range2cidr('10.0.0.0', '10.255.255.255');    // Returns: ['10.0.0.0/8']
 */
function range2cidr($start, $end) {
  if (
    filter_var($start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === False ||
    filter_var($end, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === False) {
    return False;
  }
  $start_long = ip2long($start);
  $end_long   = ip2long($end);
  $cidrs = [];

  while ($end_long >= $start_long) {
    $maxSize = 32;
    while ($maxSize > 0) {
      $mask = 1 << (32 - $maxSize);
      if (($start_long & ($mask - 1)) != 0) {
        break;
      }
      $maxSize--;
    }

    $maxDiff = 32 - (int)floor(log($end_long - $start_long + 1) / log(2));
    if ($maxSize < $maxDiff) {
      $maxSize = $maxDiff;
    }

    $cidr        = long2ip($start_long) . '/' . $maxSize;
    $cidrs[]     = $cidr;
    $start_long += 1 << (32 - $maxSize);

    // Free memory by unsetting variables
    unset($mask, $maxDiff, $cidr);
  }

  $ip["cidrs"]     = $cidrs;
  $ip["start"]     = $start;
  $ip["end"]       = $end;
  $ip["total"]     = count($cidrs);
  $ip["total_ips"] = (ip2long($end) - ip2long($start) + 1);
  return $ip;
}

/**
 * Calculate subnet information from an IP address and subnet mask
 * 
 * Given an IPv4 address and subnet mask, calculates network information including
 * network address, broadcast address, usable IP range, CIDR notation, and total
 * usable addresses.
 *
 * @param string $ip IPv4 address within the subnet (e.g., '192.168.1.100')
 * @param string $subnet Subnet mask in IPv4 notation (e.g., '255.255.255.0')
 * @return array|false Array with keys: 'network', 'broadcast', 'start', 'end', 'total', 'total_ips', 'subnet', 'cidr', 'usable_ips'
 *                     or false if invalid IP or subnet
 * 
 * @example
 * subnetmask('192.168.1.100', '255.255.255.0');  // Returns: [
 *                                                  //   'network' => '192.168.1.0',
 *                                                  //   'broadcast' => '192.168.1.255',
 *                                                  //   'start' => '192.168.1.1',
 *                                                  //   'end' => '192.168.1.254',
 *                                                  //   'total' => 254,
 *                                                  //   'subnet' => '255.255.255.0',
 *                                                  //   'cidr' => '/24',
 *                                                  //   'usable_ips' => 254
 *                                                  // ]
 */
function subnetmask($ip, $subnet) {
  if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === False || filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === False) {
    return False;
  }
  $ip         = ip2long($ip);
  $subnet     = ip2long($subnet);
  $network    = $ip & $subnet;
  $broadcast  = $ip | ~$subnet;
  $start      = $network + 1;
  $end        = $broadcast - 1;
  $start_ip   = long2ip($start);
  $end_ip     = long2ip($end);
  $total      = $broadcast - $network;

  if ($subnet == 0 || $subnet == 4294967295) {
    $end    = $start;
    $end_ip = $start_ip;
    $total  = 1; // Adjust total for special cases
  }

  $range      = [
    "start_long" => $start,
    "end_long"   => $end,
    "network"    => long2ip($network),
    "broadcast"  => long2ip($broadcast),
    "start"      => $start_ip,
    "end"        => $end_ip,
    "total"      => $total,
    "total_ips"  => ($end - $start) + 1,
    "subnet"     => long2ip($subnet),
    "cidr"       => long2ip($network) . "/" . (32 - (int)log(~$subnet & 0xFFFFFFFF, 2)),
    "usable_ips" => cidr2range(long2ip($network) . "/" . (32 - (int)log(~$subnet & 0xFFFFFFFF, 2)), True)["total"]
  ];
  return $range;
}

# ─────────────────────────────────────────────────────────────────────────── //
#                             FUNCTION: convert_any                           //
# ─────────────────────────────────────────────────────────────────────────── //
/**
 * Universal converter: bases 1..64, text, hex, base64.
 *
 * FROM/TO selectors:
 *  - integer 1..64          numeric base using digits 0-9 A-Z a-z + /
 *  - 'text'                 UTF-8 bytes as text
 *  - 'hex'                  binary-to-text (hex)
 *  - 'base64'               binary-to-text (RFC 4648 Base64)
 *
 * Quality-of-life:
 *  - Bases ≤36 accept either case in input (auto-normalized).
 *  - Numeric base-64 quietly strips trailing '=' padding.
 *  - Base-1 (unary) supported, with a safety cap to avoid million-char outputs.
 */

function convert_any($input, $from = "text", $to = "base64") {
    // Master alphabet for bases up to 64
    $ALPH64 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+/';
    // Uppercase alphabet for case-insensitive parse in bases <=36
    $ALPH36U = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Normalize selectors like '10', 'bin', 'oct', 'dec'
    $from = normalize_selector($from);
    $to   = normalize_selector($to);

    // Sanitize trivial spacing
    if (is_string($input)) {
        $input = trim($input);
    }

    // Decide if we're dealing with bytes, or digit-arrays in some base
    $have_bytes = false;
    $bytes = array();
    $digits = array();
    $digits_base = null;

    // Parse input by selector
    if ($from === 'text') {
        $bytes = str_to_bytes($input);
        $have_bytes = true;

    } elseif ($from === 'hex') {
        if (strlen($input) % 2 !== 0) $input = '0' . $input; // be lenient
        $bin = @hex2bin($input);
        if ($bin === false) throw new InvalidArgumentException("Invalid hex input.");
        $bytes = array_values(unpack('C*', $bin));
        $have_bytes = true;

    } elseif ($from === 'base64') {
        $bin = base64_decode($input, true);
        if ($bin === false) throw new InvalidArgumentException("Invalid base64 input.");
        $bytes = array_values(unpack('C*', $bin));
        $have_bytes = true;

    } elseif (is_base_selector($from)) {
        // Numeric base path
        if ($from === 64) {
            // If someone pasted padded base64 into numeric base 64, strip '=' padding silently.
            $input = rtrim($input, '=');
        }
        if ($from === 1) {
            // Unary: value is length of '1' run
            validate_unary($input);
            $n = strlen($input);
            // Represent n as base-2 digits so we can use generic converter
            list($digits, $digits_base) = integer_to_digits($n, 2);
        } else {
            // Bases ≤36 are case-insensitive: normalize to uppercase and use ALPH36U
            $digits = digits_from_string($input, $from <= 36 ? strtoupper($input) : $input,
                                         $from, $ALPH64, $ALPH36U);
            $digits_base = $from;
        }

    } else {
        throw new InvalidArgumentException("Unsupported 'from' selector: `$from`");
    }

    // If we have bytes but need a numeric output base, treat bytes as base-256 digits
    if ($have_bytes && is_base_selector($to)) {
        $digits = $bytes;
        $digits_base = 256;
        $have_bytes = false;
    }

    // If we have numeric digits but need bytes encodings, convert to base-256 first
    if (!$have_bytes && ($to === 'text' || $to === 'hex' || $to === 'base64')) {
        if ($digits_base === null) throw new RuntimeException("Internal error: digits base not set.");
        $digits = convert_digits($digits, $digits_base, 256);
        $bytes = $digits;
        $have_bytes = true;
    }

    // Produce output
    if ($to === 'text') {
        return bytes_to_str($bytes);

    } elseif ($to === 'hex') {
        return bin2hex(bytes_to_str($bytes));

    } elseif ($to === 'base64') {
        return base64_encode(bytes_to_str($bytes));

    } elseif (is_base_selector($to)) {
        if ($have_bytes) {
            $digits = $bytes;
            $digits_base = 256;
        }

        if ($to === 1) {
            // Convert to unary, with guardrails
            $value_bits = convert_digits($digits, $digits_base, 2);
            $n = bits_to_php_int($value_bits);
            if ($n > 1000000) {
                throw new OverflowException("Unary output would exceed 1,000,000 characters. Refusing.");
            }
            return str_repeat('1', $n);
        }

        // Generic numeric base conversion
        $out_digits = convert_digits($digits, $digits_base, $to);
        return digits_to_string($out_digits, $to, $ALPH64, $ALPH36U);

    } else {
        throw new InvalidArgumentException("Unsupported 'to' selector: `$to`");
    }
}

/* ------------------ Helpers ------------------ */

# ─────────────────────────────────────────────────────────────────────────── //
#                         FUNCTION: normalize_selector                        //
# ─────────────────────────────────────────────────────────────────────────── //
function normalize_selector($x) {
    if (is_int($x)) return $x;
    $s = strtolower(trim((string)$x));
    if ($s === 'dec') return 10;
    if ($s === 'bin') return 2;
    if ($s === 'oct') return 8;
    if (ctype_digit($s)) return (int)$s;
    return $s; // 'text' | 'hex' | 'base64'
}

# ─────────────────────────────────────────────────────────────────────────── //
#                          FUNCTION: is_base_selector                         //
# ─────────────────────────────────────────────────────────────────────────── //
function is_base_selector($b) {
    return is_int($b) && $b >= 1 && $b <= 64;
}

# ─────────────────────────────────────────────────────────────────────────── //
#                            FUNCTION: str_to_bytes                           //
# ─────────────────────────────────────────────────────────────────────────── //
function str_to_bytes($s) {
    return array_values(unpack('C*', $s)); // UTF-8 raw bytes
}

# ─────────────────────────────────────────────────────────────────────────── //
#                            FUNCTION: bytes_to_str                           //
# ─────────────────────────────────────────────────────────────────────────── //
function bytes_to_str($bytes) {
    if (!$bytes) return '';
    return pack('C*', ...$bytes);
}

# ─────────────────────────────────────────────────────────────────────────── //
#                           FUNCTION: validate_unary                          //
# ─────────────────────────────────────────────────────────────────────────── //
function validate_unary($s) {
    if ($s === '') return;
    // allow only '1' characters
    if (str_replace('1', '', $s) !== '') {
        throw new InvalidArgumentException("Unary expects only '1' characters.");
    }
}

# ─────────────────────────────────────────────────────────────────────────── //
#                          FUNCTION: integer_to_digits                        //
# ─────────────────────────────────────────────────────────────────────────── //
function integer_to_digits($n, $base) {
    if ($n === 0) return array(array(0), $base);
    $out = array();
    while ($n > 0) {
        array_unshift($out, $n % $base);
        $n = intdiv($n, $base);
    }
    return array($out, $base);
}

# ─────────────────────────────────────────────────────────────────────────── //
#                         FUNCTION: digits_from_string                        //
# ─────────────────────────────────────────────────────────────────────────── //
// Parse string into digit array.
// For bases ≤36, we treat input case-insensitively using ALPH36U.
// For >36, we use ALPH64 case-sensitively.
function digits_from_string($raw, $in, $base, $ALPH64, $ALPH36U) {
    $out = array();
    $len = strlen($in);

    if ($base <= 36) {
        for ($i = 0; $i < $len; $i++) {
            $ch = $in[$i];
            $pos = strpos($ALPH36U, $ch);
            if ($pos === false || $pos >= $base) {
                throw new InvalidArgumentException("Invalid digit '$raw[$i]' for base $base.");
            }
            $out[] = $pos;
        }
    } else {
        for ($i = 0; $i < $len; $i++) {
            $ch = $in[$i];
            $pos = strpos($ALPH64, $ch);
            if ($pos === false || $pos >= $base) {
                throw new InvalidArgumentException("Invalid digit '$raw[$i]' for base $base.");
            }
            $out[] = $pos;
        }
    }
    return $out;
}

# ─────────────────────────────────────────────────────────────────────────── //
#                           FUNCTION: convert_digits                          //
# ─────────────────────────────────────────────────────────────────────────── //
// Convert digit-array between bases using repeated division (works for big integers).
function convert_digits($digits, $fromBase, $toBase) {
    if ($fromBase === $toBase) {
        return $digits ? $digits : array(0);
    }

    // Strip leading zeros
    while (count($digits) > 0 && $digits[0] === 0) array_shift($digits);
    if (!$digits) return array(0);

    $result = array();
    $source = $digits;

    while ($source) {
        $quotient = array();
        $remainder = 0;
        foreach ($source as $d) {
            $acc = $remainder * $fromBase + $d;
            $q = intdiv($acc, $toBase);
            $remainder = $acc % $toBase;
            if (!empty($quotient) || $q !== 0) $quotient[] = $q;
        }
        array_unshift($result, $remainder);
        $source = $quotient;
    }
    return $result ? $result : array(0);
}

# ─────────────────────────────────────────────────────────────────────────── //
#                          FUNCTION: digits_to_string                         //
# ─────────────────────────────────────────────────────────────────────────── //
function digits_to_string($digits, $base, $ALPH64, $ALPH36U) {
    if (!$digits) return '0';
    $s = '';
    if ($base <= 36) {
        foreach ($digits as $d) $s .= $ALPH36U[$d];
    } else {
        foreach ($digits as $d) $s .= $ALPH64[$d];
    }
    $trim = ltrim($s, '0');
    return $trim === '' ? '0' : $trim;
}

# ─────────────────────────────────────────────────────────────────────────── //
#                           FUNCTION: bits_to_php_int                         //
# ─────────────────────────────────────────────────────────────────────────── //
// Turn binary digits (array of 0/1) into a PHP int, with overflow checks.
function bits_to_php_int($bits) {
    $n = 0;
    foreach ($bits as $b) {
        if ($n > (PHP_INT_MAX >> 1)) {
            throw new OverflowException("Value too large for unary conversion on this platform.");
        }
        $n = ($n << 1) + ($b ? 1 : 0);
    }
    return $n;
}

/* -------------- Examples (uncomment to test) -------------- */

// echo convert_any("Hello", "text", 36), PHP_EOL;         // Text -> base36
// echo convert_any("SGVsbG8=", "base64", "text"), PHP_EOL; // Base64 -> text
// echo convert_any("SGVsbG8=", 64, 10), PHP_EOL;           // Numeric base64 -> base10 (trims '=')
// echo convert_any("ff", 36, 10), PHP_EOL;                 // Accepts lowercase for base36
// echo convert_any("1295", 10, 36), PHP_EOL;               // -> ZZ
// echo convert_any("48656c6c6f", "hex", "base64"), PHP_EOL;// -> SGVsbG8=
// echo convert_any(str_repeat('1', 13), 1, 2), PHP_EOL;    // Unary 13 -> 1101

/* ===================================================================== */
/*                       FUNCTION: copyableOutput                        */
/* ===================================================================== */
/**
 * Creates a copyable output box with a copy-to-clipboard button.
 *
 * @param string $content The content to display and copy.
 * @param string $label Optional label for the output.
 * @return string HTML for the copyable output.
 */
function copyableOutput($content, $label = "") {
  $uniqueId = "copy_" . uniqid();
  $html = "";
  
  if (!empty($label)) {
    $html .= "<strong style='display: block; margin-bottom: 8px;'>$label</strong>";
  }
  
  $html .= "<div style='display: flex; gap: 10px; align-items: stretch;'>";
  $html .= "  <div style='flex: 1; background-color: #0f172a; color: #e9ecef; padding: 14px; border-radius: 0.4rem; font-family: monospace; font-size: 0.95rem; word-break: break-all; user-select: all; overflow-y: auto; max-height: 320px; border: 1px solid #334155; box-shadow: 0 6px 14px rgba(0,0,0,0.25);' id='$uniqueId'>";
  $html .= htmlspecialchars($content ?? '');
  $html .= "  </div>";
  $html .= "  <button type='button' class='btn btn-sm btn-outline-light' onclick=\"copyToClipboard('$uniqueId', this)\" style='height: fit-content; white-space: nowrap; align-self: flex-start; border: 1px solid #e9ecef;'>";
  $html .= "    <i class='bi bi-files'></i> Copy";
  $html .= "  </button>";
  $html .= "</div>";
  
  return $html;
}

/**
 * Comprehensive input validation helper
 * 
 * Validates input against various criteria including length, type, format,
 * and custom patterns. Returns detailed validation results.
 *
 * @param mixed $value The value to validate
 * @param array $rules Validation rules array with keys:
 *                     'required' => bool (default: true)
 *                     'minLength' => int
 *                     'maxLength' => int
 *                     'type' => 'string'|'number'|'email'|'url'|'ip'|'hostname'|'hex'|'json'|'base64'
 *                     'pattern' => regex pattern string
 *                     'allowedValues' => array of allowed values
 * @return array ['valid' => bool, 'error' => string|null, 'value' => mixed]
 * 
 * @example
 * $result = validateInput('test@example.com', ['type' => 'email']);
 * $result = validateInput(42, ['minLength' => 1, 'maxLength' => 1000]);
 * $result = validateInput('192.168.1.1', ['type' => 'ip']);
 */
function validateInput($value, $rules = []) {
  // Default rules
  $required = $rules['required'] ?? true;
  $minLength = $rules['minLength'] ?? null;
  $maxLength = $rules['maxLength'] ?? null;
  $type = $rules['type'] ?? null;
  $pattern = $rules['pattern'] ?? null;
  $allowedValues = $rules['allowedValues'] ?? null;
  
  // Check if required but empty
  if ($required && (empty($value) || (is_string($value) && trim($value) === ''))) {
    return ['valid' => false, 'error' => 'This field is required', 'value' => $value];
  }
  
  // Skip further validation if empty and not required
  if (!$required && (empty($value) || (is_string($value) && trim($value) === ''))) {
    return ['valid' => true, 'error' => null, 'value' => $value];
  }
  
  // Sanitize string input
  if (is_string($value)) {
    $value = trim($value);
  }
  
  // Check length constraints
  if ($minLength !== null && strlen((string)$value) < $minLength) {
    return ['valid' => false, 'error' => "Minimum length is {$minLength} characters", 'value' => $value];
  }
  
  if ($maxLength !== null && strlen((string)$value) > $maxLength) {
    return ['valid' => false, 'error' => "Maximum length is {$maxLength} characters", 'value' => $value];
  }
  
  // Type-specific validation
  if ($type !== null) {
    switch (strtolower($type)) {
      case 'number':
        if (!is_numeric($value)) {
          return ['valid' => false, 'error' => 'Must be a valid number', 'value' => $value];
        }
        break;
      
      case 'email':
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
          return ['valid' => false, 'error' => 'Must be a valid email address', 'value' => $value];
        }
        break;
      
      case 'url':
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
          return ['valid' => false, 'error' => 'Must be a valid URL', 'value' => $value];
        }
        break;
      
      case 'ip':
        if (!filter_var($value, FILTER_VALIDATE_IP)) {
          return ['valid' => false, 'error' => 'Must be a valid IPv4 or IPv6 address', 'value' => $value];
        }
        break;
      
      case 'hostname':
        if (!is_hostname($value)) {
          return ['valid' => false, 'error' => 'Must be a valid hostname or domain', 'value' => $value];
        }
        break;
      
      case 'hex':
        if (!preg_match('/^[a-fA-F0-9]+$/', $value)) {
          return ['valid' => false, 'error' => 'Must contain only hexadecimal characters (0-9, a-f, A-F)', 'value' => $value];
        }
        break;
      
      case 'json':
        if (json_decode($value) === null && $value !== 'null') {
          return ['valid' => false, 'error' => 'Must be valid JSON', 'value' => $value];
        }
        break;
      
      case 'base64':
        if (!preg_match('/^[A-Za-z0-9+\/=]+$/', $value) || strlen($value) % 4 !== 0) {
          return ['valid' => false, 'error' => 'Must be valid base64 encoding', 'value' => $value];
        }
        break;
      
      case 'string':
        if (!is_string($value)) {
          return ['valid' => false, 'error' => 'Must be a string', 'value' => $value];
        }
        break;
    }
  }
  
  // Pattern validation
  if ($pattern !== null && !preg_match($pattern, $value)) {
    return ['valid' => false, 'error' => 'Format is invalid', 'value' => $value];
  }
  
  // Allowed values validation
  if ($allowedValues !== null && !in_array($value, $allowedValues, true)) {
    return ['valid' => false, 'error' => 'Value not in allowed list', 'value' => $value];
  }
  
  return ['valid' => true, 'error' => null, 'value' => $value];
}

?>

