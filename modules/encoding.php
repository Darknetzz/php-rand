<div id="encoding" class="content">

<!-- Base -->
<div class="card card-primary">
<h1 class="card-header">Base</h1>
<div class="card card-body">
<span class="description">Input any text or base encoded string below, and this tool will convert it to all other base formats.</span>
<form class="form" action="gen.php" method="POST" id="base" data-action="base">
  <input type="text" name="base" class="form-control mb-2">
  <select name="from" class="form-select mb-2">
    <option value="text" disabled selected>Please choose input type [default: text/base36]...</option>
    <?php
    for ($i = 2; $i <= 36; $i++) {
      if ($i == 2) {
        $name = "Base $i (binary)";
      } else {
        $name = "Base $i";
      }
      echo "<option value='$i'>$name</option>";
    }
    ?>
  </select>
  <?= submitBtn("base", "action", "Convert", "arrow-repeat") ?>
  <div class="responseDiv" id="baseresponse"></div>
</form>
</div>
</div>

<!-- Character Encodings -->
<div class="card card-primary">
<h1 class="card-header">Character Encodings</h1>
<div class="card card-body">
<form class="form" action="gen.php" method="POST" id="base">
  <span class="form-text">
    Character Encodings: These are used to represent text in computers, telecommunications equipment, and other devices that use text. Examples include ASCII, Unicode (UTF-8, UTF-16, UTF-32), ISO-8859-1, etc.
  </span>
  <?= alert("Coming soon...", "info") ?>
</form>
</div>
</div>

<!-- Binary-to-Text Encodings -->
<div class="card card-primary">
<h1 class="card-header">Binary</h1>
<div class="card card-body">
<form class="form" action="gen.php" method="POST" id="base">
  <span class="form-text">
    Binary-to-Text Encodings: These are used to encode binary data, notably when that data needs to be stored and transferred over media designed to deal with text. This ensures that the data remains intact without modification during transport. Examples include Base64, Base32, Base16 (hexadecimal), etc.
  </span>
  <?= alert("Coming soon...", "info") ?>
</form>
</div>
</div>


</div>