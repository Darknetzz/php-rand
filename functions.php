<?php

/**
 * Icon
 * 
 */
function icon($icon, $rem = 1, $color = Null) {
  $style = "style='";
  if ($color !== Null) {
    $style .= "color: {$color};";
  }
  if ($rem !== 1) {
    $style .= "font-size: {$rem}rem;";
  }
  $style .= "'";
  return "<i class='bi bi-{$icon} mx-1' {$style}></i>";
}

/**
 * Alert
 * 
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

/* ───────────────────────────────────────────────────────────────────── */
/*                                spinner                                */
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
function formatOutput($response, $size = 4, $type = "success", $responsetype = "html") {

  if (is_array($response)) {
    $response = json_encode($response, JSON_PRETTY_PRINT);
  }

  if (empty($response)) {
    $response = "No output";
  }

  if ($responsetype == "html") {
    $response = "
    <h$size class='output bg-$type-subtle text-$type-emphasis rounded-3' style='padding:20px;'>
    {$response}
    </h$size>
    ";
  }

  return trim($response);
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


/* ───────────────────────────────────────────────────────────────────── */
/*                               spinWheel                               */
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
/*                               submitBtn                               */
/* ───────────────────────────────────────────────────────────────────── */
function submitBtn(string $value = "", string $name = "action", string $text = "Generate", string $icon = "dice", string $size = "lg") {
  $icon = ($icon == "dice") ? "<span class='dice'></span> " : icon($icon);
  return '
    <button name="'.$name.'" value="'.$value.'" type="submit" class="genBtn btn btn-success btn-'.$size.' mb-3">
      '.$icon.' '.$text.'
    </button>';
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                  pageIcon                                  */
/* ────────────────────────────────────────────────────────────────────────── */
function pageIcon($page) {
  if ($page == "dashboard") {
    $icon = "house";
  }
  if ($page == "encoding") {
    $icon = "key-fill";
  }
  if ($page == "hash") {
    $icon = "key-fill";
  }
  if ($page == "hex") {
    $icon = "file-earmark-binary-fill";
  }
  if ($page == "number_tools") {
    $icon = "123";
  }
  if ($page == "openssl") {
    $icon = "key-fill";
  }
  if ($page == "rot") {
    $icon = "arrow-clockwise";
  }
  if ($page == "spin_the_wheel") {
    $icon = "circle-fill";
  }
  if ($page == "string_tools") {
    $icon = "alphabet";
  }
  if ($page == "serialization") {
    $icon = "list-ol";
  }
  if (empty($icon)) {
    $icon = "question-octagon";
  }
  return icon($icon);
}

/* ───────────────────────────────────────────────────────────────────── */
/*                              listModules                              */
/* ───────────────────────────────────────────────────────────────────── */
function listModules() {
  foreach (glob("modules/*.php") as $module) {

    $name       = str_replace('!', '', basename($module, '.php'));
    $formalname = ucwords(str_replace('_', ' ', $name));
    $modules[]  = [
      "icon"       => pageIcon($name),
      "formalName" => $formalname,
      "name"       => $name
    ];
  }

  return $modules;
}


/* ───────────────────────────────────────────────────────────────────── */
/*                                 numGen                                */
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

/* ────────────────────────────────────────────────────────────────────────── */
/*                                   ip2hex                                   */
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
/*                                   hex2ip                                   */
/* ────────────────────────────────────────────────────────────────────────── */
function hex2ip ($hex) {
  $hex = preg_replace('/[^a-f0-9]/i', '', $hex);  // Remove non-hex characters
  $ip  = inet_ntop(hex2bin($hex));
  return $ip;
}
?>