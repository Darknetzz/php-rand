<?php
header('Content-Type: text/html; charset=utf-8');

require_once("includes/_includes.php");

do {

/* ===================================================================== */
/*                            NOTE: Debug info                           */
/* ===================================================================== */
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
    break;
  }

  # Return type (html / text)
  $responsetype = "html";
  if (!empty($_POST['responsetype'])) {
    $responsetype = $_POST['responsetype'];
  }

  $action = $_POST['action'];
  $tool   = ($_POST['tool'] ?? Null);
  $input  = ($_POST['input'] ?? Null);


/* ===================================================================== */
/*                             NOTE: datetime                            */
/* ===================================================================== */
  if ($action == "datetime") {
    $timefrom_unit = $_POST['timefrom_unit'];
    $timeto_unit   = $_POST['timeto_unit'];
    $time          = $_POST['time'];

    if (empty($time) || empty($timefrom_unit) || empty($timeto_unit)) {
      echo formatOutput("You must enter a value and select units.", type: "danger");
      break;
    }

    $time = intval($time);

    $units    = [
      "s" => ["seconds", 1],
      "i" => ["minutes", 60],
      "h" => ["hours", 3600],
      "d" => ["days", 86400],
      "w" => ["weeks", 604800],
      "M" => ["months", 2628000],
      "y" => ["years", 31536000]
    ];

    $timefrom = $units[$timefrom_unit][1];
    $timeto   = $units[$timeto_unit][1];

    $from_unit_name = $units[$timefrom_unit][0];
    $to_unit_name   = $units[$timeto_unit][0];
    $converted      = round(($time * $timefrom) / $timeto, 6);
    
    echo "<div style='margin-bottom: 15px;'>" . copyableOutput($converted . " " . $to_unit_name, "$time $from_unit_name") . "</div>";
  }


/* ===================================================================== */
/*                              MODULE: genstr                           */
/* ===================================================================== */
  if ($action == "stringgen") {
    if (empty($_POST['digits'])) {
      die(alert("You must enter a string length.", "danger"));
    }
    if (!ctype_digit($_POST['digits']) || $_POST['digits'] > 1000000 || $_POST['digits'] < 1) {
      die(alert("Invalid number of characters.", "danger"));
    }
    $strings         = (!empty($_POST['strings']) ? $_POST['strings'] : 1);
    $randomString    = [];
    $collapsibleText = "";

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
    for ($i = 0; $i < $strings; $i++) {
      $thisRandomString = $randomString[] = genStr($charsets, $length, $_POST['cchars']);
      $collapsibleText .= "

            <table class='table table-default'>

              <tr>
                <td>String</td>
                <td><pre>$thisRandomString</pre></td>
              </tr>

              <tr>
                <td>MD5</td>
                <td><pre>".hash('md5', $thisRandomString)."</pre></td>
              </tr>
              <tr>
                <td>SHA1</td>
                <td><pre>".hash('sha1', $thisRandomString)."</pre></td>
              </tr>
              <tr>
                <td>SHA256</td>
                <td><pre>".hash('sha256', $thisRandomString)."</pre></td>
              </tr>
              <tr>
                <td>SHA512</td>
                <td><pre>".hash('sha512', $thisRandomString)."</pre></td>
              </tr>

              <tr>
                <td>Possible combinations</td>
                <td><pre>".number_format(strlen($charsets)**$length)." (".strlen($charsets)."^$length)</pre></td>
              </tr>

            </table>";
    }

    $charactersLength = strlen($charsets);

    echo "<hr>";
    foreach ($randomString as $string) {
      echo "<div style='margin-bottom: 15px;'>" . copyableOutput($string) . "</div>";
    }
    // echo formatOutput($randomString);
    echo "

    <button class='btn btn-info' type='button' data-bs-toggle='collapse' data-bs-target='#additionalInfo' aria-expanded='false' aria-controls='additionalInfo'>".icon('info-circle')."</button>

    <div id='additionalInfo' class='collapse' style='margin:15px;'>
      <div class='card border-info'>
        <h4 class='card-header text-bg-info'>".icon('info-circle')." Info</h4>
        <div class='card-body'>
            $collapsibleText
        </div>
      </div>
    </div>
    ";
  }
  /* ───────────────────────────────────────────────────────────────────── */


/* ===================================================================== */
/*                          NOTE: Repeat string                          */
/* ===================================================================== */
  if ($action == 'repeatstr') {
    echo str_repeat($_POST['repeatstr'], $_POST['repeatamt']);
  }

