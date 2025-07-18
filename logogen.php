<?php

/* ===================================================================== */
/*                    NOTE: functions.php from logogen                   */
/* ===================================================================== */
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

/* ===================================================================== */
/*                    # NOTE: config.php from logogen                    */
/* ===================================================================== */
/* ========================== NOTE: Prechecks ========================== */
// Set content type to image
if (!extension_loaded('gd')) {
    die('GD extension is not loaded.');
}
if (!function_exists('imagepng')) {
    die('GD imagepng function is not available.');
}
if (!function_exists('imagettftext')) {
    die('GD imagettftext function is not available.');
}
if (!function_exists('imagettftext')) {
    die('GD imagettftext function is not available.');
}

/* =========================== NOTE: Classes =========================== */
$classes = [
    "card"        => "card text-bg-dark border border-secondary mb-3",
    "card-header" => "card-header text-bg-secondary",
    "card-body"   => "card-body border border-secondary text-bg-dark",
    "table-title" => 'class="bg-dark text-warning p-3" colspan="100%" style="font-size:2rem;"',
    "input-title" => 'class="text-info"',
    "thead"       => 'class="mt-5"',
];

/* ========================= NOTE: Size presets ======================== */
$size_presets = [
    "small"     => ["width" => 100, "height" => 100],
    "medium"    => ["width" => 250, "height" => 250],
    "large"     => ["width" => 500, "height" => 500],
    "portrait"  => ["width" => 250, "height" => 500],
    "landscape" => ["width" => 500, "height" => 250],
];
$size_presets_select = "";
foreach ($size_presets as $preset => $dimensions) {
    $width  = $dimensions['width'];
    $height = $dimensions['height'];
    $size_presets_select .= '
        <label class="form-selectgroup-item size-preset" data-width="'.$width.'" data-height="'.$height.'">
            <input type="radio" name="name" value="'.$preset.'" class="form-selectgroup-input" />
            <span class="form-selectgroup-label">'.$preset.' ('.$width.'x'.$height.')</span>
        </label>
    ';
}

/* ============================ NOTE: Shapes =========================== */
$shapes = [
    "rectangle" => "Rectangle (default)",
    "rounded"   => "Rounded",
    "circle"    => "Circle",
];
$shapes_select = "<div class='form-selectgroup'>";
foreach ($shapes as $shape => $label) {
    $checked = "";
    if ($shape == "rectangle") {
        $checked = "checked";
    }
    $shapes_select .= '
        <label class="form-selectgroup-item">
            <input type="radio" name="shape" value="'.$shape.'" class="form-selectgroup-input shape-radio" '.$checked.' />
            <span class="form-selectgroup-label">'.$label.'</span>
        </label>
    ';
}
$shapes_select .= "</div>";

/* ======================== NOTE: Image formats ======================== */
$default_image_format = "png";
$image_formats = [
    "png"  => "image/png",
    "jpg"  => "image/jpeg",
    "jpeg" => "image/jpeg",
    "gif"  => "image/gif",
    "webp" => "image/webp",
    "bmp"  => "image/bmp",
    "wbmp" => "image/vnd.wap.wbmp",
    "xbm"  => "image/xbm",
    "tiff" => "image/tiff",
    "gd"   => "image/gd",
    "gd2"  => "image/gd2",
];
if (!isset($_GET['format']) || !in_array($_GET['format'], $image_formats)) {
    $_GET['format'] = $default_image_format;
}
$image_formats_select = "";
foreach ($image_formats as $format => $mime) {
    $checked = "";
    if ($format == $default_image_format) {
        $checked = "checked";
    }
    $image_formats_select .= '
        <label class="form-selectgroup-item">
            <input type="radio" name="name" value="'.$format.'" class="form-selectgroup-input" '.$checked.' />
            <span class="form-selectgroup-label">'.$format.'</span>
        </label>
    ';
}

