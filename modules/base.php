<div id="base" class="content">
<div class="card card-primary">
<h1 class="card-header">Base</h1>
<div class="card card-body">
<span class="description">Input any text or base encoded string below, and this tool will convert it to all other base formats.</span>
<form class="form" action="gen.php" method="POST" id="base">
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
</div>