/* ===================================================================== */
/*                               MODULE: Base                            */
/* ===================================================================== */
  if ($action == 'base64encode' || $action == 'base64decode' || $action == 'base') {
    // die(formatOutput($_POST));
    $input = (!empty($_POST['base']) ? $_POST['base'] : Null);
    $from  = (!empty($_POST['from']) ? ($_POST['from']) : "text");
    $to    = (!empty($_POST['to']) ? ($_POST['to']) : 64);

    $result = convert_any($input, $from, $to);
    
    echo "<div style='margin-bottom: 20px;'>";
    echo "<div style='margin-bottom: 15px;'><strong>Base $from → Base $to</strong></div>";
    echo copyableOutput($result);
    echo "</div>";
  }


/* ===================================================================== */
/*                               MODULE: Hash                            */
/* ===================================================================== */
  if (isset($_POST['hash'])) {
    $hashalgo = (!empty($_POST['hashalgo']) ? $_POST['hashalgo'] : Null);
    $types = hash_algos();
    if (!empty($hashalgo) && in_array($hashalgo, hash_algos())) {
      $types = [$hashalgo];
    }
    $output = "";
    foreach ($types as $type) {
      $hashValue = hash($type, $_POST['hash']);
      $output .= "<div style='margin-bottom: 20px;'>" . copyableOutput($hashValue, $type) . "</div>";
    }
    echo formatOutput($output);
  }

/* ===================================================================== */
/*                              MODULE: binhex                           */
/* ===================================================================== */
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


    echo "<div style='margin-bottom: 15px;'>" . copyableOutput($output) . "</div>";
  }


/* ===================================================================== */
/*                              MODULE: numgen                           */
/* ===================================================================== */
  if (isset($_POST['numgenfrom']) && isset($_POST['numgento'])) {
      $numgenfrom = $_POST['numgenfrom'];
      $numgento   = $_POST['numgento'];
      $enableSeed = (isset($_POST['seed']) ? True : False);
      $seed       = Null;
      if ($enableSeed !== False) {
        $seed = $_POST['numgenseed'];
      }
      $gen = numGen($numgenfrom, $numgento, $seed);
      echo "<div style='margin-bottom: 15px;'>" . copyableOutput($gen) . "</div>";
      if ($seed) {
        echo "<div style='margin-top: 15px; opacity: 0.7;'><small><strong>Seed used:</strong> $seed</small></div>";
      }
  }


  /* ===================================================================== */
  /*                           MODULE: Calculator                          */
  /* ===================================================================== */
  if ($action == 'calc' && !empty($_POST['calcinput'])) {
    $calcinput = $_POST['calcinput'];
    if (empty($calcinput)) {
      echo formatOutput("You must enter a calculation.", type: "danger");
      break;
    }
    $result = calc($calcinput);
    if ($result === False) {
      echo formatOutput("Invalid calculation.", type: "danger");
    } else {
      echo formatOutput("Result: <b>$result</b>");
    }
  }

/* ===================================================================== */
/*                               MODULE: ROT                             */
/* ===================================================================== */
  if (isset($_POST['rot'])) {
    if ($_POST['bruteforce'] == 1) {
      $alphabet = 26;
      $output = "";
      for ($i = 0; $i < $alphabet; $i++) {
          $rotated = str_rot($_POST['rot'], $i);
          $output .= "<div style='margin-bottom: 15px;'>" . copyableOutput($rotated, "ROT" . $i) . "</div>";
      }
      echo $output;
    }
    elseif (!empty($_POST['rotations'])) {
      $rotations = $_POST['rotations'];
      $strrot = str_rot($_POST['rot'], $rotations);
      echo "<div style='margin-bottom: 15px;'>" . copyableOutput($strrot) . "</div>";
    } else {
      $rotations = 13;
      $strrot = str_rot($_POST['rot'], $rotations);
      echo "<div style='margin-bottom: 15px;'>" . copyableOutput($strrot) . "</div>";
    }
  }

/* ===================================================================== */
/*                             MODULE: Shuffler                          */
/* ===================================================================== */
  if (isset($_POST['shuffler'])) {
    echo "<b>Your string would be: </b>".str_shuffle(utf8_encode($_POST['shuffler']));
  }

