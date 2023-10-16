<div id="rot" class="content">
<div class="card card-primary">
<h1 class="card-header">ROT</h1>
<div class="card card-body">
<form class="form" action="gen.php" method="POST" id="rot">
  <?php
  if (isset($_POST['rot'])) {
  $rot = $_POST['rot'];
  $rotations = $_POST['rotations'];
  }
  else {
  $rot = NULL;
  $rotations = NULL;
  }
  echo '<input type="text" name="rot" class="form-control" value="'.$rot.'">';
  echo '<input type="number" name="rotations" id="rotations" class="form-control" value="'.$rotations.'" placeholder="Optional: Amount of rotations (13 default)">';
  echo '<input type="hidden" name="bruteforce" value="0">';
  echo '<label><input type="checkbox" name="bruteforce" id="rotbruteforce" value="1"> Bruteforce</label><br>';
  ?>
  <input type="submit" name="rot" value="Generate" class="btn btn-success">
</form>
 <div id="rotresponse"></div>
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