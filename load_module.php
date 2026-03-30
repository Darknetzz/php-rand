<?php
header('Content-Type: text/html; charset=utf-8');

require_once("includes/_includes.php");

$module = isset($_GET['module']) ? trim((string) $_GET['module']) : '';

if ($module === '' || !preg_match('/^[a-z0-9_]+$/', $module)) {
  http_response_code(400);
  echo alert("Invalid module name.", "danger");
  exit;
}

$disabledModules = ['encoding'];
if (in_array($module, $disabledModules, true)) {
  http_response_code(404);
  echo alert("Module is disabled.", "warning");
  exit;
}

$modulePath = __DIR__ . "/modules/" . $module . ".php";

if (!is_file($modulePath)) {
  http_response_code(404);
  echo alert("Module not found: " . htmlspecialchars($module, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), "warning");
  exit;
}

include $modulePath;
?>
