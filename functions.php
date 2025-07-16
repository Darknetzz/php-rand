<?php

/**
 * Icon
 * 
 */
function icon($icon, $rem = 1, $color = Null, $margin = 1) {
  $style = "style='";
  if ($color !== Null) {
    $style .= "color: {$color};";
  }
  if ($rem !== 1) {
    $style .= "font-size: {$rem}rem;";
  }
  $style .= "'";
  return "<i class='bi bi-{$icon} m-{$margin}' {$style}></i>";
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
  if ($page == "logogen") {
    $icon = "image";
  }
  if ($page == "encoding") {
    $icon = "file-earmark-binary-fill";
  }
  if ($page == "hash") {
    $icon = "key-fill";
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
  if ($page == "datetime") {
    $icon = "clock";
  }
  if ($page == "networking") {
    $icon = "globe";
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

/* ────────────────────────────────────────────────────────────────────────── */
/*                               cidr2range                                   */
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
/*                                 range2cidr                                 */
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
/*                                subnetmask                                 */
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


/* ===================================================================== */
/*                     NOTE: LOGOGEN FUNCTIONS                           */
/* ===================================================================== */

/* ===================================================================== */
/*                           FUNCTION: hex2rgb                           */
/* ===================================================================== */
function hex2rgb($hex) {

    try {
        if (strlen($hex) != 7 || $hex[0] != '#') {
            throw new Exception("Invalid hex color format");
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }

    $hex = str_replace('#', '', $hex);
    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2))
    ];
}

/* ===================================================================== */
/*                         FUNCTION: createImage                         */
/* ===================================================================== */
function createImage($height = 100, $width = 100, $background = '#000000', $angle = 0, $shape = "rectangle") {

    try {
        if (!is_numeric($height) || !is_numeric($width)) {
            throw new Exception("Height and width must be numeric");
        }
        if ($height <= 0 || $width <= 0) {
            throw new Exception("Height and width must be greater than zero");
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }

    // Create a blank image
    $image = imagecreatetruecolor($width, $height);

    // Enable alpha blending and save full alpha channel information
    imagealphablending($image, false);
    imagesavealpha($image, true);

    // Set background color
    $bg_rgb = hex2rgb($background);
    $bg     = imagecolorallocatealpha($image, $bg_rgb[0], $bg_rgb[1], $bg_rgb[2], 0);

    // Fill with transparent background first
    imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));

    if ($shape == "circle") {
        // Draw a filled circle
        $divisor = 2;
        $radius = min($width, $height) / $divisor;
        imagefilledellipse($image, $width / 2, $height / 2, $radius * 2, $radius * 2, $bg);
    } elseif ($shape == "rounded") {
        // Draw a filled rounded rectangle
        $divisor = 1.5;
        $radius = min($width, $height) / $divisor;
        imagefilledellipse($image, $width / 2, $height / 2, $radius * 2, $radius * 2, $bg);
    } elseif ($shape == "rectangle" || empty($shape)) {
        // Fill the image with the background color
        $divisor = 1;
        $radius = min($width, $height) / $divisor;
        imagefilledrectangle($image, 0, 0, $width - 1, $height - 1, $bg);
    } else {
        die("Unsupported shape: $shape");
    }


    // Rotate the image if specified
    if ($angle != 0) {
        $rotated = imagerotate($image, $angle, imagecolorallocatealpha($image, 0, 0, 0, 127));
        imagedestroy($image);
        $image = $rotated;
    }

    return $image;
}

