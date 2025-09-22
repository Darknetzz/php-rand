<?php

/* ===================================================================== */
/*                             FUNCTION: icon                            */
/* ===================================================================== */
function icon($icon, $rem = 1, $color = Null, $margin = 1) {
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
    <button name="'.$name.'" value="'.$value.'" type="submit" class="genBtn btn btn-success btn-'.$size.' mb-3">
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
 * Universal converter: between bases 1..64, text, hex, and base64.
 *
 * $from and $to can be:
 *  - integer 1..64  (numeric base with digits 0-9 A-Z a-z + /)
 *  - 'text'         (UTF-8 bytes)
 *  - 'hex'          (binary-to-text encoding, not numeric base)
 *  - 'base64'       (binary-to-text encoding, not numeric base)
 *
 * Notes:
 *  - Base-1 is unary: the value is the count of '1' characters.
 *  - For 'text', bytes are interpreted/produced as UTF-8.
 *  - For 'hex' and 'base64', standard encoders/decoders are used.
 */
function convert_any($input, $from = "text", $to = "text") {
    $alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+/';

    // Normalize selectors
    $normalize = function($x) {
        if (is_int($x)) return $x;
        $x = strtolower(trim($x));
        if ($x === 'dec') return 10;
        if ($x === 'bin') return 2;
        if ($x === 'oct') return 8;
        if (ctype_digit($x)) return (int)$x;
        return $x; // 'text' | 'hex' | 'base64'
    };
    $from = $normalize($from);
    $to   = $normalize($to);

    // Helpers
    $str_to_bytes = function(string $s): array {
        // UTF-8 bytes
        return array_values(unpack('C*', $s));
    };
    $bytes_to_str = function(array $bytes): string {
        // From bytes to string (assumes they represent UTF-8)
        return pack('C*', ...$bytes);
    };

    // Validate bases
    $is_base = function($b) { return is_int($b) && $b >= 1 && $b <= 64; };

    // Convert digits string (in base 2..64) to array of digit values
    $digits_from_string = function(string $s, int $base) use ($alphabet): array {
        $out = [];
        $len = strlen($s);
        for ($i = 0; $i < $len; $i++) {
            $ch = $s[$i];
            $v = strpos($alphabet, $ch);
            if ($v === false || $v >= $base) {
                throw new InvalidArgumentException("Invalid digit '$ch' for base $base.");
            }
            $out[] = $v;
        }
        return $out;
    };

    // Convert array of digit values to string using alphabet
    $string_from_digits = function($digits) use ($alphabet) {
        if (!$digits) return '0';
        $s = '';
        foreach ($digits as $d) {
            $s .= $alphabet[$d];
        }
        // Trim leading zeros, but leave a single '0' if all were zeros
        return ltrim($s, '0') === '' ? '0' : ltrim($s, '0');
    };

    // Core base conversion on big integers represented as digit arrays.
    // Input: digits in base $fromBase. Output: digits in base $toBase.
    $convert_digits = function(array $digits, int $fromBase, int $toBase): array {
        if ($fromBase === $toBase) return $digits ?: [0];

        // Strip leading zeros
        while (count($digits) > 0 && $digits[0] === 0) array_shift($digits);
        if (!$digits) return [0];

        $result = [];
        $source = $digits;

        // Repeated division algorithm
        while ($source) {
            $quotient = [];
            $remainder = 0;
            foreach ($source as $d) {
                $acc = $remainder * $fromBase + $d;
                $q = intdiv($acc, $toBase);
                $remainder = $acc % $toBase;
                if ($quotient || $q !== 0) $quotient[] = $q;
            }
            array_unshift($result, $remainder);
            $source = $quotient;
        }
        return $result ?: [0];
    };

    // Special handling for base-1 (unary)
    $from_unary_to_digits = function(string $s): array {
        if ($s === '') return [0];
        // Only '1' allowed
        if (trim(str_replace('1','',$s)) !== '') {
            throw new InvalidArgumentException("Unary expects only '1' characters.");
        }
        // Value = count of '1'; return as base-10 digits array [value] in base-10 representation would be huge,
        // but we want it in internal "digits array" form. We'll convert from decimal integer to an array later.
        // Easier: create digits in base-256 directly from integer count by repeated division.
        $n = strlen($s);
        // Return as base-10-like internal? We'll just convert via generic path after constructing base-10-like digits.
        // Build decimal digits array is overkill; better route: create base-2 digits directly.
        // To keep it simple: produce base-2 digits for $n.
        if ($n === 0) return [0];
        // Build binary digits of $n
        $bits = [];
        while ($n > 0) { array_unshift($bits, $n & 1); $n >>= 1; }
        return $bits; // base-2 digits
    };

    $digits_from_number_string = function($s, $base) use ($digits_from_string, $from_unary_to_digits) {
        if ($base === 1) {
            return $from_unary_to_digits($s); // returns base-2 digits
        } elseif ($base >= 2 && $base <= 64) {
            return $digits_from_string($s, $base);
        } else {
            throw new InvalidArgumentException("Unsupported from-base.");
        }
    };

    // Step 1: normalize input into a byte array (if 'text'/'hex'/'base64') or a digit array with a known base.
    $have_bytes = false;
    $bytes = [];
    $digits = [];
    $digits_base = null; // base of $digits if we use digits route

    if ($from === 'text') {
        $bytes = $str_to_bytes($input);
        $have_bytes = true;
    } elseif ($from === 'hex') {
        if (strlen($input) % 2 !== 0) {
            // tolerate odd length by prefixing a zero
            $input = '0' . $input;
        }
        $bin = @hex2bin($input);
        if ($bin === false) throw new InvalidArgumentException("Invalid hex input.");
        $bytes = array_values(unpack('C*', $bin));
        $have_bytes = true;
    } elseif ($from === 'base64') {
        $bin = base64_decode($input, true);
        if ($bin === false) throw new InvalidArgumentException("Invalid base64 input.");
        $bytes = array_values(unpack('C*', $bin));
        $have_bytes = true;
    } elseif ($is_base($from)) {
        // Parse numeric string into digits
        if ($from === 1) {
            $digits = $digits_from_number_string($input, 1); // base-2 digits
            $digits_base = 2;
        } else {
            $digits = $digits_from_number_string($input, $from);
            $digits_base = $from;
        }
    } else {
        throw new InvalidArgumentException("Unsupported 'from' selector.");
    }

    // If we have bytes and the target is a numeric base, first turn bytes (base-256) into target-base digits.
    if ($have_bytes && ($is_base($to))) {
        // Convert bytes (each 0..255) base-256 -> base-$to
        $digits = $bytes;           // treat as digits in base 256
        $digits_base = 256;
        if ($to === 1) {
            // bytes -> integer -> unary
            // First convert to base-10/any, but easier: convert to base-2 then count → but unary requires exact count of value.
            // We'll convert base-256 digits directly to base-2 digits, then to an integer count would be enormous for large inputs.
            // Unary of large data is impractical; still, we support it.
        }
    }

    // If we have digits and the target is bytes/text/hex/base64, we need to convert digits to base-256 first.
    if (!$have_bytes && ($to === 'text' || $to === 'hex' || $to === 'base64')) {
        // Convert digits_base -> 256
        if (!isset($digits_base)) throw new RuntimeException("Internal error: digits base not set.");
        $digits = $convert_digits($digits, $digits_base, 256);
        $digits_base = 256;
        $bytes = $digits;
        $have_bytes = true;
    }

    // Now produce the final output depending on $to.
    if ($to === 'text') {
        // From bytes to UTF-8 string
        return $bytes_to_str($bytes);
    } elseif ($to === 'hex') {
        return bin2hex($bytes_to_str($bytes));
    } elseif ($to === 'base64') {
        return base64_encode($bytes_to_str($bytes));
    } elseif ($is_base($to)) {
        // We have either:
        //  - digits with base $digits_base (from numeric input), or
        //  - bytes with base-256 (from text/hex/base64).
        if ($have_bytes) {
            $digits = $bytes;
            $digits_base = 256;
        }
        if ($to === 1) {
            // Convert digits_base -> base-10 magnitude to replicate as unary.
            // We'll convert to base-2 digits, then to an integer count. For large values, this will be huge.
            $bits = $convert_digits($digits, $digits_base, 2);
            // Convert binary digits to a PHP integer if possible, otherwise build unary by repeated subtraction (impractical).
            // Realistically, unary of anything nontrivial is astronomical. We’ll guard against absurd sizes.
            // If value is too large, refuse.
            $value = 0;
            foreach ($bits as $b) {
                // Prevent overflow explosions
                if ($value > PHP_INT_MAX >> 1) {
                    throw new OverflowException("Result too large to represent in unary safely.");
                }
                $value = ($value << 1) + $b;
            }
            if ($value > 1000000) { // hard cap to avoid memory meltdown
                throw new OverflowException("Unary output would exceed 1,000,000 characters. Refusing.");
            }
            return str_repeat('1', $value);
        } else {
            $out_digits = $convert_digits($digits, $digits_base, $to);
            return $string_from_digits($out_digits);
        }
    } else {
        throw new InvalidArgumentException("Unsupported 'to' selector.");
    }
}

?>