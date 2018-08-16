<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<div class="container">
    <h1>Random string generator</h1>
  <div class="panel panel-primary">
  <div class="panel panel-body">
<form action="index.php" method="POST">
<input type="hidden" name="containnumbers" value="0">
<input type="hidden" name="containletters" value="0">
<input type="hidden" name="containuletters" value="0">
<input type="hidden" name="containsymbols" value="0">
<select name="digits" class="form-control">
<?php
$start = 1;
$maxdigits = 100;
while ($start <= $maxdigits) {
  if (isset($_POST['digits']) && $_POST['digits'] == $start) {
  echo "<option value='$start' selected>$start</option>";
  }
  else {
  echo "<option value='$start'>$start</option>";
  }
  $start++;
}
?>
</select>
<?php
if (isset($_POST['containnumbers']) && $_POST['containnumbers'] == 1) {
  echo '<label><input type="checkbox" name="containnumbers" value="1" checked> Contain numbers</label><br>';
} else {
  echo '<label><input type="checkbox" name="containnumbers" value="1"> Contain numbers</label><br>';
}
if (isset($_POST['containletters']) && $_POST['containletters'] == 1) {
  echo '<label><input type="checkbox" name="containletters" value="1" checked> Contain lowercase letters</label><br>';
}
else {
  echo '<label><input type="checkbox" name="containletters" value="1"> Contain lowercase letters</label><br>';
}
if (isset($_POST['containuletters']) && $_POST['containuletters'] == 1) {
  echo '<label><input type="checkbox" name="containuletters" value="1" checked> Contain uppercase letters</label><br>';
}
else {
  echo '<label><input type="checkbox" name="containuletters" value="1"> Contain uppercase letters</label><br>';
}
if (isset($_POST['containsymbols']) && $_POST['containsymbols'] == 1) {
  echo '<label><input type="checkbox" name="containsymbols" value="1" checked> Contain symbols</label><br>';
}
else {
  echo '<label><input type="checkbox" name="containsymbols" value="1"> Contain symbols</label><br>';
}
?>
</label><br>
<input type="submit" name="submit" class="btn btn-success" value="Generate">
</form>

<?php
function cleanString() {

  global $randomString;
  global $digitsint;
  $randomString = utf8_encode($randomString);
  $randomString = trim($randomString);
  echo "<h4><b>Your $digitsint character string: </b>";
  print_r($randomString);
  echo "</h4>";
}
    if(isset($_POST['submit']) &&
    isset($_POST['containnumbers']) &&
    isset($_POST['containletters']) &&
    isset($_POST['containuletters']) &&
    isset($_POST['containsymbols'])
  ) {
    $digits = $_POST['digits'];
    $digitsint = intval($digits);
    $characters = "";
    $numbers = "0123456789";
    $letters = "abcdefghijklmnopqrstuvwxyz";
    $uletters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $symbols = "!#¤%&\/()=?;:-_.,";
    if ($_POST['containnumbers'] == 1) {
      $characters = $characters.$numbers;
    }
    if ($_POST['containletters'] == 1) {
      $characters = $characters.$letters;
    }
    if ($_POST['containuletters'] == 1) {
      $characters = $characters.$uletters;
    }
    if ($_POST['containsymbols'] == 1) {
      $characters = $characters.$symbols;
    }
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $digitsint; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
  cleanString();

$md5RS = md5($randomString);
$sha1RS = sha1($randomString);
$sha256 = hash('sha256', $randomString);
$sha512 = hash('sha512', $randomString);
$poscomb = number_format($charactersLength**$digitsint);
echo "<br><b>SHA1:</b> $sha1RS<br>
<b>SHA256:</b> $sha256<br>
<b>SHA512:</b> $sha512<br>
<b>MD5:</b> $md5RS<br>
<b>Possible combinations:</b> $poscomb";
}
?>
</div>
</div>


<!-------------------------------------------------------------------------------->

<h1>MD5 Hasher</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form action="index.php" method="POST">
  <?php
  if (isset($_POST['md5'])) {
  echo '<input type="text" name="md5" class="form-control" value="' . $_POST['md5'] . '">';
  }
  else {
  echo '<input type="text" name="md5" class="form-control">';
  }
  ?>
  <input type="submit" name="md5hash" value="Hash" class="btn btn-success">
</form>
<?php
if (isset($_POST['md5hash'])) {
  $md5 = md5($_POST['md5']);
  echo "<b>Your hashed string would be:</b> $md5";
}
 ?>
</div>
</div>
<h1>SHA1 Hasher</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form action="index.php" method="POST">
  <?php
  if (isset($_POST['sha1'])) {
  echo '<input type="text" name="sha1" class="form-control" value="' . $_POST['sha1'] . '">';
  }
  else {
  echo '<input type="text" name="sha1" class="form-control">';
  }
  ?>
  <input type="submit" name="sha1hash" value="Hash" class="btn btn-success">
</form>
<?php
if (isset($_POST['sha1hash'])) {
  $sha1 = sha1($_POST['sha1']);
  echo "<b>Your hashed string would be:</b> $sha1";
}
 ?>
</div>
</div>
<h1>SHA256 Hasher</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form action="index.php" method="POST">
  <?php
  if (isset($_POST['sha256'])) {
  echo '<input type="text" name="sha256" class="form-control" value="' . $_POST['sha256'] . '">';
  }
  else {
  echo '<input type="text" name="sha256" class="form-control">';
  }
  ?>
  <input type="submit" name="sha256hash" value="Hash" class="btn btn-success">
</form>
<?php
if (isset($_POST['sha256hash'])) {
  $sha256 = hash('sha256', $_POST['sha256']);
  echo "<b>Your hashed string would be:</b> $sha256";
}
 ?>
</div>
</div>
<h1>SHA512 Hasher</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form action="index.php" method="POST">
  <?php
  if (isset($_POST['sha512'])) {
  echo '<input type="text" name="sha512" class="form-control" value="' . $_POST['sha512'] . '">';
  }
  else {
  echo '<input type="text" name="sha512" class="form-control">';
  }
  ?>
  <input type="submit" name="sha512hash" value="Hash" class="btn btn-success">
</form>
<?php
if (isset($_POST['sha512hash'])) {
  $sha512 = hash('sha512', $_POST['sha512']);
  echo "<b>Your hashed string would be:</b> $sha512";
}
 ?>
</div>
</div>
