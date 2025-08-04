<div id="base" class="content">

    <!-- Base -->
    <div class="card card-primary">
        <h1 class="card-header">Base</h1>
        <div class="card-body">
            <span class="description">Input any text or base encoded string below, and this tool will convert it to all other base formats.</span>
            <form class="form" action="gen.php" method="POST" id="base" data-action="base">
                <textarea name="base" class="form-control mb-2"
                    placeholder="Enter text or base encoded string to convert..." value="" required></textarea>
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