/* ===================================================================== */
/*                             MODULE: OpenSSL                           */
/* ===================================================================== */
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
        echo "<div style='margin-bottom: 20px;'>";
        echo copyableOutput($string);
        echo "</div>";
        echo "<div style='margin-top: 20px; padding: 15px; background-color: rgba(255, 193, 7, 0.1); border-radius: 0.5rem;'>";
        echo "<strong>Encryption Details:</strong><br>";
        echo "🔑 <strong>Cipher:</strong> <code>$cipher</code><br>";
        echo "🔓 <strong>Key:</strong> <code>" . htmlspecialchars($key) . "</code><br>";
        echo "📍 <strong>IV (Hex):</strong> <code>$iv</code>";
        echo "</div>";
  }

/* ===================================================================== */
/*                          MODULE: Spin the wheel                         */
/* ===================================================================== */
  if ($action == "spinwheel") {

    $wheelitem = (!empty($_POST['wheelitem']) ? $_POST['wheelitem'] : []);
    $spinsamt = (!empty($_POST['spinsamt'])   ? intval($_POST['spinsamt']) : 1);
    $unique    = (!empty($_POST['unique'])    ? intval($_POST['unique'])    : 0);

    $spin = spinWheel($wheelitem, $spinsamt, $unique);

    echo $spin;
  }


  /* ───────────────────────────────────────────────────────────────────── */


/* ===================================================================== */
/*                          MODULE: Serialization                        */
/* ===================================================================== */
  if ($action == "serialization") {
    $type          = $_POST['type'] ?? 'JSON';
    $input         = $_POST['input'] ?? '';
    $stripcomments = $_POST['stripcomments'] ?? 0;
    if (empty($type) || empty($input)) {
      echo formatOutput("You must select a type and enter data.", type: "danger");
      break;
    }

    if (!empty($stripcomments) && $stripcomments == 1) {
      $input = preg_replace('/^.*#.*\n/', '', $input);
      $input = preg_replace('/^.*\/\/.*\n/', '', $input);
    }

    $xmlparser = xml_parser_create();
    $detected  = null;

    # Detect input
    if (json_validate($input)) {
      $input    = json_decode($input, True);
      $detected = 'JSON';
    }
    // elseif (yaml_parse($input)) {
    //   $input    = yaml_parse($input);
    //   $detected = 'YAML';
    // }
    elseif (xml_parse($xmlparser, $input)) {
      // NOTE: xml_parse returns bool; full XML conversion not implemented in this build
      $input    = $input;
      $detected = 'XML';
    } else {
      echo formatOutput("Invalid input. It must valid JSON, XML or YAML.", type: "danger");
      break;
    }

    # Convert to desired type
    $output = "";
    if ($type == "JSON") {
      $output = is_string($input) ? $input : json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    } elseif ($type == "XML") {
      $output = "[XML output is not available in this build]";
    } elseif ($type == "YAML") {
      $output = "[YAML output is not available in this build]";
    }

    echo copyableOutput($output, "Detected: " . ($detected ?? 'Unknown'));
  }

/* ===================================================================== */
/*                         MODULE: String tools                          */
/* ===================================================================== */
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


/* ===================================================================== */
/*                               NOTE: trim                              */
/* ===================================================================== */
    if ($tool == "trim") {
      $string = trim($string);
    }

/* ===================================================================== */
/*                         NOTE: removewhitespace                        */
/* ===================================================================== */
    if ($tool == "removewhitespace") {
      $string = preg_replace('/\s+/', '', $string);
    }


/* ===================================================================== */
/*                             NOTE: reverse                             */
/* ===================================================================== */
    if ($tool == "reverse") {
      $string = strrev($string);
    }

/* ===================================================================== */
/*                              NOTE: repeat                             */
/* ===================================================================== */
    if ($tool == "repeat") {
      $string = str_repeat($string, 2);
    }

/* ===================================================================== */
/*                             NOTE: shuffle                             */
/* ===================================================================== */
    if ($tool == "shuffle") {
      $string = str_shuffle($string);
    }

/* ===================================================================== */
/*                            NOTE: leetspeak                            */
/* ===================================================================== */
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
/* ===================================================================== */
/*                            NOTE: UPPERCASE                            */
/* ===================================================================== */
    if ($tool == "uppercase") {
      $string = strtoupper($string);
    }
/* ===================================================================== */
/*                            NOTE: lowercase                            */
/* ===================================================================== */
    if ($tool == "lowercase") {
      $string = strtolower($string);
    }
/* ===================================================================== */
/*                             NOTE: slugify                             */
/* ===================================================================== */
    if ($tool == "slugify") {
      $string = str_replace([" ", "-"], "_", $string);
      $string = preg_replace('/[^a-zA-Z0-9_]/', '', $string);
      $string = strtolower($string);
    }
