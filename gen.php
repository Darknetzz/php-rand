<?php
header('Content-Type: text/html; charset=utf-8');

function cleanString($randomString, $digitsint) {
  $randomString = $randomString;
  $randomString = trim($randomString);
  echo "<h4><b>Your $digitsint character string: </b>";
  print_r($randomString);
  echo "</h4>";
}

function str_rot($s, $n = 13) {
  static $letters = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
  $n = (int)$n % 26;
  if (!$n) return $s;
  if ($n < 0) $n += 26;
  if ($n == 13) return str_rot13($s);
  $rep = substr($letters, $n * 2) . substr($letters, 0, $n * 2);
  return strtr($s, $letters, $rep);
}

echo "<hr>";

    if(
    isset($_POST['containnumbers']) &&
    isset($_POST['containletters']) &&
    isset($_POST['containuletters']) &&
    isset($_POST['containsymbols'])
  ) {
    $digits = $_POST['digits'];
    $digitsint = intval($digits);
    $characters = "";
    $numbers = "0123456789";
    $letters = "abcdefghijklmnopqrstuvwxyz";
    $uletters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $symbols = htmlspecialchars("!#¤%&\/()=?;:-_.,'\"*^<>{}[]@~+´`");
    $symbols_expanded = htmlspecialchars("ƒ†‡™•");
    $customcharset = $_POST['charset'];
    if ($_POST['containnumbers'] == 1) {
      $characters = $characters.$numbers;
    }
    if ($_POST['containletters'] == 1) {
      $characters = $characters.$letters;
    }
    if ($_POST['containuletters'] == 1) {
      $characters = $characters.$uletters;
    }
    if ($_POST['containsymbols'] == 1) {
      $characters = $characters.$symbols;
    }
    if ($_POST['containesymbols'] == 1) {
      $characters = $characters.$symbols_expanded;
    }
    if ($_POST['customizecharset'] == 1) {
      $characters = $characters.$customcharset;
    }
      # $characters = utf8_encode($characters);
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $digitsint; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
  cleanString($randomString, $digitsint);

$md5RS = md5($randomString);
$sha1RS = sha1($randomString);
$sha256 = hash('sha256', $randomString);
$sha512 = hash('sha512', $randomString);
$poscomb = number_format($charactersLength**$digitsint)." ($charactersLength^$digitsint)";
echo "<br><b>SHA1:</b> $sha1RS<br>
<b>SHA256:</b> $sha256<br>
<b>SHA512:</b> $sha512<br>
<b>MD5:</b> $md5RS<br>
<b>Possible combinations:</b> $poscomb";
}

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

?>