/* =========================== NOTE: Filters =========================== */
// IMG_FILTER_NEGATE: Reverses all colors of the image.
// IMG_FILTER_GRAYSCALE: Converts the image into grayscale by changing the red, green and blue components to their weighted sum using the same coefficients as the REC.601 luma (Y') calculation. The alpha components are retained. For palette images the result may differ due to palette limitations.
// IMG_FILTER_BRIGHTNESS: Changes the brightness of the image. Use args to set the level of brightness. The range for the brightness is -255 to 255.
// IMG_FILTER_CONTRAST: Changes the contrast of the image. Use args to set the level of contrast.
// IMG_FILTER_COLORIZE: Like IMG_FILTER_GRAYSCALE, except you can specify the color. Use args, arg2 and arg3 in the form of red, green, blue and arg4 for the alpha channel. The range for each color is 0 to 255.
// IMG_FILTER_EDGEDETECT: Uses edge detection to highlight the edges in the image.
// IMG_FILTER_EMBOSS: Embosses the image.
// IMG_FILTER_GAUSSIAN_BLUR: Blurs the image using the Gaussian method.
// IMG_FILTER_SELECTIVE_BLUR: Blurs the image.
// IMG_FILTER_MEAN_REMOVAL: Uses mean removal to achieve a "sketchy" effect.
// IMG_FILTER_SMOOTH: Makes the image smoother. Use args to set the level of smoothness.
// IMG_FILTER_PIXELATE: Applies pixelation effect to the image, use args to set the block size and arg2 to set the pixelation effect mode.
// IMG_FILTER_SCATTER: Applies scatter effect to the image, use args and arg2 to define the effect strength and additionally arg3 to only apply the on select pixel colors.
$filters = [
    "negate"     => [
        "name"   => "Negate",
        "filter" => IMG_FILTER_NEGATE,
        "args"   => Null,
    ],
    "grayscale"  => [
        "name"   => "Grayscale",
        "filter" => IMG_FILTER_GRAYSCALE,
        "args"   => Null,
    ],
    "brightness" => [
        "name"   => "Brightness",
        "filter" => IMG_FILTER_BRIGHTNESS,
        "args"   => ["brightness_level" => 0],
    ],
    "contrast"   => [
        "name"   => "Contrast",
        "filter" => IMG_FILTER_CONTRAST,
        "args"   => ["contrast_level" => 0],
    ],
    "colorize"   => [
        "name"   => "Colorize",
        "filter" => IMG_FILTER_COLORIZE,
        "args"   => [
            "red"   => 0,
            "green" => 0,
            "blue"  => 0,
            "alpha" => 0,
        ],
    ],
    "emboss"     => [
        "name"   => "Emboss",
        "filter" => IMG_FILTER_EMBOSS,
        "args"   => Null,
    ],
    "edge"       => [
        "name"   => "Edge",
        "filter" => IMG_FILTER_EDGEDETECT,
        "args"   => Null,
    ],
    "gaussian"   => [
        "name"   => "Gaussian blur",
        "filter" => IMG_FILTER_GAUSSIAN_BLUR,
        "args"   => Null,
    ],
    "pixelate"   => [
        "name"   => "Pixelate",
        "filter" => IMG_FILTER_PIXELATE,
        "args"   => [
            "block_size" => 0,
            "mode"       => 0,
        ],
    ],
    "mean"       => [
        "name"   => "Mean removal",
        "filter" => IMG_FILTER_MEAN_REMOVAL,
        "args"   => Null,
    ],
    "smooth"     => [
        "name"   => "Smooth",
        "filter" => IMG_FILTER_SMOOTH,
        "args"   => ["smooth_level" => 0],
    ],
    "selective"  => [
        "name"   => "Selective blur",
        "filter" => IMG_FILTER_SELECTIVE_BLUR,
        "args"   => Null,
    ],
    "scatter"    => [
        "name"   => "Scatter",
        "filter" => IMG_FILTER_SCATTER,
        "args"   => [
            "strength" => 0,
            "mode"     => 0,
            "color"    => 0,
        ],
    ],
];
$filters_select = "";
$filters_args    = "";
foreach ($filters as $name => $filter) {

    $filter_name = $filter["name"];
    $filter_id   = $filter["filter"];
    $arglist     = $filter["args"];

    $filters_select .= '
        <label class="form-selectgroup-item">
            <input type="checkbox" name="name" value="'.$name.'" class="form-selectgroup-input filter-check" />
            <span class="form-selectgroup-label">'.$filter["name"].'</span>
        </label>
    ';

    $filter_args =
        '<table class="table table-sm args-table" style="display:none;" data-filter="'.$name.'">
            <tr><th colspan="100%" class="text-success bg-secondary">✅ '.$filter["name"].'</th></tr>';
    if (empty($arglist)) {
        $filter_args .= '<tr><td><span class="badge text-success">Enabled</span></td></tr>';
    } elseif (!is_array($arglist)) {
        $filter_args .= '<tr><td>Invalid arguments (not an array)</td></tr>';
    } elseif (count($arglist) > 0) {
        foreach ($arglist as $argname => $argdefaultval) {
            $filter_args .= '
            <tr>
                <td>
                    <div class="input-group m-2 p-2">
                        <div class="input-group-text">'.$argname.'</div>
                        <input type="text" name="'.$argname.'" value="'.$argdefaultval.'" class="form-control" />
                    </div>
                </td>
            </tr>
            ';
        }
    }
    $filter_args .= "</table>";
    $filters_args .= $filter_args;
}

/* ========================== NOTE: Size units ========================= */
$size_units = [
    "px",
    "em",
    "rem",
    "%",
    "vw",
    "vh",
    "vmin",
    "vmax",
    "cm",
    "mm",
    "in",
    "pt",
    "pc",
    "ex",
    "ch"
];
$units_dropdown = "";
foreach ($size_units as $unit) {
    $units_dropdown .= "<option value=\"$unit\">$unit</option>";
}

/* ============================ NOTE: Fonts ============================ */
$font_path = "fonts";
$fonts     = recursiveScan($font_path);
if (empty($fonts)) {
    die("No fonts found in $font_path");
}
$default_font = "fonts/MapleMonoNormalNL-Regular.ttf";
if (!in_array($default_font, $fonts)) {
    // $default_font = $fonts[mt_rand(0, count($fonts) - 1)];
    die("Default font not found in $font_path");
}

