<div id="base" class="content">
<div class="card card-primary">
<h1 class="card-header">Base</h1>
<div class="card card-body">
<span class="description">Input any text or base encoded string below, and this tool will convert it to all other base formats.</span>
<form class="form" action="gen.php" method="POST" id="base">
  <input type="text" name="base" class="form-control">
  <select name="from" class="form-select">
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
  <input type="submit" value="Convert" class="btn btn-success">
</form>
 <div id="baseresponse"></div>
</div>
</div>
</div>