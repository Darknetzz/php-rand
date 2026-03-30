<?php
header('Content-Type: text/html; charset=utf-8');

require_once("includes/_includes.php");
require_once("includes/handlers_functional.php");

$debugEnabled = defined('DEBUG_MODE') && DEBUG_MODE === true;
$responseType = $_POST['responsetype'] ?? 'html';

if (!isset($_POST['action']) && !isset($_POST['hash']) && !isset($_POST['numgenfrom']) && !isset($_POST['rot'])) {
  echo formatOutput("Invalid request: missing action.", type: "danger");
  exit;
}

$output = executeHandler($_POST);
if ($output === null) {
  $action = htmlspecialchars((string) ($_POST['action'] ?? 'unknown'));
  echo formatOutput("No handler found for action '$action'.", type: "danger");
  exit;
}

echo $output;

if ($debugEnabled && $responseType === 'html') {
  $requestDump = htmlspecialchars(
    trim(json_encode($_REQUEST, JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)),
    ENT_QUOTES,
    'UTF-8'
  );
  echo "
    <a class='btn btn-warning' data-bs-toggle='collapse' data-bs-target='#debugCard' aria-expanded='false' aria-controls='debugCard'>".icon('bug-fill')."</a>
    <div class='collapse' id='debugCard' style='margin:15px;'>
      <div class='card border-warning'>
        <h4 class='card-header text-bg-warning'>".icon('bug-fill')." Debug</h4>
        <div class='card-body'><pre>$requestDump</pre></div>
      </div>
    </div>
  ";
}
