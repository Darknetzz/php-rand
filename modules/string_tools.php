<div id="string_tools" class="content">
<div class="card">
<h1 class="card-header">Random String Generator</h1>
<div class="card card-body">
<span class="description">This will generate a string with the charset defined.</span>
<hr>
<form class="form" action="gen.php" method="POST" id="stringgen">
  <input type="hidden" name="action" value="stringgen">
  <input type="hidden" name="n" value="0">
  <input type="hidden" name="l" value="0">
  <input type="hidden" name="u" value="0">
  <input type="hidden" name="s" value="0">
  <input type="hidden" name="c" value="0">
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
echo '
<label><input type="checkbox" name="n" value="1" checked> Contain numbers</label> <font color="grey">0-9</font><br>
<label><input type="checkbox" name="l" value="1" checked> Contain lowercase letters</label> <font color="grey">a-z</font><br>
<label><input type="checkbox" name="u" value="1" checked> Contain uppercase letters</label> <font color="grey">A-Z</font><br>
<label><input type="checkbox" name="s" value="1"> Contain symbols</label> <font color="grey">!#¤%&\/()=?;:-_.,\'"*^<>{}[]@~+´`</font><br>
<label><input type="checkbox" name="e" value="1"> Contain extended symbols</label> <font color="grey">ƒ†‡™•</font><br>
<label><input type="checkbox" name="c" id="c" value="1"> Custom characters</label><br>
<textarea class="form-control" name="cchars" id="cchars" style="display:none;"></textarea><br>
';

?>
</label>
<input type="submit" name="submit" class="btn btn-success" value="Generate">
</form>

<div class="responseDiv" id="stringgenresponse"></div>
</div>
</div>

<hr>

<div class="card">
  <h1 class="card-header">Tools</h1>
  <div class="card-body">

  <form class="form" action="gen.php" method="POST" id="strtools">
  <div class="class=output" id="strtoolsresponse" name="string"></div>

  <?php
  $stringTools = [
    "Character" => [
      "Reverse",
      "Replace",
      "Repeat",
      "Shuffle",
      "Slugify",
    ],
    "Case" => [
      "Randomcase",
      "Lowercase",
      "Uppercase",
      "Titlecase",
      "Invertedcase",
      "Snakecase",
      "Kebabcase",
    ],
    "Misc" => [
      "L33t5p34k",
      "Regex",
    ],
  ];

  foreach ($stringTools as $cat => $tool) {
    echo "<h4>$cat</h4>";
    echo "<div class='btn-group'>";
    foreach ($tool as $t) {
      $postvar = strtolower($t);
      echo "<button type='submit' name='action' value='$postvar' class='btn btn-success'>$t</button> ";
    }
    echo "</div>";
  }
  ?>
  </form>

  <!-- <div id="strtoolsresponse"></div> -->
  </div>
</div>

<hr>

<!-- <div class="card">
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
</div> -->
</div>

<script>
      // Toggle charset
      $("#c").change(function() {
      if ($(this).is(":checked")) {
        $("#cchars").fadeIn();
      } else {
        $("#cchars").fadeOut();
      }
    });
</script>