<?php

/**
 * Icon
 * 
 */
function icon($icon, $rem = 1, $color = Null) {
  if ($color !== Null) {
    $color = "color: {$color}";
  }
  if ($rem !== 1) {
    $rem = "font-size: {$rem}rem";
  }
  return "<i class='bi bi-{$icon}' style='{$rem}; {$color};'></i>";
}

/**
 * Alert
 * 
 */
function alert($message, $type = 'success') {
  return "
  <div class='alert alert-{$type} alert-dismissible fade show' style='margin:15px;' role='alert'>
  {$message}
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
  ";
}

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

/**
 * Formats the output.
 *
 * @param string $response The response to print.
 * @return void
 */
function formatOutput($response) {
  return "
  <div class='output'>
    <h5 class='p-3 bg-success-subtle text-success-emphasis border border-primary-subtle rounded-3' style='margin:15px;'>
      ".print_r($response, True)."
    </h5>
  </div>
  ";
}

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
?>