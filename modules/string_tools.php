<div id="string_tools" class="content">
<div class="card">
<h1 class="card-header">Random String Generator</h1>
<div class="card card-body">
<span class="description">This will generate a string with the charset defined.</span>
<hr>
<form class="form" action="gen.php" method="POST" id="stringgen">
<input type="hidden" name="containnumbers" value="0">
<input type="hidden" name="containletters" value="0">
<input type="hidden" name="containuletters" value="0">
<input type="hidden" name="containsymbols" value="0">
<input type="hidden" name="customizecharset" value="0">
<select name="digits" class="form-select">
<?php
$start = 1;
$maxdigits = 100;
while ($start <= $maxdigits) {
$selected = ($start == 10) ? 'selected' : '';
  if (isset($_POST['digits']) && $_POST['digits'] == $start) {
  echo "<option value='$start' selected>$start</option>";
  }
  else {
  echo "<option value='$start' $selected>$start</option>";
  }
  $start++;
}
?>
</select>
<?php
if (isset($_POST['containnumbers']) && $_POST['containnumbers'] == 1) {
$numberscheckbox = '<label><input type="checkbox" name="containnumbers" value="1" checked> Contain numbers</label> <font color="grey">0-9</font><br>';
} else {
$numberscheckbox = '<label><input type="checkbox" name="containnumbers" value="1" checked> Contain numbers</label> <font color="grey">0-9</font><br>';
}
if (isset($_POST['containletters']) && $_POST['containletters'] == 1) {
$letterscheckbox = '<label><input type="checkbox" name="containletters" value="1" checked> Contain lowercase letters</label> <font color="grey">a-z</font><br>';
}
else {
$letterscheckbox = '<label><input type="checkbox" name="containletters" value="1" checked> Contain lowercase letters</label> <font color="grey">a-z</font><br>';
}
if (isset($_POST['containuletters']) && $_POST['containuletters'] == 1) {
$uletterscheckbox = '<label><input type="checkbox" name="containuletters" value="1" checked> Contain uppercase letters</label> <font color="grey">A-Z</font><br>';
}
else {
$uletterscheckbox = '<label><input type="checkbox" name="containuletters" value="1" checked> Contain uppercase letters</label> <font color="grey">A-Z</font><br>';
}
if (isset($_POST['containsymbols']) && $_POST['containsymbols'] == 1) {
$symbolscheckbox = '<label><input type="checkbox" name="containsymbols" value="1" checked> Contain symbols</label> <font color="grey">!#¤%&\/()=?;:-_.,\'"*^<>{}[]@~+´`</font><br>';
}
else {
$symbolscheckbox = '<label><input type="checkbox" name="containsymbols" value="1"> Contain symbols</label> <font color="grey">!#¤%&\/()=?;:-_.,\'"*^<>{}[]@~+´`</font><br>';
}
if (isset($_POST['containesymbols']) && $_POST['containesymbols'] == 1) {
$symbolsecheckbox = '<label><input type="checkbox" name="containesymbols" value="1" checked> Contain extended symbols</label> <font color="grey">ƒ†‡™•)</font><br>';
}
else {
$symbolsecheckbox = '<label><input type="checkbox" name="containesymbols" value="1"> Contain extended symbols</label> <font color="grey">ƒ†‡™•</font><br>';
}
if (isset($_POST['customizecharset']) && $_POST['customizecharset'] == 1) {
$customizecharset = '<label><input type="checkbox" name="customizecharset" id="customizecharset" value="1" checked> Custom characters</label><br>
<textarea class="form-control" name="charset" id="charset" style="display:none;"></textarea><br>';
}
else {
$customizecharset = '<label><input type="checkbox" name="customizecharset" id="customizecharset" value="1"> Custom characters</label><br>
<textarea class="form-control" name="charset" id="charset" style="display:none;"></textarea><br>';
}
echo $numberscheckbox;
echo $letterscheckbox;
echo $uletterscheckbox;
echo $symbolscheckbox;
echo $symbolsecheckbox;
echo $customizecharset;

?>
</label><br>
<input type="submit" name="submit" class="btn btn-success" value="Generate">
</form>

<div id="stringgenresponse"></div>
</div>
</div>

<hr>

<div class="card">
  <h1 class="card-header">String Repeater</h1>
  <div class="card-body">
  <span class="description">This will repeat the given string n number of times</span>
  <hr>
  <form class="form" action="gen.php" method="POST" id="strrepeat">
    String to repeat:
    <input type="text" name="repeatstr" class="form-control">
    How many times to repeat:
    <input type="number" name="repeatamt" class="form-control">
    <input type="submit" class="btn btn-success" value="Generate">
  </form>
  <div id="strrepeatresponse"></div>
  </div>
</div>

<hr>

<div class="card card-primary">
<h1 class="card-header">String Shuffler</h1>
<div class="card card-body">
<span class="description">Randomly shuffle a string</span>
<hr>
<form class="form" action="gen.php" method="POST" id="shuffler">
  <?php
  if (isset($_POST['shuffler'])) {
  $shuffler = $_POST['shuffler'];
  }
  else {
  $shuffler = NULL;
  }
  echo '<input type="text" name="shuffler" class="form-control" value="'.$shuffler.'">';
  ?>
  <input type="submit" name="shuffle" value="Generate" class="btn btn-success">
</form>
 <div id="shufflerresponse"></div>
</div>
</div>
</div>

<script>
      // Toggle charset
      $("#customizecharset").change(function() {
      if ($(this).is(":checked")) {
        $("#charset").fadeIn();
      } else {
        $("#charset").fadeOut();
      }
    });
</script>