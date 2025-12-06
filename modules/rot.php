<div id="rot" class="content">
    <div class="card card-primary">
        <h1 class="card-header">ROT</h1>
        <div class="card card-body">
            <form class="form" action="gen.php" method="POST" data-action="rot">
                <?php
  if (isset($_POST['rot'])) {
    $rot = $_POST['rot'];
    $rotations = $_POST['rotations'];
  }
  else {
    $rot = NULL;
    $rotations = NULL;
  }
  echo '
  <div class="row g-3">
    <div class="col-12 col-lg-6">
      <label for="rotInput" class="form-label"><strong>Input</strong></label>
      <textarea name="rot" class="form-control" id="rotInput" placeholder="Enter text to rotate..." style="min-height: 200px; resize: vertical; font-family: monospace;">'.$rot.'</textarea>
    </div>
    <div class="col-12 col-lg-6 d-flex flex-column">
      <label class="form-label"><strong>Output</strong></label>
      <div class="responseDiv flex-grow-1" id="rotresponse" style="border: 1px solid #dee2e6; padding: 15px; min-height: 200px; max-height: 400px; overflow-y: auto; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; white-space: pre-wrap; word-break: break-word;">Result will appear here...</div>
    </div>
  </div>

  ';
  echo '<input type="number" name="rotations" id="rotations" class="form-control mb-2 mt-3" value="'.$rotations.'" placeholder="Optional: Amount of rotations (13 default)" style="display:none; max-width: 300px;">';
  echo '<input type="hidden" name="bruteforce" value="0">';
  echo '

  <div class="form-check form-switch mt-3">
    <input class="form-check-input" type="checkbox" name="bruteforce" id="rotbruteforce" value="1" role="switch" checked>
    <label class="form-check-label" for="rotbruteforce"><strong>Bruteforce</strong> (show all rotations)</label>
  </div>

  <br>';
  echo submitBtn("rot", "action", "Generate", "arrow-repeat")
  ?>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle ROT bruteforce
$("#rotbruteforce").change(function() {
    if ($(this).is(":checked")) {
        $("#rotations").fadeOut();
    } else {
        $("#rotations").fadeIn();
    }
});
</script>