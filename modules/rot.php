<div id="rot" class="content">
<div class="card card-primary">
<h1 class="card-header">ROT</h1>
<div class="card card-body">
<form class="form" action="gen.php" method="POST" id="rot">
  <input type="hidden" name="action" value="rot">
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
  echo '<input type="number" name="rotations" id="rotations" class="form-control" value="'.$rotations.'" placeholder="Optional: Amount of rotations (13 default)">';
  echo '<input type="hidden" name="bruteforce" value="0">';
  echo '<label><input type="checkbox" name="bruteforce" id="rotbruteforce" value="1"> Bruteforce</label><br>';
  ?>
  <input type="submit" name="rot" value="Generate" class="btn btn-success">
</form>
 <div class="responseDiv" id="rotresponse"></div>
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