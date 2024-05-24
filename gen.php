<?php
header('Content-Type: text/html; charset=utf-8');

require_once("functions.php");

do {
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

  # Return type (html / text)
  $responsetype = "html";
  if (!empty($_POST['responsetype'])) {
    $responsetype = $_POST['responsetype'];
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
  /*                                binhex                                 */
  /* ───────────────────────────────────────────────────────────────────── */
  if ($action == 'hex') {
    $tool         = $_POST['tool'];
    $type         = "success";
    $split        = (!empty($_POST['split']) ? True : False);
    $delimiter    = (!empty($_POST['delimiter']) ? $_POST['delimiter'] : ":");
    $chunk_length = (!empty($_POST['chunklength']) ? $_POST['chunklength'] : 2);
    $linebreak    = (!empty($_POST['linebreak']) ? $_POST['linebreak'] : Null);

    if ($tool == "hex2bin" || $tool == "bin2hex") {
      $input = $_POST['binhex'];
    }
    if ($tool == "ip2hex" || $tool == "hex2ip") {
      $input = $_POST['iphex'];
    }
    $input        = trim($input);

    if (empty($input)) {
      echo formatOutput("Empty input", type: "danger");
      break;
    }


    # Bin2Hex
    if ($tool == 'bin2hex') {
      $output = bin2hex($input);

      # Split
      if ($split == True) {
        $output = chunk_split($output, $chunk_length, $delimiter);
        $output = rtrim($output, $delimiter);
      }
    }

    # Hex2Bin
    if ($tool == 'hex2bin') {
      $input = preg_replace('/[^a-zA-Z0-9]/', '', $input);
      if (!ctype_xdigit($input) || (strlen($input) % 2) != 0) {
        $type   = "danger";
        $output = "<b>Input must only include hexadecimal and have an even length.</b>";
      } else {
        $output = hex2bin($input);
      }
    }

    # IP2Hex
    if ($tool == 'ip2hex') {
      $input = str_replace(" ", "", $input);

      # More than one IP given
      if (strpos($input, ",") !== False) {
        $ip_array = explode(",", $input);
        $output = "";
        foreach ($ip_array as $ip) {
          $output .= ip2hex($ip, $split, $delimiter);
          if ($linebreak !== Null) {
            $output .= "<br>";
          }
        }
      } else {
        $output = ip2hex($input, $split, $delimiter);
      }
    }

    # Hex2IP
    if ($tool == 'hex2ip') {
      $output = hex2ip($input);
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
  if ($action == 'openssl') {

      $tool   = !empty($_POST['tool'])                  ? $_POST['tool']   : '';
      $string = !empty($_POST['openssl'])               ? $_POST['openssl']: '';
      $key    = !empty($_POST['key'])                   ? $_POST['key']    : '';
      $cipher = !empty($_POST['cipher'])                ? $_POST['cipher'] : '';
      $iv     = !empty($_POST['iv'])                    ? $_POST['iv']     : '';

      # No cipher provided, use aes-256-cbc as default
      if (empty($_POST['cipher'])) {
        $cipher = "aes-256-cbc";
        if (!in_array($cipher, openssl_get_cipher_methods())) {
          die(formatOutput("Cipher `$cipher` is not supported.", type: "danger"));
        }
        echo formatOutput("No cipher selected, using `$cipher` as default.", type: "danger");
      }

      # No IV provided, generate random IV
      if (empty($_POST['iv'])) {
        $ivlen  = (openssl_cipher_iv_length($cipher) / 2);
        $iv     = bin2hex(openssl_random_pseudo_bytes($ivlen));
        echo formatOutput("No IV specified, using random IV: ".$iv, type: "warning");
      }

      # No key provided, warn user
      if (empty($_POST['key'])) {
        echo formatOutput("No key specified, <b>this is unsafe</b>.", type: "warning");
      }
      //$key should have been previously generated in a cryptographically safe way, like openssl_random_pseudo_bytes

        if ($tool == "encrypt") {
          $string = openssl_encrypt($string, $cipher, $key, iv: $iv);
        }
        if ($tool == "decrypt") {
          $string = openssl_decrypt($string, $cipher, $key, iv: $iv);
        }
        if (empty($string)) {
          $string = "[empty]";
        }
        echo formatOutput("
          <b>".$string."</b>
          <hr>
          <b>Encryption key:</b> $key<br>
          <b>Initialization vector (Hex representation):</b> ".$iv);
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


  /* ───────────────────────────────────────────────────────────────────── */


  /* ───────────────────────────────────────────────────────────────────── */
  /*                                Serialization                           */
  /* ───────────────────────────────────────────────────────────────────── */
  if ($action == "serialization") {
    $type  = $_POST['type'];
    $input = $_POST['input'];
    if (empty($type) || empty($input)) {
      echo formatOutput("You must select a type and enter data.", type: "danger");
      break;
    }

    $xmlparser = xml_parser_create();
    # Detect input
    if (json_validate($input)) {
      $input = json_decode($input, True);
    } elseif (yaml_parse($input)) {
      $input = yaml_parse($input);
    } elseif (xml_parse($xmlparser, $input)) {
      $input = xml_parse($xmlparser, $input);
    } else {
      echo formatOutput("Invalid input. It must valid JSON, XML or YAML.", type: "danger");
      break;
    }

    # Convert to desired type
    if ($type == "JSON") {
      $output = json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    } elseif ($type == "XML") {
      $output = array2xml($input);
    } elseif ($type == "YAML") {
      $output = yaml_emit($input);
    }
    echo formatOutput($output, responsetype: "text");
  }

  /* -------------------------------------------------------------------------- */
  /*                                String tools                                */
  /* -------------------------------------------------------------------------- */
  if ($action == "stringtools") {
    $string          = (!empty($_POST['string']) ? $_POST['string']: "");
    $tool            = (!empty($_POST['tool'])  ? $_POST['tool']   : "");
    $outputToTextbox = (isset($_POST['outputToTextbox']) ? True    :  False);

    if (empty($string)) {
      echo formatOutput("You must enter a string.", type: "danger");
    }
    if (empty($tool)) {
      echo formatOutput("You must select a tool.", type: "danger");
    }


    /* ───────────────────────────────────────────────────────────────────── */
    /*                                  trim                                 */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "trim") {
      $string = trim($string);
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                               removewhitespace                        */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "removewhitespace") {
      $string = preg_replace('/\s+/', '', $string);
    }
    

    /* -------------------------------------------------------------------------- */
    /*                                   reverse                                  */
    /* -------------------------------------------------------------------------- */
    if ($tool == "reverse") {
      $string = strrev($string);
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                                 repeat                                */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "repeat") {
      $string = str_repeat($string, 2);
    }

    /* -------------------------------------------------------------------------- */
    /*                                   shuffle                                  */
    /* -------------------------------------------------------------------------- */
    if ($tool == "shuffle") {
      $string = str_shuffle($string);
    }

    /* -------------------------------------------------------------------------- */
    /*                                  leetspeak                                 */
    /* -------------------------------------------------------------------------- */
    if ($tool == "l33tsp34k") {
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

    /* ───────────────────────────────────────────────────────────────────── */
    /*                               UPPERCASE                               */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "uppercase") {
      $string = strtoupper($string);
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                               lowercase                               */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "lowercase") {
      $string = strtolower($string);
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                                slugify                                */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "slugify") {
      $string = str_replace([" ", "-"], "_", $string);
      $string = preg_replace('/[^a-zA-Z0-9_]/', '', $string);
      $string = strtolower($string);
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                                kebabcase                              */ 
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "kebabcase") {
      $string = str_replace([" ", "_"], "-", $string);
      $string = preg_replace('/[^a-zA-Z0-9_-]/', '', $string);
      $string = strtolower($string);
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                               l337s934k                               */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "l33t5p34k") {
      $search = [
        'a', 'e', 'o', 't', 'l', 's', 'b',
        'A', 'E', 'O', 'T', 'L', 'S', 'B',
      ];
      $replace = [
        '4', '3', '0', '7', '1', '5', '6',
        '4', '3', '0', '7', '1', '5', '6',
      ];
      $string = str_replace($search, $replace, $string);
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                              Titlecase                                */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "titlecase") {
      $string = ucwords($string);
    }

    /* -------------------------------------------------------------------------- */
    /*                                 rANdOmcAse                                 */
    /* -------------------------------------------------------------------------- */
    if ($tool == "randomcase") {
      foreach (str_split($string) as $i => $char) {
        $roll = mt_rand(0,100);
        $string[$i] = strtolower($char);
        if ($roll >= 50) {
          $string[$i] = strtoupper($char);
        }
      }
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                                ctlf2lf                                */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "crlf2lf") {
      $string = str_replace(["\r", "\n"], "", $string);
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                                formatlineendings                      */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "formatlineendings") {
      $string = str_replace(["\\r", "\\n"], "\n", $string);
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                                lf2crlf                                */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "lf2crlf") {
      $string = str_replace(["\r", "\n"], "\r\n", $string);
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                                removebackslashes                      */
    /* ───────────────────────────────────────────────────────────────────── */
    if ($tool == "removebackslashes") {
      $string = str_replace(["\\"], "", $string);
    }


    if ($outputToTextbox) {
      echo $string;
      break;
    }

    echo formatOutput(nl2br($string));
  }

  if ($responsetype != "html") {
    break;
  }

  echo $debug;
} while (False);
?>