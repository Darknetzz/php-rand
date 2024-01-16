<?php
header('Content-Type: text/html; charset=utf-8');

require_once("functions.php");
/* ───────────────────────────────────────────────────────────────────── */
/*                               Debug info                              */
/* ───────────────────────────────────────────────────────────────────── */
$rVars = trim(json_encode($_REQUEST, JSON_PRETTY_PRINT));
$debug = "
  <a class='btn btn-warning' data-bs-toggle='collapse' data-bs-target='#debugCard' aria-expanded='false' aria-controls='debugCard'>".icon('bug-fill')."</a>
  <div class='collapse' id='debugCard' style='margin:15px;'>
    <div class='card border-warning'>
      <h4 class='card-header text-bg-warning'>
        ".icon('bug-fill')." Debug
      </h4>
      <div class='card-body'>
          <pre>$rVars</pre>
      </div>
    </div>
  </div>
";

// echo "<hr>";
if (!isset($_POST['action'])) {
  die("No action specified.");
}

$action = $_POST['action'];


/* ───────────────────────────────────────────────────────────────────── */
/*                                 genstr                                */
/* ───────────────────────────────────────────────────────────────────── */
if ($action == "stringgen") {
  if (empty($_POST['digits'])) {
    die(alert("You must enter a string length.", "danger"));
  }
  if (!ctype_digit($_POST['digits']) || $_POST['digits'] > 1000000 || $_POST['digits'] < 1) {
    die(alert("Invalid number of characters.", "danger"));
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

  echo "<hr>";
  echo formatOutput($randomString);
  echo "

  <button class='btn btn-info' type='button' data-bs-toggle='collapse' data-bs-target='#additionalInfo' aria-expanded='false' aria-controls='additionalInfo'>".icon('info-circle')."</button>

  <div id='additionalInfo' class='collapse' style='margin:15px;'>
    <div class='card border-info'>
      <h4 class='card-header text-bg-info'>".icon('info-circle')." Info</h4>
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


/* ───────────────────────────────────────────────────────────────────── */
/*                             Repeat string                             */
/* ───────────────────────────────────────────────────────────────────── */
if ($action == 'repeatstr') {
  echo str_repeat($_POST['repeatstr'], $_POST['repeatamt']);
}

/* ───────────────────────────────────────────────────────────────────── */
/*                                  Base                                 */
/* ───────────────────────────────────────────────────────────────────── */
if ($action == 'base64encode' || $action == 'base64decode' || $action == 'base') {
  if (!isset($_POST['from']) || $_POST['from'] == "text") {
    $from = 36;
  } else {
    $from = $_POST['from'];
  }

  $allBasesAreBelongToUs = "";

  $allBasesAreBelongToUs .= "<b>Input (Base $from):</b> $_POST[base]<br><br>";
  $allBasesAreBelongToUs .= "Base64 encode: ".base64_encode($_POST['base'])."<br>";
  $allBasesAreBelongToUs .= "Base64 decode: ".base64_decode($_POST['base'])."<br>";
  $allBasesAreBelongToUs .= "<hr>";
  for ($i = 2; $i <= 36; $i++) {
    $allBasesAreBelongToUs .= "<b>Base$i:</b> ".base_convert($_POST['base'], $from, $i)."<br>";
  }

  echo formatOutput($allBasesAreBelongToUs);
}


/* ───────────────────────────────────────────────────────────────────── */
/*                                  Hash                                 */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['hash'])) {
  $types  = ["SHA512", "SHA256", "SHA1", "MD5"];
  $output = "<table class='table border border-success'>";
  foreach ($types as $type) {
    $output .= "<tr><td><b>$type:</b></td> <td class='text-break'>".hash($type, $_POST['hash'])."</td></tr>";
  }
  $output .= "</table>";
  echo formatOutput("Input: $_POST[hash]<hr>".$output);
}

/* ───────────────────────────────────────────────────────────────────── */
/*                                bin2hex                                */
/* ───────────────────────────────────────────────────────────────────── */
if ($action == 'bin2hex' || $action == 'hex2bin') {
  $input  = $_POST['binhex'];
  $output = bin2hex($_POST['binhex']);
  $type   = "success";

  if ($action == 'bin2hex') {
    $output = bin2hex($input);
  }
  if ($action == 'hex2bin') {
    if (!ctype_xdigit($input) || (strlen($input) % 2) != 0) {
      $type   = "danger";
      $output = "<b>Input must only include hexadecimal and have an even length.</b>";
    } else {
      $output = hex2bin($input);
    }
  }
  echo formatOutput($output, type: $type);
}

/* ───────────────────────────────────────────────────────────────────── */
/*                                 numgen                                */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['numgenfrom']) && isset($_POST['numgento'])) {
    $numgenfrom = $_POST['numgenfrom'];
    $numgento   = $_POST['numgento'];
    $enableSeed = (isset($_POST['seed']) ? True : False);
    $seed       = Null;
    if ($enableSeed !== False) {
      $seed = $_POST['numgenseed'];
    }
    $gen = numGen($numgenfrom, $numgento, $seed);
    echo formatOutput(
      "
      $gen
      <hr>
      Seed: $seed"
    );
}

/* ───────────────────────────────────────────────────────────────────── */
/*                                  ROT                                  */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['rot'])) {
  if ($_POST['bruteforce'] == 1) {
    $alphabet = 26;
    $strrot = "<table>";
    for ($i = 0; $i < $alphabet; $i++) {
        $strrot .= "<tr><td><b>$i</b></td> <td>:</td> <td>".str_rot($_POST['rot'], $i)."</td></tr>";
    }
    $strrot .= "</table>";
  }
  elseif (!empty($_POST['rotations'])) {
    $rotations = $_POST['rotations']+26;
    $strrot = str_rot($_POST['rot'], $rotations);
  } else {
    $rotations = 13;
    $strrot = str_rot($_POST['rot'], $rotations);
  }
  echo formatOutput($strrot);
}

/* ───────────────────────────────────────────────────────────────────── */
/*                                Shuffler                               */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['shuffler'])) {
  echo "<b>Your string would be: </b>".str_shuffle(utf8_encode($_POST['shuffler']));
}

/* ───────────────────────────────────────────────────────────────────── */
/*                                OpenSSL                                */
/* ───────────────────────────────────────────────────────────────────── */
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

/* ───────────────────────────────────────────────────────────────────── */
/*                            OpenSSL Decrypt                            */
/* ───────────────────────────────────────────────────────────────────── */
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
if ($action == "spinwheel") {

  $wheelitem = (!empty($_POST['wheelitem']) ? $_POST['wheelitem'] : []);
  $spinsamt = (!empty($_POST['spinsamt'])   ? intval($_POST['spinsamt']) : 1);
  $unique    = (!empty($_POST['unique'])    ? intval($_POST['unique'])    : 0);

  $spin = spinWheel($wheelitem, $spinsamt, $unique);

  echo $spin;
}

/* -------------------------------------------------------------------------- */
/*                                String tools                                */
/* -------------------------------------------------------------------------- */
if ($action == "strtools") {
  $string = $_POST['string'];
  $action = $_POST['tool'];

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

  echo formatOutput($string);
}

echo $debug;
?>