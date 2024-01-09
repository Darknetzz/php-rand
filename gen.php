<?php
header('Content-Type: text/html; charset=utf-8');

require_once("functions.php");

// echo "<hr>";
if (!isset($_POST['action'])) {
  die("No action specified.");
}


/* ───────────────────────────────────────────────────────────────────── */
/*                                 genstr                                */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['action']) && $_POST['action'] == "stringgen") {
  if (empty($_POST['digits'])) {
    die("You must enter a number of characters.");
  }
  if (!ctype_digit($_POST['digits']) || $_POST['digits'] > 1000000 || $_POST['digits'] < 1) {
    die("Invalid number of characters.");
  }

  $l        = (isset($_POST['l']) && $_POST['l'] == 1 ? "l": "");
  $u        = (isset($_POST['u']) && $_POST['u'] == 1 ? "u": "");
  $n        = (isset($_POST['n']) && $_POST['n'] == 1 ? "n": "");
  $s        = (isset($_POST['s']) && $_POST['s'] == 1 ? "s": "");
  $e        = (isset($_POST['e']) && $_POST['e'] == 1 ? "e": "");
  $c        = (isset($_POST['c']) && $_POST['c'] == 1 ? "c" : "");
  $charsets = $l.$u.$n.$s.$e.$c;
  $length   = intval($_POST['digits']);
  // $randomString = genStr($charsets, $length);
  // cleanString($randomString, $length);
  $randomString     = genStr($charsets, $length, $_POST['cchars']);
  $charactersLength = strlen($charsets);

  # Additional info in a collapsible
  $md5RS   = md5($randomString);
  $sha1RS  = hash('sha1',   $randomString);
  $sha256  = hash('sha256', $randomString);
  $sha512  = hash('sha512', $randomString);
  $poscomb = number_format($charactersLength**$length)." ($charactersLength^$length)";

  echo formatOutput($randomString);
  echo "

  <button class='btn btn-primary' type='button' data-bs-toggle='collapse' data-bs-target='#additionalInfo' aria-expanded='false' aria-controls='additionalInfo'>More info</button>

  <div id='additionalInfo' class='collapse' style='margin:15px;'>
    <div class='card border-info'>
      <h4 class='card-header text-bg-info'>Additional info</h4>
      <div class='card-body'>
          <b>SHA1:</b> $sha1RS<br>
          <b>SHA256:</b> $sha256<br>
          <b>SHA512:</b> $sha512<br>
          <b>MD5:</b> $md5RS<br>
          <b>Possible combinations:</b> $poscomb
      </div>
    </div>
  </div>
  ";
}
/* ───────────────────────────────────────────────────────────────────── */

if (!empty($_POST['repeatstr']) && !empty($_POST['repeatamt'])) {
  echo str_repeat($_POST['repeatstr'], $_POST['repeatamt']);
}

if (isset($_POST['base'])) {
  if (!isset($_POST['from']) || $_POST['from'] == "text") {
    $from = 36;
  } else {
    $from = $_POST['from'];
  }
  echo "<b>Input (Base $from):</b> $_POST[base]<br><br>";
  for ($i = 2; $i <= 36; $i++) {
    echo "<b>Base$i:</b> ".base_convert($_POST['base'], $from, $i)."<br>";
  }
  echo "Base64 encode: ".base64_encode($_POST['base'])."<br>";
  echo "Base64 decode: ".base64_decode($_POST['base'])."<br>";
}