/* =========================== NOTE: Defaults ========================== */
$defaults = [
        "width"          => 250,
        "width_units"    => "px",
        "height"         => 250,
        "height_units"   => "px",
        "image_rotation" => 0,
        "text"           => "Insert text here...",
        "text_pos_x"     => 0,
        "text_pos_y"     => 0,
        "text_rotation"  => 0,
        "background"     => "#".sprintf('%06X', mt_rand(0, 0xFFFFFF)),
        "color"          => ($default_color = "#".sprintf('%06X', mt_rand(0, 0xFFFFFF))),
        "shape"          => "rectangle", // rectangle, rounded, circle
        "border"         => 0,
        "border_color"   => $default_color,
        "font"           => $default_font,
        "font_size"      => 15,
        "format"         => "png",
        "filter"         => [],
        "filter_args"    => [],
];

$font_dropdown = "";
foreach ($fonts as $font) {
    if ($defaults['font'] == $font) {
        $font_dropdown .= "<option value=\"$font\" selected>$font</option>";
        continue;
    }
    $font_name = str_replace(".ttf", "", basename($font));
    $font_dropdown .= "<option value=\"$font\">$font_name</option>";
}

$width          = isset($_GET['width']) ? $_GET['width'] : $defaults['width'];
$height         = isset($_GET['height']) ? $_GET['height'] : $defaults['height'];
$text           = isset($_GET['text']) ? $_GET['text'] : $defaults['text'];
$image_rotation = isset($_GET['image_rotation']) ? $_GET['image_rotation'] : $defaults['image_rotation'];
$text_rotation  = isset($_GET['text_rotation']) ? $_GET['text_rotation'] : $defaults['text_rotation'];
$font           = isset($_GET['font']) ? $_GET['font'] : $defaults['font'];
$font_size      = isset($_GET['font_size']) ? $_GET['font_size'] : $defaults['font_size'];
$text_pos_x     = isset($_GET['text_pos_x']) ? $_GET['text_pos_x'] : $defaults['text_pos_x'];
$text_pos_y     = isset($_GET['text_pos_y']) ? $_GET['text_pos_y'] : $defaults['text_pos_y'];
$background     = isset($_GET['background']) ? $_GET['background'] : $defaults['background'];
$color          = isset($_GET['color']) ? $_GET['color'] : $defaults['color'];
$shape          = isset($_GET['shape']) ? $_GET['shape'] : $defaults['shape'];
$border         = isset($_GET['border']) ? $_GET['border'] : $defaults['border'];
$border_color   = isset($_GET['border_color']) ? $_GET['border_color'] : $defaults['border_color'];
$format         = isset($_GET['format']) ? $_GET['format'] : $defaults['format'];
$filter         = isset($_GET['filter']) ? $_GET['filter'] : [];
$filter_args    = isset($_GET['filter_args']) ? $_GET['filter_args'] : [];

/* ===================================================================== */
/*                      # NOTE: gen.php from logogen                     */
/* ===================================================================== */
$image = createImage($height, $width, $background, $border, $shape);
$image = addBorder($image, $border, $border_color);
$image = addText($image, $font, $text, $font_size, $color, $image_rotation, $text_pos_x, $text_pos_y);

if (isset($_GET['debug'])) {
    $debug = True;
    if ($debug) {

        if (textFits($image, $font, $text, $font_size, $border = 0) === True) {
            echo "<h3 class='alert alert-success alert-important'>Text fits</h3>";
        } else {
            echo "<h3 class='alert alert-warning alert-important'>Text does not fit</h3>";
        }

        echo "Image format: " . $format . "<br>";



        echo "<table class='table table-sm'>";
        echo "<tr><th colspan='100%' class='text-bg-secondary'><h3>Defaults</h3></th></tr>";
        echo "<tr class='text-bg-secondary'><th>Key</th><th>Value</th></tr>";
        foreach ($defaults as $key => $value) {
            if (is_array($value)) {
            echo "<tr><td>$key</td><td>";
            foreach ($value as $subkey => $subvalue) {
                echo "$subkey: $subvalue<br>";
            }
            echo "</td></tr>";
            } else {
            echo "<tr><td>$key</td><td><code>$value</code></td></tr>";
            }
        }
        echo "</table>";

        echo "<table class='table table-sm'>";
        echo "<tr><th colspan='100%' class='text-bg-secondary'><h3>GET</h3></th></tr>";
        echo "<tr class='text-bg-secondary'><th>Key</th><th>Value</th></tr>";
        foreach ($_GET as $key => $value) {
            if ($key == 'defaults') {
            continue;
            }
            echo "<tr><td>$key</td><td>";
            if (is_array($value)) {
            foreach ($value as $subkey => $subvalue) {
                echo "$subkey: $subvalue<br>";
            }
            } else {
            echo "<code>$value</code>";
            }
            echo "</td></tr>";
        }
        echo "</table>";

        die();
    }
}

showImage($image, $format);
