<?php

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

const RAND_QRCODE_DARK_MODULE_TYPES = [
    QRMatrix::M_DARKMODULE,
    QRMatrix::M_DATA_DARK,
    QRMatrix::M_FINDER_DARK,
    QRMatrix::M_SEPARATOR_DARK,
    QRMatrix::M_ALIGNMENT_DARK,
    QRMatrix::M_TIMING_DARK,
    QRMatrix::M_FORMAT_DARK,
    QRMatrix::M_VERSION_DARK,
    QRMatrix::M_QUIETZONE_DARK,
    QRMatrix::M_LOGO_DARK,
    QRMatrix::M_FINDER_DOT,
];

const RAND_QRCODE_LIGHT_MODULE_TYPES = [
    QRMatrix::M_NULL,
    QRMatrix::M_DARKMODULE_LIGHT,
    QRMatrix::M_DATA,
    QRMatrix::M_FINDER,
    QRMatrix::M_SEPARATOR,
    QRMatrix::M_ALIGNMENT,
    QRMatrix::M_TIMING,
    QRMatrix::M_FORMAT,
    QRMatrix::M_VERSION,
    QRMatrix::M_QUIETZONE,
    QRMatrix::M_LOGO,
    QRMatrix::M_FINDER_DOT_LIGHT,
];

function qrcode_hex_to_rgb(string $hex): ?array {
    $hex = ltrim(trim($hex), '#');

    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }

    if (strlen($hex) !== 6 || !ctype_xdigit($hex)) {
        return null;
    }

    return [
        (int)hexdec(substr($hex, 0, 2)),
        (int)hexdec(substr($hex, 2, 2)),
        (int)hexdec(substr($hex, 4, 2)),
    ];
}

function qrcode_normalize_hex(string $hex, string $fallback): string {
    $rgb = qrcode_hex_to_rgb($hex);

    if ($rgb === null) {
        return $fallback;
    }

    return sprintf('#%02x%02x%02x', $rgb[0], $rgb[1], $rgb[2]);
}

function qrcode_module_values_rgb(array $fg, array $bg): array {
    $moduleValues = [];

    foreach (RAND_QRCODE_DARK_MODULE_TYPES as $type) {
        $moduleValues[$type] = $fg;
    }

    foreach (RAND_QRCODE_LIGHT_MODULE_TYPES as $type) {
        $moduleValues[$type] = $bg;
    }

    return $moduleValues;
}

function qrcode_ecc_level(string $level): int {
    return match (strtoupper($level)) {
        'M' => EccLevel::M,
        'Q' => EccLevel::Q,
        'H' => EccLevel::H,
        default => EccLevel::L,
    };
}

function qrcode_generate_png(
    string $text,
    int $targetSize = 300,
    string $ecc = 'M',
    int $margin = 4,
    string $foreground = '#000000',
    string $background = '#ffffff'
): string {
    $targetSize = max(128, min(1024, $targetSize));
    $margin = max(0, min(20, $margin));

    $fgHex = qrcode_normalize_hex($foreground, '#000000');
    $bgHex = qrcode_normalize_hex($background, '#ffffff');
    $fgRgb = qrcode_hex_to_rgb($fgHex) ?? [0, 0, 0];
    $bgRgb = qrcode_hex_to_rgb($bgHex) ?? [255, 255, 255];

    $options = new QROptions([
        'outputType' => QROutputInterface::GDIMAGE_PNG,
        'eccLevel' => qrcode_ecc_level($ecc),
        'scale' => 12,
        'quietzoneSize' => $margin,
        'bgColor' => $bgRgb,
        'moduleValues' => qrcode_module_values_rgb($fgRgb, $bgRgb),
        'outputBase64' => false,
    ]);

    $png = (new QRCode($options))->render($text);

    if (!function_exists('imagecreatefromstring') || !function_exists('imagecreatetruecolor')) {
        return $png;
    }

    $source = @imagecreatefromstring($png);

    if ($source === false) {
        return $png;
    }

    $resized = imagecreatetruecolor($targetSize, $targetSize);

    if ($resized === false) {
        return $png;
    }

    imagealphablending($resized, false);
    imagesavealpha($resized, true);

    $backgroundColor = imagecolorallocate($resized, $bgRgb[0], $bgRgb[1], $bgRgb[2]);
    imagefill($resized, 0, 0, $backgroundColor);

    imagecopyresampled(
        $resized,
        $source,
        0,
        0,
        0,
        0,
        $targetSize,
        $targetSize,
        imagesx($source),
        imagesy($source)
    );

    ob_start();
    imagepng($resized);
    $resizedPng = (string)ob_get_clean();

    return $resizedPng !== '' ? $resizedPng : $png;
}

function qrcode_png_data_uri(string $png): string {
    return 'data:image/png;base64,' . base64_encode($png);
}