/* ===================================================================== */
/*                            NOTE: kebabcase                            */
/* ===================================================================== */
    if ($tool == "kebabcase") {
      $string = str_replace([" ", "_"], "-", $string);
      $string = preg_replace('/[^a-zA-Z0-9_-]/', '', $string);
      $string = strtolower($string);
    }
/* ===================================================================== */
/*                            NOTE: l337s934k                            */
/* ===================================================================== */
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

/* ===================================================================== */
/*                            NOTE: Titlecase                            */
/* ===================================================================== */
    if ($tool == "titlecase") {
      $string = ucwords($string);
    }

/* ===================================================================== */
/*                            NOTE: rANdOmcAse                           */
/* ===================================================================== */
    if ($tool == "randomcase") {
      foreach (str_split($string) as $i => $char) {
        $roll = mt_rand(0,100);
        $string[$i] = strtolower($char);
        if ($roll >= 50) {
          $string[$i] = strtoupper($char);
        }
      }
    }

/* ===================================================================== */
/*                             NOTE: ctlf2lf                             */
/* ===================================================================== */
    if ($tool == "crlf2lf") {
      $string = str_replace(["\r", "\n"], "", $string);
    }

/* ===================================================================== */
/*                        NOTE: formatlineendings                        */
/* ===================================================================== */
    if ($tool == "formatlineendings") {
      $string = str_replace(["\\r", "\\n"], "\n", $string);
    }

/* ===================================================================== */
/*                             NOTE: lf2crlf                             */
/* ===================================================================== */
    if ($tool == "lf2crlf") {
      $string = str_replace(["\r", "\n"], "\r\n", $string);
    }

/* ===================================================================== */
/*                        NOTE: removebackslashes                        */
/* ===================================================================== */
    if ($tool == "removebackslashes") {
      $string = str_replace(["\\"], "", $string);
    }

