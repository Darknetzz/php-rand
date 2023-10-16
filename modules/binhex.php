<div id="binhex" class="content">
<div class="card card-primary">
<h1 class="card-header">Bin2Hex</h1>
<div class="card card-body">
<form class="form" action="gen.php" method="POST" id="bin2hex">
  <?php
  if (isset($_POST['bin2hex'])) {
  echo '<input type="text" name="bin2hex" class="form-control" value="' . $_POST['bin2hex'] . '">';
  }
  else {
  echo '<input type="text" name="bin2hex" class="form-control">';
  }
  ?>
  <input type="submit" name="bin2hex" value="Bin2Hex" class="btn btn-success">
</form>
 <div id="bin2hexresponse"></div>
</div>
</div>

<br>

<div class="card card-primary">
<h1 class="card-header">Hex2Bin</h1>
<div class="card card-body">
<form class="form" action="gen.php" method="POST" id="hex2bin">
  <?php
  if (isset($_POST['hex2bin'])) {
  echo '<input type="text" name="hex2bin" class="form-control" value="' . $_POST['hex2bin'] . '">';
  }
  else {
  echo '<input type="text" name="hex2bin" class="form-control">';
  }
  ?>
  <input type="submit" name="hex2bin" value="Hex2Bin" class="btn btn-success">
</form>
 <div id="hex2binresponse"></div>
</div>
</div>
</div>