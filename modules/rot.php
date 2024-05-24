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
  <div class="form-floating mb-3">
    <textarea name="rot" class="form-control" id="rotInput" placeholder="Input" style="height:200px;">'.$rot.'</textarea>
    <label for="rotInput">Input</label>
  </div>

  ';
  echo '<input type="number" name="rotations" id="rotations" class="form-control mb-1" value="'.$rotations.'" placeholder="Optional: Amount of rotations (13 default)" style="display:none;">';
  echo '<input type="hidden" name="bruteforce" value="0">';
  echo '

  <div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="bruteforce" id="rotbruteforce" value="1" role="switch" checked>
    <label class="form-check-label" for="rotbruteforce">Bruteforce</label>
  </div>

  <br>';
  echo submitBtn("rot", "action", "Generate", "arrow-repeat")
  ?>
  <div class="responseDiv" id="rotresponse"></div>
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