if (isset($_POST['hash'])) {
  $types = ["SHA512", "SHA256", "SHA1", "MD5"];
  foreach ($types as $type) {
    echo "<b>$type:</b> ".hash($type, $_POST['hash'])."<br>";
  }
}
if (isset($_POST['bin2hex'])) {
    $bin2hex = bin2hex($_POST['bin2hex']);
    echo "<b>Your hex string would be:</b> $bin2hex";
}
if (isset($_POST['hex2bin'])) {
    if (ctype_xdigit($_POST['hex2bin']) && (strlen($_POST['hex2bin']) % 2) == 0) {
    $hex2bin = hex2bin($_POST['hex2bin']);
    echo "<b>Your binary string would be:</b> $hex2bin";
    } else {
    echo "<b>Input must only include hexadecimal and have an even length.</b>";
    }
}
if (isset($_POST['numgenfrom']) && isset($_POST['numgento'])) {
    $numgenfrom = $_POST['numgenfrom'];
    $numgento = $_POST['numgento'];
    if (strlen($numgenfrom) > 20 || strlen($numgento) > 20) {
        die("Please use numbers with less than 20 digits.");
    } 
    if (is_numeric($numgenfrom) === FALSE || is_numeric($numgento) === FALSE) {
        die("All values must be numeric!");
    }
    $seed = "None";
    if (!empty($_POST['numgenseed']) && $_POST['seed'] == 1) {
        $seed = $_POST['numgenseed'];
        if (!ctype_digit(strval($seed)) || strlen($seed) > 17) {
            echo "<b>Warning: Seed was not used because it's not a valid seed.</b><br>";
        } else {
            mt_srand($seed);
        }
    }
  $gen = mt_rand($numgenfrom, $numgento);
  echo "Your number is <h3>$gen</h3><br>
  Seed: $seed";
}
if (isset($_POST['rot'])) {
  if ($_POST['bruteforce'] == 1) {
    $alphabet = 26;
    $strrot = "";
    for ($i = 0; $i < $alphabet; $i++) {
        $strrot .= "<br>".str_rot($_POST['rot'], $i);
    }
  }
  elseif (!empty($_POST['rotations'])) {
    $rotations = $_POST['rotations']+26;
    $strrot = str_rot($_POST['rot'], $rotations);
  } else {
    $rotations = 13;
    $strrot = str_rot($_POST['rot'], $rotations);
  }
  echo "<b>Your string would be: </b>".$strrot;
}
if (isset($_POST['shuffler'])) {
  echo "<b>Your string would be: </b>".str_shuffle(utf8_encode($_POST['shuffler']));
}
if (isset($_POST['openssl'])) {
    //$key should have been previously generated in a cryptographically safe way, like openssl_random_pseudo_bytes
  $plaintext = $_POST['openssl'];
  $key = $_POST['key'];
  $iv = $_POST['iv'];
  $cipher = $_POST['cipher'];
  if (in_array($cipher, openssl_get_cipher_methods()))
  {
    if (empty($iv)) {
      $ivlen = openssl_cipher_iv_length($cipher);
      $iv = openssl_random_pseudo_bytes($ivlen);
    }
    if (empty($key)) {
      $key = openssl_random_pseudo_bytes($ivlen);
    }
      $ciphertext = @openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
      //store $cipher, $iv, and $tag for decryption later
      $original_plaintext = @openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $tag);
      echo "<b>Your OpenSSL encrypted string would be: </b>".$ciphertext."<br>
      <b>Encryption key:</b> $key<br>
      <b>Initialization vector (Hex representation):</b> ".bin2hex($iv);
  }
}
if (isset($_POST['openssld'])) {
  //$key should have been previously generated in a cryptographically safe way, like openssl_random_pseudo_bytes
$plaintext = $_POST['openssld'];
$key = $_POST['key'];
$iv = $_POST['iv'];
$cipher = $_POST['cipher'];
if (in_array($cipher, openssl_get_cipher_methods()))
{
  if (empty($iv)) {
    die("IV must be set. In decryption it can't be generated for you.");
  }
    # $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
    //store $cipher, $iv, and $tag for decryption later
    $original_plaintext = @openssl_decrypt($plaintext, $cipher, $key, $options=0, hex2bin($iv), $tag);
    if (empty($original_plaintext)) {
      die("Failed to decrypt. Are you sure you have the right encryption key and IV?");
    }
    echo "<b>Your OpenSSL decrypted string would be: </b>".$original_plaintext."<br>
    <b>Encryption key:</b> $key<br>
    <b>Initialization vector (Hex representation):</b> $iv";
}
}

/* -------------------------------------------------------------------------- */
/*                               Spin the wheel                               */
/* -------------------------------------------------------------------------- */
if (isset($_POST['wheelitem'])) {
  foreach ($_POST['wheelitem'] as $wheelitem) {
    if (!empty($wheelitem)) {
      $valid = true;
    }
  }

  if ($valid !== true) {
    die("No items");
  }
  
  $wheelitems = count($_POST['wheelitem']);
  $roll = mt_rand(0, $wheelitems-1);
  $item = $_POST['wheelitem'][$roll];
  while (empty($item)) {
    $item = $_POST['wheelitem'][mt_rand(0, $wheelitems-1)];
  }
  echo $item;

}

/* -------------------------------------------------------------------------- */
/*                                String tools                                */
/* -------------------------------------------------------------------------- */
if (!empty($_POST['string'])) {
  $string = $_POST['string'];
  $action = $_POST['action'];

  /* -------------------------------------------------------------------------- */
  /*                                   reverse                                  */
  /* -------------------------------------------------------------------------- */
  if ($action == "reverse") {
    $string = strrev($string);
  }

  

  /* -------------------------------------------------------------------------- */
  /*                                   shuffle                                  */
  /* -------------------------------------------------------------------------- */
  if ($action == "shuffle") {
    $string = str_shuffle($string);
  }

  /* -------------------------------------------------------------------------- */
  /*                                  leetspeak                                 */
  /* -------------------------------------------------------------------------- */
  if ($action == "l33tsp34k") {
    $translate = [
      'A' => '4',
      'E' => '3',
      'O' => '0',
      'T' => '7',
      'L' => '1',
      'S' => '5',
      'B' => '6',
    ];
    $string = str_ireplace(array_keys($translate), array_values($translate), $string);
    // $string = strtr($string, $translate);
  }

  /* -------------------------------------------------------------------------- */
  /*                                 randomcase                                 */
  /* -------------------------------------------------------------------------- */
  if ($action == "randomcase") {
    foreach (str_split($string) as $i => $char) {
      $roll = mt_rand(0,100);
      $string[$i] = strtolower($char);
      if ($roll >= 50) {
        $string[$i] = strtoupper($char);
      }
    }
  }

  echo $string;
}

echo "<hr>";

/* ───────────────────────────────────────────────────────────────────── */
/*                               Debug info                              */
/* ───────────────────────────────────────────────────────────────────── */
$postVars = trim(json_encode($_POST, JSON_PRETTY_PRINT));
echo "
<a class='btn btn-warning mb-3' data-bs-toggle='collapse' data-bs-target='#debugCard' aria-expanded='false' aria-controls='debugCard'>Debug info</a>
<div class='collapse' id='debugCard'>
  <div class='card border-warning'>
    <h4 class='card-header text-bg-warning'>
      DEBUG INFO
    </h4>
    <div class='card-body'>
        <pre>$postVars</pre>
    </div>
  </div>
</div>
";
?>