/* ===================================================================== */
/*                        NOTE: invertedcase                             */
/* ===================================================================== */
    if ($tool == "invertedcase") {
      $string = strtr($string, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
    }

/* ===================================================================== */
/*                        NOTE: camelcase                                */
/* ===================================================================== */
    if ($tool == "camelcase") {
      $string = lcfirst(str_replace(' ', '', ucwords($string)));
    }

/* ===================================================================== */
/*                        NOTE: removehtmltags                           */
/* ===================================================================== */
    if ($tool == "removehtmltags") {
      $string = strip_tags($string);
    }

/* ===================================================================== */
/*                        NOTE: removepunctuation                        */
/* ===================================================================== */
    if ($tool == "removepunctuation") {
      $string = preg_replace('/[^\w\s]/', '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removenewlines                           */
/* ===================================================================== */
    if ($tool == "removenewlines") {
      $string = str_replace(["\r\n", "\r", "\n"], '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removetabs                               */
/* ===================================================================== */
    if ($tool == "removetabs") {
      $string = str_replace("\t", '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removespaces                             */
/* ===================================================================== */
    if ($tool == "removespaces") {
      $string = str_replace(" ", '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removeslashes                            */
/* ===================================================================== */
    if ($tool == "removeslashes") {
      $string = str_replace(["/", "\\"], '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removenonascii                           */
/* ===================================================================== */
    if ($tool == "removenonascii") {
      $string = preg_replace('/[^\x00-\x7F]/', '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removenonprintable                       */
/* ===================================================================== */
    if ($tool == "removenonprintable") {
      $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removewhitespaceext                      */
/* ===================================================================== */
    if ($tool == "removewhitespaceext") {
      $string = preg_replace('/\s+/', '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removenumbers                            */
/* ===================================================================== */
    if ($tool == "removenumbers") {
      $string = preg_replace('/\d/', '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removeletters                            */
/* ===================================================================== */
    if ($tool == "removeletters") {
      $string = preg_replace('/[a-zA-Z]/', '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removesymbols                            */
/* ===================================================================== */
    if ($tool == "removesymbols") {
      $string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removeextendedsymbols                    */
/* ===================================================================== */
    if ($tool == "removeextendedsymbols") {
      $string = preg_replace('/[^a-zA-Z0-9\s\-_.]/', '', $string);
    }

/* ===================================================================== */
/*                        NOTE: removecustomcharacters                   */
/* ===================================================================== */
    if ($tool == "removecustomcharacters") {
      $custom = (!empty($_POST['customchars']) ? $_POST['customchars'] : '');
      if (!empty($custom)) {
        $string = str_replace(str_split($custom), '', $string);
      }
    }

/* ===================================================================== */
/*                        NOTE: regex                                    */
/* ===================================================================== */
    if ($tool == "regex") {
      $pattern = (!empty($_POST['pattern']) ? $_POST['pattern'] : '');
      $replacement = (!empty($_POST['replacement']) ? $_POST['replacement'] : '');
      if (!empty($pattern)) {
        $string = preg_replace($pattern, $replacement, $string);
      }
    }


    if ($outputToTextbox) {
      echo $string;
      break;
    }

    echo formatOutput(nl2br($string));
  }
/* ===================================================================== */
/*                             MODULE: IP tools                          */
/* ===================================================================== */
  if ($action == "ip") {

# =========================================================================== //
#                                NOTE: dnslookup                              //
# =========================================================================== //
if ($tool == "dnslookup") {
  $hostname = (!empty($_POST['hostname']) ? $_POST['hostname'] : Null);
  if ($hostname !== null) {
    if (is_ip($hostname)) {
      $result = gethostbyaddr($hostname);
    } elseif (is_hostname($hostname)) {
      $result = gethostbyname($hostname);
    } else {
      echo formatOutput("Invalid hostname/IP", type: "danger");
      break;
    }
    echo "<div style='margin-bottom: 15px;'>" . copyableOutput($result, $hostname) . "</div>";
  }
}

/* ===================================================================== */
/*                            NOTE: cidr2range                           */
/* ===================================================================== */
    if ($tool == "cidr2range") {
        $cidr = (!empty($_POST['cidr']) ? $_POST['cidr'] : Null);
        if (empty($cidr)) {
          echo formatOutput("You must enter a CIDR range.", type: "danger");
          break;
        }

        $range = cidr2range($cidr);
        if (!$range) {
          echo formatOutput("Invalid CIDR range.", type: "danger");
          break;
        }
        if (is_array($range['cidr'])) {
          $range['cidr'] = implode("<br>", $range['cidr']);
        }
        $output = "
          <div style='display: grid; grid-template-columns: 1fr 1fr; gap: 12px;'>
            <div><strong>CIDR:</strong><br><code style='font-size: 0.95rem;'>" . $range['cidr'] . "</code></div>
            <div><strong>Start IP:</strong><br><code style='font-size: 0.95rem;'>" . $range['start'] . "</code></div>
            <div><strong>End IP:</strong><br><code style='font-size: 0.95rem;'>" . $range['end'] . "</code></div>
            <div><strong>Total IPs:</strong><br><code style='font-size: 0.95rem;'>" . $range['total'] . "</code></div>
          </div>
        ";
        echo "<div style='margin-bottom: 15px;'>" . copyableOutput($output, "CIDR Range Info") . "</div>";
    }
/* ===================================================================== */
/*                            NOTE: range2cidr                           */
/* ===================================================================== */
    if ($tool == "range2cidr") {
      $startip = (!empty($_POST['startip']) ? $_POST['startip'] : Null);
      $endip   = (!empty($_POST['endip'])   ? $_POST['endip']   : Null);

      if (empty($startip) || empty($endip)) {
        echo formatOutput("You must enter a start and end IP.", type: "danger");
        break;
      }

      $cidr = range2cidr($startip, $endip);
      if (!$cidr) {
        echo formatOutput("Invalid IP range.", type: "danger");
        break;
      }
      if (is_array($cidr["cidrs"])) {
        $cidr["cidrs"] = implode("<br>", $cidr["cidrs"]);
      }
      $output = "
        <div style='display: grid; grid-template-columns: 1fr 1fr; gap: 12px;'>
          <div><strong>CIDR Range(s):</strong><br><code style='font-size: 0.95rem;'>" . $cidr["cidrs"] . "</code></div>
          <div><strong>Start:</strong><br><code style='font-size: 0.95rem;'>" . $cidr["start"] . "</code></div>
          <div><strong>End:</strong><br><code style='font-size: 0.95rem;'>" . $cidr["end"] . "</code></div>
          <div><strong>Total IPs:</strong><br><code style='font-size: 0.95rem;'>" . $cidr["total_ips"] . "</code></div>
        </div>
      ";
      echo "<div style='margin-bottom: 15px;'>" . copyableOutput($output, "IP Range to CIDR") . "</div>";
    }
/* ===================================================================== */
/*                            NOTE: subnetmask                           */
/* ===================================================================== */
    if ($tool == "subnetmask") {
      $ip     = (!empty($_POST['ip'])     ? $_POST['ip']     : Null);
      $subnet = (!empty($_POST['subnet']) ? $_POST['subnet'] : Null);

      if (empty($ip) || empty($subnet)) {
        echo formatOutput("You must enter an IP and subnet mask.", type: "danger");
        break;
      }

      $subnetmask = subnetmask($ip, $subnet);
      if (!$subnetmask) {
        echo formatOutput("Invalid IP or subnet mask.", type: "danger");
        break;
      }

      if (is_array($subnetmask["cidrs"])) {
        $subnetmask["cidrs"] = implode("<br>", $subnetmask["cidrs"]);
      }

      $output = "
        <div style='display: grid; grid-template-columns: 1fr 1fr; gap: 12px;'>
          <div><strong>Network:</strong><br><code style='font-size: 0.95rem;'>" . $subnetmask["network"] . "</code></div>
          <div><strong>First IP:</strong><br><code style='font-size: 0.95rem;'>" . $subnetmask["start"] . "</code></div>
          <div><strong>Last IP:</strong><br><code style='font-size: 0.95rem;'>" . $subnetmask["end"] . "</code></div>
          <div><strong>Broadcast:</strong><br><code style='font-size: 0.95rem;'>" . $subnetmask["broadcast"] . "</code></div>
          <div><strong>Subnet Mask:</strong><br><code style='font-size: 0.95rem;'>" . $subnetmask["subnet"] . "</code></div>
          <div><strong>CIDR:</strong><br><code style='font-size: 0.95rem;'>" . $subnetmask["cidr"] . "</code></div>
          <div><strong>Usable IPs:</strong><br><code style='font-size: 0.95rem;'>" . $subnetmask["usable_ips"] . "</code></div>
        </div>
      ";
      echo "<div style='margin-bottom: 15px;'>" . copyableOutput($output, "Subnet Information") . "</div>";
    }

  }

/* ===================================================================== */
/*                             MODULE: URL tools                          */
/* ===================================================================== */
  if ($action == "urlencode") {
    $url = (!empty($_POST['urlencode']) ? $_POST['urlencode'] : Null);

    if (empty($url)) {
      echo formatOutput("You must enter a URL.", type: "danger");
      break;
    }

    $encoded = urlencode($url);
    $decoded = urldecode($url);

    $output  = "<div style='margin-bottom: 16px;'>" . copyableOutput($url, "Original Input") . "</div>";
    $output .= "<div style='margin-bottom: 16px;'>" . copyableOutput($encoded, "URL Encoded") . "</div>";
    $output .= "<div style='margin-bottom: 16px;'>" . copyableOutput($decoded, "URL Decoded") . "</div>";

    echo $output;
  }

/* ===================================================================== */
/*                         MODULE: HTML Entities                         */
/* ===================================================================== */
if ($action == "htmlentities") {
    $input = (!empty($_POST['htmlentities']) ? $_POST['htmlentities'] : Null);

    if (empty($input)) {
      echo formatOutput("You must enter a string.", type: "danger");
      break;
    }

    $output  = "<div style='margin-bottom: 16px;'>" . copyableOutput($input, "Original Input") . "</div>";
    $output .= "<div style='margin-bottom: 16px;'>" . copyableOutput(htmlentities($input), "HTML Entities") . "</div>";
    $output .= "<div style='margin-bottom: 16px;'>" . copyableOutput(html_entity_decode($input), "HTML Decoded") . "</div>";

    echo $output;
  }



  # ─────────────────────────────────────────────────────────────────────────── //
  #                                MODULE: minify                               //
  # ─────────────────────────────────────────────────────────────────────────── //
  if ($tool == "minify") {
    $type  = (!empty($_POST['type']) ? $_POST['type'] : Null);
    $input = (!empty($_POST['input']) ? $_POST['input'] : Null);

    if (empty($type) || empty($input)) {
      echo formatOutput("You must select a tool and enter data.", type: "danger");
      break;
    }

    $output = "";

    if ($type == "css") {
      if (!class_exists(\MatthiasMullie\Minify\CSS::class)) {
        echo formatOutput("CSS minifier class not available (install matthiasmullie/minify).", type: "danger");
        break;
      }
      $minifier = new Minify\CSS($input);
      $output = $minifier->minify();
    } elseif ($type == "js") {
      if (!class_exists(\MatthiasMullie\Minify\JS::class)) {
        echo formatOutput("JS minifier class not available (install matthiasmullie/minify).", type: "danger");
        break;
      }
      $minifier = new Minify\JS($input);
      $output = $minifier->minify();
    } elseif ($type == "html") {
      // Fallback simple HTML minifier (MatthiasMullie library does not provide HTML)
      $output = $input;
      // Remove HTML comments (except IE conditionals)
      $output = preg_replace('/<!--(?!\[if).*?-->/s', '', $output);
      // Collapse whitespace between tags
      $output = preg_replace('/>\s+</', '><', $output);
      // Collapse multiple spaces/newlines
      $output = preg_replace('/\s{2,}/', ' ', $output);
      $output = trim($output);
    } else {
      echo formatOutput("Invalid type selected '$type'.", type: "danger");
      break;
    }

    $originalSize = strlen($input);
    $minifiedSize = strlen($output);
    $savings = $originalSize > 0 ? round((($originalSize - $minifiedSize) / $originalSize) * 100, 2) : 0;

    echo "<div style='margin-bottom: 20px;'>" . copyableOutput($output, strtoupper($type) . " Minified") . "</div>";
    echo "<div style='margin-top: 15px; padding: 12px; background-color: rgba(255, 193, 7, 0.15); border-radius: 0.5rem;'>";
    echo "<strong>📊 Compression Stats:</strong><br>";
    echo "Original: <code>" . number_format($originalSize) . " bytes</code> | ";
    echo "Minified: <code>" . number_format($minifiedSize) . " bytes</code> | ";
    echo "Saved: <code>" . number_format($originalSize - $minifiedSize) . " bytes</code> (<strong>" . $savings . "%</strong>)";
    echo "</div>";
  }

  # =========================================================================== //
  #                               MODULE: metaphone                             //
  # =========================================================================== //
  if ($action == "metaphone") {
    $input = $_POST['metaphone'] ?? '';
    if (empty($input)) {
      echo formatOutput("You must enter a string.", type: "danger");
    } else {
      $output = metaphone($input);
      echo "<div style='margin-bottom: 15px;'>" . copyableOutput($output, "Metaphone Key") . "</div>";
    }
  }

  # =========================================================================== //
  #                               MODULE: levenshtein                           //
  # =========================================================================== //
  if ($action == "levenshtein") {

    $insertion_cost   = (!empty($_POST['insertion_cost']) ? $_POST['insertion_cost'] : 1);
    $replacement_cost = (!empty($_POST['replacement_cost']) ? $_POST['replacement_cost'] : 1);
    $deletion_cost    = (!empty($_POST['deletion_cost']) ? $_POST['deletion_cost'] : 1);

    $string1 = $_POST['levenshtein1'];
    $string2 = $_POST['levenshtein2'];
    
    $distance = levenshtein($string1, $string2, $insertion_cost, $replacement_cost, $deletion_cost);
    
    echo "
      <div style='margin-bottom: 15px;'>
        <div style='display: flex; align-items: center; justify-content: space-between; background: #0f172a; color: #e9ecef; padding: 15px 20px; border-radius: 0.5rem; border: 1px solid #334155; box-shadow: 0 6px 16px rgba(0,0,0,0.25);'>
          <div style='flex: 1;'>
            <div style='text-align: center; padding: 30px;'>
              <div style='font-size: 4rem; font-weight: bold; color: #ff5722; margin-bottom: 20px;'>$distance</div>
              <div style='font-size: 1.2rem; margin-bottom: 30px;'>
                <strong>Levenshtein Distance</strong>
              </div>
              <div style='display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; text-align: center; margin-top: 20px;'>
                <div>
                  <small style='opacity: 0.7;'><strong>Insertion Cost</strong></small><br>
                  <code style='font-size: 1.1rem;'>$insertion_cost</code>
                </div>
                <div>
                  <small style='opacity: 0.7;'><strong>Replacement Cost</strong></small><br>
                  <code style='font-size: 1.1rem;'>$replacement_cost</code>
                </div>
                <div>
                  <small style='opacity: 0.7;'><strong>Deletion Cost</strong></small><br>
                  <code style='font-size: 1.1rem;'>$deletion_cost</code>
                </div>
              </div>
            </div>
          </div>
          <button onclick='copyToClipboard(\"$distance\", this)' class='btn btn-outline-light' style='margin-left: 15px; border: 1px solid #e9ecef; white-space: nowrap;'>
            <i class='ti ti-copy'></i> Copy
          </button>
        </div>
      </div>
    ";
  }


  # =========================================================================== //
  #                                 MODULE: diff                                //
  # =========================================================================== //
  if ($action == "diff") {
    $diff1 = (!empty($_POST['diff1']) ? $_POST['diff1'] : Null);
    $diff2 = (!empty($_POST['diff2']) ? $_POST['diff2'] : Null);
    if (!function_exists("xdiff_string_diff")) {
      echo formatOutput("Function xdiff_string_diff must be available.");
      break;
    }
    $diff  = xdiff_string_diff($diff1, $diff2);
    echo formatOutput($diff);
  }

  # ─────────────────────────────────────────────────────────────────────────── //
  #                             MODULE: currency                               //
  # ─────────────────────────────────────────────────────────────────────────── //
  if ($action == "currency") {
    $currency_amount = (!empty($_POST['currency_amount']) ? floatval($_POST['currency_amount']) : Null);
    $currency_from   = (!empty($_POST['currency_from']) ? strtoupper($_POST['currency_from']) : Null);
    $currency_to     = (!empty($_POST['currency_to']) ? strtoupper($_POST['currency_to']) : Null);
    $custom_rate     = (!empty($_POST['currency_rate']) ? floatval($_POST['currency_rate']) : Null);

    if (empty($currency_amount) || empty($currency_from) || empty($currency_to)) {
      echo formatOutput("You must enter an amount and select both source and target currencies.", type: "danger");
      break;
    }

    if ($currency_amount < 0) {
      echo formatOutput("Amount must be a positive number.", type: "danger");
      break;
    }

    // Handle same currency conversion
    if ($currency_from == $currency_to) {
      $result = $currency_amount;
      $output = "<b>$currency_amount $currency_from = <span class='text-success'>$result $currency_to</span></b>";
      echo formatOutput($output);
      break;
    }

    // If custom rate provided, use it
    if ($custom_rate !== null && $custom_rate > 0) {
      $result = $currency_amount * $custom_rate;
      $output = "<div class='conversion-result'>";
      $output .= "<div style='font-size: 1.5em; margin: 15px 0;'>";
      $output .= "<b>" . number_format($currency_amount, 2) . " $currency_from</b> = <span class='text-success'><b>" . number_format($result, 2) . " $currency_to</b></span>";
      $output .= "</div>";
      $output .= "<hr>";
      $output .= "<div><small class='text-muted'>";
      $output .= "Custom Rate Used: 1 $currency_from = " . number_format($custom_rate, 4) . " $currency_to";
      $output .= "</small></div>";
      $output .= "</div>";
      echo formatOutput($output);
      break;
    }

    // Fetch live exchange rate from API
    $rate = null;
    $api_url = "https://api.exchangerate-api.com/v4/latest/" . urlencode($currency_from);
    
    // Fetch the exchange rates
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $response = @file_get_contents($api_url, false, $context);
    
    if ($response === false) {
      echo formatOutput("Unable to fetch exchange rates. Please check your internet connection or try again later.", type: "danger");
      break;
    }

    $data = json_decode($response, true);

    if (!$data || json_last_error() !== JSON_ERROR_NONE || !isset($data['rates'])) {
      echo formatOutput("Invalid response from exchange rate API. Please try again.", type: "danger");
      break;
    }

    if (!isset($data['rates'][$currency_to])) {
      echo formatOutput("Currency $currency_to not found in API database. Please select a valid currency.", type: "danger");
      break;
    }

    $rate = $data['rates'][$currency_to];
    $result = $currency_amount * $rate;
    $timestamp = $data['time_last_updated'] ?? '';

    $output = "<div class='conversion-result'>";
    $output .= "<h4 class='text-success'>Conversion Result</h4>";
    $output .= "<div style='font-size: 1.5em; margin: 15px 0;'>";
    $output .= "<b>" . number_format($currency_amount, 2) . " $currency_from</b> = <span class='text-success'><b>" . number_format($result, 2) . " $currency_to</b></span>";
    $output .= "</div>";
    $output .= "<hr>";
    $output .= "<div><small class='text-muted'>";
    $output .= "Live Exchange Rate: 1 $currency_from = " . number_format($rate, 4) . " $currency_to<br>";
    if ($timestamp) {
      $output .= "Last Updated: " . htmlspecialchars($timestamp) . "<br>";
    }
    $output .= "<em>Rates provided by exchangerate-api.com</em>";
    $output .= "</small></div>";
    $output .= "</div>";

    echo formatOutput($output);
  }

  # ─────────────────────────────────────────────────────────────────────────── //


  if ($responsetype != "html") {
    break;
  }

  echo $debug;
} while (False);
?>