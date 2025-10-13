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
    die("No action specified.");
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
    $converted      = ($time * $timefrom) / $timeto;
    $converted      = "$time $from_unit_name is equal to <b>$converted $to_unit_name</b>";
    echo formatOutput($converted);
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
      echo formatOutput($string);
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

    $allBasesAreBelongToUs = "";

    $allBasesAreBelongToUs .= "<b>Input (Base $from):</b> <code>$_POST[base]</code><br><br>";
    // $allBasesAreBelongToUs .= "Base64 encode: <code>".base64_encode($_POST['base'])."</code><br>";
    // $allBasesAreBelongToUs .= "Base64 decode: <code>".base64_decode($_POST['base'])."</code><br>";
    $allBasesAreBelongToUs .= "
      <hr>
      <b>Base $from to Base $to:</b><br>
      <pre><code>".convert_any($input, $from, $to).PHP_EOL."</code></pre>
      ";
    // if (!empty($to) && is_numeric($to) && $to >= 1 && $to <= 36) {
    //   $allBasesAreBelongToUs .= "<b>Base $from to Base $to:</b><br>
    //   <pre><code>".convert_any($_POST['base'], $from, $to).PHP_EOL."</code></pre>
    //   <br>";
    // } else {
    //   for ($i = 2; $i <= 36; $i++) {
    //     $allBasesAreBelongToUs .= "<b>Base$i:</b><br>
    //     <pre><code>".convert_any($_POST['base'], $from, $i).PHP_EOL."</code></pre>
    //     <br>";
    //   }
    // }

    echo formatOutput($allBasesAreBelongToUs);
  }


