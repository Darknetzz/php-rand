<?php

/* ===================================================================== */
/*                             FUNCTION: icon                            */
/* ===================================================================== */
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

/* ===================================================================== */
/*                            FUNCTION: alert                            */
/* ===================================================================== */
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

/* ───────────────────────────────────────────────────────────────────── */
/*                   FUNCTION:    spinner                                */
/* ───────────────────────────────────────────────────────────────────── */
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

/* ===================================================================== */
/*                        FUNCTION: sanitizeString                       */
/* ===================================================================== */
/**
 * Cleans a string.
 *
 * @param string $string The string to clean.
 * @return string The cleaned string.
 */
function sanitizeString($string) {
  $string = str_replace(' ', '-', $string);                 // Replaces all spaces with hyphens.
  $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);  // Removes special chars.
  $string = preg_replace('/-+/', '-', $string);             // Replaces multiple hyphens with single one.
  return $string;
}

/* ===================================================================== */
/*                         FUNCTION: cleanString                         */
/* ===================================================================== */
/**
 * Cleans the random string and displays it.
 *
 * @param string $randomString The random string to clean and display.
 * @param int $digitsint The number of characters in the random string.
 * @return void
 */
function cleanString($randomString, $digitsint = null) {
  $randomString = $randomString;
  $randomString = trim($randomString);
  echo "<h4><b>Your $digitsint character string: </b>";
  print_r($randomString);
  echo "</h4>";
}

/* ===================================================================== */
/*                         FUNCTION: returnClean                         */
/* ===================================================================== */
/**
 * Cleans the random string and returns it.
 *
 * @param string $randomString The random string to clean and return.
 * @return string The cleaned random string.
 */
function returnClean($randomString) {
  $randomString = $randomString;
  $randomString = trim($randomString);
  return print_r($randomString, True);
}

/* ===================================================================== */
/*                         FUNCTION: formatOutput                        */
/* ===================================================================== */
/**
 * Formats the output.
 *
 * @param string $response The response to print.
 * @return void
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
 * Performs the ROT (rotate) operation on a string.
 *
 * @param string $s The string to rotate.
 * @param int $n The number of rotations to perform.
 * @return string The rotated string.
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


/* ===================================================================== */
/*                            FUNCTION: genStr                           */
/* ===================================================================== */
/**
 * Generates a random string.
 *
 * @param string $charsets The character sets to use.
 * @param int $length The length of the string to generate.
 * @return string The generated string.
 */
function genStr(string $charsets, int $length = Null, $cchars = Null) {
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


/* ───────────────────────────────────────────────────────────────────── */
/*                FUNCTION:      spinWheel                               */
/* ───────────────────────────────────────────────────────────────────── */
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

/* ───────────────────────────────────────────────────────────────────── */
/*                   FUNCTION:   submitBtn                               */
/* ───────────────────────────────────────────────────────────────────── */
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
/*                     FUNCTION:   numGen                                */
/* ───────────────────────────────────────────────────────────────────── */
function numGen(int $from, int $to, string $seed = Null) {
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

/* ===================================================================== */
/*                             FUNCTION: calc                            */
/* ===================================================================== */
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

# =========================================================================== //
#                                FUNCTION: is_ip                              //
# =========================================================================== //
function is_ip(string $s): bool {
  return filter_var($s, FILTER_VALIDATE_IP) !== false; // v4 or v6
}

# =========================================================================== //
#                             FUNCTION: is_hostname                           //
# =========================================================================== //
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

/* ────────────────────────────────────────────────────────────────────────── */
/*                       FUNCTION:   ip2hex                                   */
/* ────────────────────────────────────────────────────────────────────────── */
function ip2hex ($ip, $split = False, $delimiter = ":") {
  $hex = bin2hex(inet_pton($ip));
  if ($split) {
    $hex = str_split($hex, 2);
    $hex = implode($delimiter, $hex);
  }
  return $hex;
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                       FUNCTION:   hex2ip                                   */
/* ────────────────────────────────────────────────────────────────────────── */
function hex2ip ($hex) {
  $hex = preg_replace('/[^a-f0-9]/i', '', $hex);  // Remove non-hex characters
  $ip  = inet_ntop(hex2bin($hex));
  return $ip;
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                   FUNCTION:   cidr2range                                   */
/* ────────────────────────────────────────────────────────────────────────── */
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


/* ────────────────────────────────────────────────────────────────────────── */
/*                       FUNCTION: range2cidr                                 */
/* ────────────────────────────────────────────────────────────────────────── */
/**
 * Converts an IP range to multiple CIDR notations if necessary.
 *
 * @param string $start The starting IP address of the range.
 * @param string $end The ending IP address of the range.
 * @return array An array of CIDR notations covering the IP range.
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

/* ────────────────────────────────────────────────────────────────────────── */
/*                       FUNCTION: subnetmask                                 */
/* ────────────────────────────────────────────────────────────────────────── */
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

?>
