<?php

declare(strict_types=1);

ob_start();
require_once __DIR__ . '/includes/_includes.php';
require_once __DIR__ . '/includes/about_info.php';
ob_end_clean();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: private, no-cache, must-revalidate');

echo json_encode(getAboutInfo(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