/* ===================================================================== */
/*                               MODULE: Hash                            */
/* ===================================================================== */
  if (isset($_POST['hash'])) {
    // $types  = ["SHA512", "SHA256", "SHA1", "MD5"];
    $hashalgo = (!empty($_POST['hashalgo']) ? $_POST['hashalgo'] : Null);
    $types = hash_algos();
    if (!empty($hashalgo) && in_array($hashalgo, hash_algos())) {
      $types = [$hashalgo];
    }
    $output = "<table class='table border border-success'>";
    foreach ($types as $type) {
      $output .= "<tr><td><b>$type:</b></td> <td class='text-break'>".hash($type, $_POST['hash'])."</td></tr>";
    }
    $output .= "</table>";
    echo formatOutput("Input: $_POST[hash]<hr>".$output);
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


    echo formatOutput($output, type: $type);
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
      echo formatOutput(
        "
        $gen
        <hr>
        Seed: $seed"
      );
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
        echo formatOutput("
          <b>".$string."</b>
          <hr>
          <b>Encryption key:</b> $key<br>
          <b>Initialization vector (Hex representation):</b> ".$iv);
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
    $type          = $_POST['type'];
    $input         = $_POST['input'];
    $stripcomments = $_POST['stripcomments'];
    if (empty($type) || empty($input)) {
      echo formatOutput("You must select a type and enter data.", type: "danger");
      break;
    }

    if (!empty($stripcomments) && $stripcomments == 1) {
      $input = preg_replace('/^.*#.*\n/', '', $input);
      $input = preg_replace('/^.*\/\/.*\n/', '', $input);
    }

    $xmlparser = xml_parser_create();
    # Detect input
    if (json_validate($input)) {
      $input = json_decode($input, True);
    }
    # REVIEW: yaml_parse is undefined.
    // elseif (yaml_parse($input)) {
    //   $input = yaml_parse($input);
    // }
    elseif (xml_parse($xmlparser, $input)) {
      $input = xml_parse($xmlparser, $input);
    } else {
      echo formatOutput("Invalid input. It must valid JSON, XML or YAML.", type: "danger");
      break;
    }

    # Convert to desired type
    if ($type == "JSON") {
      $output = json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
    # REVIEW: array2xml is undefined.
    // elseif ($type == "XML") {
    //   $output = array2xml($input);
    // }
    # REVIEW: yaml_emit is undefined.
    // elseif ($type == "YAML") {
    //   $output = yaml_emit($input);
    // }
    echo formatOutput($output, responsetype: "text");
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
        echo formatOutput("
          <table class='table table-bordered'>
            <tr class='table table-primary'>
              <th>Property</th>
              <th>Value</th>
            </tr>
            <tr>
              <td>CIDR range</td>
              <td>". $range['cidr'] ."</td>
            </tr>
            <tr>
              <td>Start IP</td>
              <td>". $range['start'] ."</td>
            </tr>
            <tr>
              <td>End IP</td>
              <td>". $range['end'] ."</td>
            </tr>
            <tr>
              <td>Total IPs</td>
              <td>". $range['total'] ."</td>
            </tr>
          </table>
        ");
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
      echo formatOutput("
        <table class='table table-bordered'>
          <tr class='table table-primary'>
            <th>Property</th>
            <th>Value</th>
          </tr>
          <tr>
            <td>CIDR range(s)</td>
            <td>". $cidr["cidrs"] ."</td>
          </tr>
          <tr>
            <td>Start</td>
            <td>". $cidr["start"] ."</td>
          </tr>
          <tr>
            <td>End</td>
            <td>". $cidr["end"] ."</td>
          </tr>
          <tr>
            <td>Total IPs</td>
            <td>". $cidr["total_ips"] ."</td>
          </tr>
        </table>
      ");
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

      echo formatOutput("
        <table class='table table-bordered'>
          <tr class='table table-primary'>
            <th>Property</th>
            <th>Value</th>
          </tr>
          <tr>
            <td>Network</td>
            <td>". $subnetmask["network"] ."</td>
          </tr>
          <tr>
            <td>First IP</td>
            <td>". $subnetmask["start"] ."</td>
          </tr>
          <tr>
            <td>Last IP</td>
            <td>". $subnetmask["end"] ."</td>
          </tr>
          <tr>
            <td>Broadcast</td>
            <td>". $subnetmask["broadcast"] ."</td>
          </tr>
          <tr>
            <td>Subnet mask</td>
            <td>". $subnetmask["subnet"] ."</td>
          </tr>
          <tr>
            <td>CIDR</td>
            <td>". $subnetmask["cidr"] ."</td>
          </tr>
          <tr>
            <td>Usable IPs</td>
            <td>". $subnetmask["usable_ips"] ."</td>
          </tr>
      ");
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

    $output = "<b>URL:</b> <code>".htmlspecialchars($url)."</code><br>";
    $output .= "<b>URL encoded:</b> <code>".urlencode($url)."</code><br>";
    $output .= "<b>URL decoded:</b> <code>".urldecode($url)."</code><br>";

    echo formatOutput($output);
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

    $output = "<b>Input:</b> <br>
      <pre><code>".htmlspecialchars($input)."</code></pre><br>";
    $output .= "<b>HTML entities:</b> <br>
      <pre><code>".htmlentities($input)."</code></pre><br>";
    $output .= "<b>HTML decoded:</b> <br>
      <pre><code>".html_entity_decode($input)."</code></pre><br>";

    echo formatOutput($output);
  }



  # ─────────────────────────────────────────────────────────────────────────── //
  #                                MODULE: minify                               //
  # ─────────────────────────────────────────────────────────────────────────── //
  if ($tool == "minify") {
    echo formatOutput($_POST);
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
      $output = new Minify\CSS($input);
    } elseif ($type == "js") {
      if (!class_exists(\MatthiasMullie\Minify\JS::class)) {
        echo formatOutput("JS minifier class not available (install matthiasmullie/minify).", type: "danger");
        break;
      }
      $output = new Minify\JS($input);
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

    echo formatOutput($output, responsetype: "text");
  }

  # =========================================================================== //
  #                               MODULE: metaphone                             //
  # =========================================================================== //
  if ($action == "metaphone") {
    $output = metaphone($_POST['metaphone']);
    echo formatOutput($output);
  }

  # =========================================================================== //
  #                               MODULE: levenshtein                           //
  # =========================================================================== //
  if ($action == "levenshtein") {

    $insertion_cost   = (!empty($_POST['insertion_cost']) ? $_POST['insertion_cost'] : 1);
    $replacement_cost = (!empty($_POST['replacement_cost']) ? $_POST['replacement_cost'] : 1);
    $deletion_cost    = (!empty($_POST['deletion_cost']) ? $_POST['deletion_cost'] : 1);

    $output = levenshtein($_POST['levenshtein1'], $_POST['levenshtein2'], $insertion_cost, $replacement_cost, $deletion_cost);
    if (empty($output)) {
      $output = "No difference";
    }
    echo formatOutput($output);
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


  if ($responsetype != "html") {
    break;
  }

  echo $debug;
} while (False);
?>