/* ===================================================================== */
/*                          FUNCTION: addBorder                          */
/* ===================================================================== */
function addBorder(&$image, $border, $border_color) {

    try {
        if (!is_numeric($border)) {
            throw new Exception("Border must be numeric");
        }
        if ($border < 0) {
            throw new Exception("Border must be greater than or equal to zero");
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }

    // Get image dimensions
    $image_size   = getImageDimensions($image);
    $width        = $image_size['width'];
    $height       = $image_size['height'];

    // Add border if specified
    if ($border > 0) {
        $border_rgb = hex2rgb($border_color);
        $border_color_allocated = imagecolorallocate($image, $border_rgb[0], $border_rgb[1], $border_rgb[2]);
        
        // Draw border rectangle
        for ($i = 0; $i < $border; $i++) {
            imagerectangle($image, $i, $i, $width - 1 - $i, $height - 1 - $i, $border_color_allocated);
        }
    }
    return $image;
}

/* ===================================================================== */
/*                           FUNCTION: addText                           */
/* ===================================================================== */
function addText(&$image, $font, $text, $font_size, $color, $angle = 0, $text_pos_x = 0, $text_pos_y = 0) {

    try {
        if (!is_numeric($font_size)) {
            throw new Exception("Font size must be numeric");
        }
        if ($font_size <= 0) {
            throw new Exception("Font size must be greater than zero");
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }

    // Get image dimensions
    $image_size   = getImageDimensions($image);
    $image_width  = $image_size['width'];
    $image_height = $image_size['height'];

    // Get text dimensions
    $text_size   = getTextDimensions($font, $text, $font_size);
    $text_width  = $text_size['width'];
    $text_height = $text_size['height'];

    // Calculate text position (center)
    $text_pos   = calculateTextPos($image, $font, $text, $font_size);
    $text_pos_x = (!empty($text_pos_x) && $text_pos_x != 0) ? $text_pos_x : $text_pos['text_pos_x'];
    $text_pos_y = (!empty($text_pos_y) && $text_pos_y != 0) ? $text_pos_y : $text_pos['text_pos_y'];

    // Allocate text color
    $text_rgb   = hex2rgb($color);
    $text_color = imagecolorallocate($image, $text_rgb[0], $text_rgb[1], $text_rgb[2]);

    // Add text to image
    imagettftext($image, $font_size, $angle, $text_pos_x, $text_pos_y, $text_color, $font, $text);

    return $image;
}

/* ===================================================================== */
/*                         FUNCTION: outputImage                         */
/* ===================================================================== */
function showImage(&$image, $format = 'png') {

    if ($format == 'png') {
        header('Content-Type: image/png');
        imagepng($image);
    } elseif ($format == 'jpeg') {
        header('Content-Type: image/jpeg');
        imagejpeg($image);
    } elseif ($format == 'gif') {
        header('Content-Type: image/gif');
        imagegif($image);
    } elseif ($format == 'webp') {
        header('Content-Type: image/webp');
        imagewebp($image);
    } elseif ($format == 'bmp') {
        header('Content-Type: image/bmp');
        imagebmp($image);
    } elseif ($format == 'wbmp') {
        header('Content-Type: image/vnd.wap.wbmp');
        imagewbmp($image);
    } elseif ($format == 'xbm') {
        header('Content-Type: image/x-xbitmap');
        imagexbm($image);
    } elseif ($format == 'gd') {
        header('Content-Type: image/gd');
        imagegd($image);
    } elseif ($format == 'gd2') {
        header('Content-Type: image/gd2');
        imagegd2($image);
    } else {
        die("Unsupported image format: $format");
    }

    // Free memory
    imagedestroy($image);
}

/* ===================================================================== */
/*                      FUNCTION: getImageDimensions                     */
/* ===================================================================== */
function getImageDimensions(&$image) {
    // Return image dimensions
    return [
        'width' => imagesx($image),
        'height' => imagesy($image)
    ];
}

/* ===================================================================== */
/*                      FUNCTION: getTextDimensions                      */
/* ===================================================================== */
function getTextDimensions($font, $text, $font_size) {
    // Use provided font size instead of calculating it
    $bbox        = imagettfbbox($font_size, 0, $font, $text);
    $text_width  = $bbox[2] - $bbox[0];
    $text_height = $bbox[1] - $bbox[7];

    return [
        'width' => $text_width,
        'height' => $text_height
    ];
}

/* ===================================================================== */
/*                           FUNCTION: textFits                          */
/* ===================================================================== */
function textFits(&$image, $font, $text, $font_size, $border = 0) {
    // Get image dimensions
    $image_size   = getImageDimensions($image);
    $image_width  = $image_size['width'];
    $image_height = $image_size['height'];

    // Get text dimensions
    $text_size    = getTextDimensions($font, $text, $font_size);
    $text_width   = $text_size['width'];
    $text_height  = $text_size['height'];

    // Check if text fits within image boundaries
    if ($text_width > $image_width - 2 * $border || $text_height > $image_height - 2 * $border) {
        return false; // Text does not fit
    }
    return true; // Text fits
}

/* ===================================================================== */
/*                       FUNCTION: calculateTextPos                      */
/* ===================================================================== */
function calculateTextPos(&$image, $font, $text, $font_size) {
    // Get image dimensions
    $image_size   = getImageDimensions($image);
    $image_width  = $image_size['width'];
    $image_height = $image_size['height'];

    // Get text dimensions
    $text_size    = getTextDimensions($font, $text, $font_size);
    $text_width   = $text_size['width'];
    $text_height  = $text_size['height'];

    // Calculate text position (center)
    $x = ($image_width - $text_width) / 2;
    $y = ($image_height + $text_height) / 2;

    return ['text_pos_x' => $x, 'text_pos_y' => $y];
}

/* ===================================================================== */
/*                        FUNCTION: recursiveScan                        */
/* ===================================================================== */
function recursiveScan($dir) {
        $fonts = [];
        foreach (scandir($dir) as $item) {
            if ($item == "." || $item == "..") {
                continue;
            }
            $path = $dir . "/" . $item;
            if (is_dir($path)) {
                $fonts = array_merge($fonts, recursiveScan($path));
            } elseif (preg_match("/\.ttf$/", $item)) {
                $fonts[] = $path;
            }
        }
        return $fonts;
}

/* ===================================================================== */
/*                          FUNCTION: colorInput                         */
/* ===================================================================== */
function colorInput($input_name = "color", $color = "#000000") {
    $colorinput = '
        <input type="color" class="form-control form-control-color" style="max-width: 100px;"
        name="' . $input_name . '" 
        id="' . $input_name . '" 
        value="' . $color . '" required>
        <button type="button" class="btn btn-dark randomize-color" data-input="' . $input_name . '">🎲</button>
     ';
     return $colorinput;
}

?>