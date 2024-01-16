<div id="string_tools" class="content">
<div class="card">
<h1 class="card-header">Random String Generator</h1>
<div class="card-body">
<span class="description">This will generate a string with the charset defined.</span>
<hr>
<form class="form" action="gen.php" method="POST" id="stringgen">
  <input type="hidden" name="n" value="0">
  <input type="hidden" name="l" value="0">
  <input type="hidden" name="u" value="0">
  <input type="hidden" name="s" value="0">
  <input type="hidden" name="c" value="0">

  <div class="input-group mb-3">
    <!-- <span class="input-group-text">Length</span> -->
    <div class="form-floating">
      <input type="number" name="digits" class="form-control" id="stringgenLength" value="10">
      <label for="stringgenLength">Length</label>
    </div>
  </div>

<?php
echo '
<div class="card border-secondary">
  <h5 class="card-header text-bg-secondary">Options</h5>
  <div class="card-body">
    <label><input type="checkbox" name="n" value="1" checked> Contain numbers</label> <font color="grey">0-9</font><br>
    <label><input type="checkbox" name="l" value="1" checked> Contain lowercase letters</label> <font color="grey">a-z</font><br>
    <label><input type="checkbox" name="u" value="1" checked> Contain uppercase letters</label> <font color="grey">A-Z</font><br>
    <label><input type="checkbox" name="s" value="1"> Contain symbols</label> <font color="grey">!#¤%&\/()=?;:-_.,\'"*^<>{}[]@~+´`</font><br>
    <label><input type="checkbox" name="e" value="1"> Contain extended symbols</label> <font color="grey">ƒ†‡™•</font><br>
    <label><input type="checkbox" name="c" id="c" value="1"> Custom characters</label><br>
    <div id="cchars" style="display:none;">
      <textarea class="form-control border-secondary" name="cchars" placeholder="Input custom characters here"></textarea>
      <span class="form-text">
        Your custom characters will be appended to the character set.<br>
        If you want to generate a string that contains only your custom characters,
        uncheck all other options.
      </span>
    </div>
  </div>
</div>
'.submitBtn("stringgen");

?>
<div class="responseDiv" id="stringgenresponse"></div>
</form>

</div>
</div>

<hr>

<div class="card">
  <h1 class="card-header">Tools</h1>
  <div class="card-body">

  <form class="form" action="gen.php" method="POST" id="strtools">
    <input type="hidden" name="action" value="stringtools">
    <textarea type="text" name="string" class="form-control mb-3" placeholder="Input string here"></textarea>
    <div class="responseDiv" id="strtoolsresponse" name="string"></div>

    <div class="mb-3">

    <!--
    /*  ───────────────────────────────────────────────────────────────────── */
    /*                           Search and replace                           */
    /* ─────────────────────────────────────────────────────────────────────  */
    -->
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="replace" id="replace" value="1" role="switch">
        <label class="form-check-label" for="replace">Replace</label>
      </div>
      <div class="replaceInput" style="display:none;">
        <div class="form-floating mb-3" >
          <input type="text" id="replaceSearch" name="search" class="form-control" placeholder="Search">
          <label for="replaceSearch">Search</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" id="replaceReplace" name="replace" class="form-control" placeholder="Replace">
          <label for="replaceReplace">Replace</label>
        </div>
      </div>

    <!--
    /*  ───────────────────────────────────────────────────────────────────── */
    /*                                 Repeat                                 */
    /* ─────────────────────────────────────────────────────────────────────  */
    -->
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="repeat" id="repeat" value="1" role="switch">
        <label class="form-check-label" for="repeat">Repeat</label>
      </div>
      <div class="repeatInput form-floating mb-3" style="display:none;">
          <input type="number" id="repeat" name="repeat" class="form-control" placeholder="Times to repeat">
          <label for="repeat">Times to repeat</label>
      </div>

    </div>

    <?php
    $stringTools = [
      "Character" => [
        "Reverse",
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
        echo submitBtn($postvar, "tool", $t, "arrow-repeat", "sm");
      }
      echo "</div>";
    }

    ?>
  </form>

  <!-- <div id="strtoolsresponse"></div> -->
  </div>
</div>

<hr>

<script>
      // Toggle charset
      $("#c").change(function() {
      if ($(this).is(":checked")) {
        $("#cchars").fadeIn();
      } else {
        $("#cchars").fadeOut();
      }
    });

    // Repeat
    $("#repeat").change(function() {
      if ($(this).is(":checked")) {
        $(".repeatInput").fadeIn();
      } else {
        $(".repeatInput").fadeOut();
      }
    });

    // Replace
    $("#replace").change(function() {
      if ($(this).is(":checked")) {
        $(".replaceInput").fadeIn();
      } else {
        $(".replaceInput").fadeOut();
      }
    });
</script>