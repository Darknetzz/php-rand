<div id="string_tools" class="content">

    <!--
    /* ───────────────────────────────────────────────────────────────────── */
    /*                                 GENERATOR                             */
    /* ───────────────────────────────────────────────────────────────────── */
    -->
    <div class="card">
        <h1 class="card-header">Random String Generator</h1>
        <div class="card-body">
            <span class="description">This will generate a string with the charset defined.</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="stringgen" data-action="stringgen">

                <div class="input-group mb-3">
                    <!-- <span class="input-group-text">Length</span> -->
                    <div class="form-floating">
                        <input type="number" name="digits" class="form-control" id="stringgenLength" value="10">
                        <label for="stringgenLength">Length</label>
                    </div>
                </div>

                <?php
                $opts = [
                  "n" => [
                    "desc" =>"Contain numbers",
                    "checked" => "checked",
                    "chars" => "0-9",
                  ],
                  "l" => [
                    "desc" =>"Contain lowercase letters",
                    "checked" => "checked",
                    "chars" => "a-z",
                  ],
                  "u" => [
                    "desc" =>"Contain uppercase letters", 
                    "checked" => "checked",
                    "chars" => "A-Z",
                  ],
                  "s" => [
                    "desc" =>"Contain symbols", 
                    "checked" => "",
                    "chars" => "!#¤%&\/() = ?;: -_.,'\"*^<>{}[]@~+´`",
                  ],
                  "e" => [
                    "desc" =>"Contain extended symbols", 
                    "checked" => "",
                    "chars" => "ƒ†‡™•",
                  ],
                  "c" => [
                    "desc" =>"Custom characters", 
                    "checked" => "",
                    "chars" => "",
                  ],
                ];

                echo '
                <div class="card border-secondary">
                <h5 class="card-header text-bg-secondary">Options</h5>
                <div class="card-body">    
                ';
                foreach ($opts as $opt => $data) {
                  $checked  = $data["checked"];
                  $desc     = $data["desc"];
                  $chars    = $data["chars"];
                  
                  echo '<input type="hidden" name="'.$opt.'" value="0">';
                  echo '
                  <div class="d-flex justify-content-between">
                    <div>
                      <label><input type="checkbox" id="'.$opt.'" name="'.$opt.'" value="1" '.$checked.'> '.$desc.'</label>
                    </div>
                    <div>
                      '.(!empty($chars) ? "<span class='badge bg-secondary'>".$chars."</span>" : "").'
                    </div>
                  </div>
                  <br>';
                }
                echo '
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
                ';
                ?>

               
                <?= submitBtn("stringgen") ?>
                <div class="responseDiv" id="stringgenresponse"></div>
            </form>
        </div>
    </div>

    <hr>

    <!--
/* ───────────────────────────────────────────────────────────────────── */
/*                                 TOOLS                                 */
/* ───────────────────────────────────────────────────────────────────── */
-->
    <div class="card">
        <h1 class="card-header">Tools</h1>
        <div class="card-body">

            <span class="description">This will perform various operations on the input string.</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="strtools">

                <input type="hidden" name="action" value="stringtools">

                <textarea type="text" id="strtoolsinput" name="string" class="form-control mb-1"
                    style="height:200px;" placeholder="Input string here"></textarea>
                
                <div id="count" class="mb-3 border border-secondary"></div>

                <div class="historyDiv" style='display:none;'></div>
                <div class="responseDiv" id="strtoolsresponse"></div>
                <button type="button" class="btn btn-secondary undo" data-target="#strtoolsinput"><?= icon("arrow-counterclockwise") ?> Undo</button>
                <button type="button" class="btn btn-secondary redo" data-target="#strtoolsinput"><?= icon("arrow-clockwise") ?> Redo</button>
                <button type="button" class="btn btn-secondary clear" data-target="#strtoolsinput"><?= icon("trash") ?> Clear</button>
                
                <div class="card border border-secondary">
                    <h4 class="card-header text-bg-secondary">Options</h4>
                    <div class="card-body">

                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" name="outputToTextbox" id="outputToTextbox" value="1"
                          role="switch" checked>
                      <label class="form-check-label" for="outputToTextbox">Output to textbox</label>
                    </div>

                        <!--
      /*  ───────────────────────────────────────────────────────────────────── */
      /*                           Search and replace                           */
      /* ─────────────────────────────────────────────────────────────────────  */
      -->
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="replace" id="replace" value="1"
                                role="switch">
                            <label class="form-check-label" for="replace">Replace</label>
                        </div>
                        <div class="replaceInput" style="display:none;">
                            <div class="form-floating mb-3">
                                <input type="text" id="replaceSearch" name="search" class="form-control"
                                    placeholder="Search">
                                <label for="replaceSearch">Search</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" id="replaceReplace" name="replace" class="form-control"
                                    placeholder="Replace">
                                <label for="replaceReplace">Replace</label>
                            </div>
                            <?= submitBtn("replace", "tool", "Replace", "arrow-repeat", "sm") ?>
                        </div>

                        <!--
      /*  ───────────────────────────────────────────────────────────────────── */
      /*                                 Repeat                                 */
      /* ─────────────────────────────────────────────────────────────────────  */
      -->

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="repeat" id="repeat" value="1"
                                role="switch">
                            <label class="form-check-label" for="repeat">Repeat</label>
                        </div>
                        <div class="repeatInput form-floating mb-3" style="display:none;">
                            <input type="number" id="repeat" name="repeat" class="form-control"
                                placeholder="Times to repeat">
                            <label for="repeat">Times to repeat</label>
                        </div>

                        <hr>

                        <?php
      $stringTools = [

        "Sanitize"  => [
          [
            "name" => "Trim",
            "icon" => "scissors",
            "value" => "trim",
            "tooltip" => "Remove whitespace from both sides of the string"
          ],
          [
            "name" => "Remove whitespace",
            "icon" => "eraser",
            "value" => "removewhitespace",
            "tooltip" => "Remove whitespace from the string"
          ],
          [
            "name" => "Slugify",
            "icon" => "code-slash",
            "value" => "slugify",
            "tooltip" => "Convert the string to a URL-friendly slug"
          ],
          [
            "name" => "Kebabcase",
            "icon" => "dash",
            "value" => "kebabcase",
            "tooltip" => "Convert the string to kebab case"
          ],
        ],

        "Character" => [
          [
            "name" => "Reverse",
            "icon" => "arrow-left",
            "value" => "reverse",
            "tooltip" => "Reverse the string"
          ],
          [
            "name" => "Repeat",
            "icon" => "repeat",
            "value" => "repeat",
            "tooltip" => "Repeat the string"
          ],
          [
            "name" => "Shuffle",
            "icon" => "dice",
            "value" => "shuffle",
            "tooltip" => "Shuffle the characters in the string"
          ],
        ],

        "Case" => [
          [
            "name" => "Randomcase",
            "icon" => "shuffle",
            "value" => "randomcase",
            "tooltip" => "Randomly change the case of characters in the string"
          ],
          [
            "name" => "Lowercase",
            "icon" => "alphabet",
            "value" => "lowercase",
            "tooltip" => "Convert the string to lowercase"
          ],
          [
            "name" => "Uppercase",
            "icon" => "alphabet-uppercase",
            "value" => "uppercase",
            "tooltip" => "Convert the string to uppercase"
          ],
          [
            "name" => "Titlecase",
            "icon" => "type",
            "value" => "titlecase",
            "tooltip" => "Convert the string to title case"
          ],
          [
            "name" => "Invertedcase",
            "icon" => "arrow-down-up",
            "value" => "invertedcase",
            "tooltip" => "Invert the case of characters in the string"
          ],
          [
            "name" => "Camelcase",
            "icon" => "c-square",
            "value" => "camelcase",
            "tooltip" => "Convert the string to camel case"
          ],
        ],

        "Misc" => [
          [
            "name" => "L33t5p34k",
            "icon" => "123",
            "value" => "l33t5p34k",
            "tooltip" => "Convert the string to l33t5p34k"
          ],
          [
            "name" => "Regex",
            "icon" => "regex",
            "value" => "regex",
            "tooltip" => "Perform regular expression operations on the string"
          ],
        ],
        
        "Formatting" => [
          [
            "name" => "CRLF to LF",
            "icon" => "text-wrap",
            "value" => "crlf2lf",
            "tooltip" => "Convert DOS-like (\\r\\n) line endings to LF (\\n) line endings"
          ],
          [
            "name" => "LF to CRLF",
            "icon" => "text-wrap",
            "value" => "crlf2lf",
            "tooltip" => "Convert to LF (\\n) line endings DOS-like (\\r\\n) line endings"
          ],
          [
            "name" => "Format",
            "icon" => "text-wrap",
            "value" => "formatlineendings",
            "tooltip" => "Format line endings"
          ],
        ],

        "Remove" => [
          [
            "name" => "HTML tags",
            "icon" => "code-square",
            "value" => "removehtmltags",
            "tooltip" => "Remove HTML tags"
          ],
          [
            "name" => "Punctuation",
            "icon" => "dot",
            "value" => "removepunctuation",
            "tooltip" => "Remove punctuation"
          ],
          [
            "name" => "Newlines",
            "icon" => "code-square",
            "value" => "removenewlines",
            "tooltip" => "Remove newlines"
          ],
          [
            "name" => "Tabs",
            "icon" => "code-square",
            "value" => "removetabs",
            "tooltip" => "Remove tabs"
          ],
          [
            "name" => "Spaces",
            "icon" => "code-square",
            "value" => "removespaces",
            "tooltip" => "Remove spaces"
          ],
          [
            "name" => "Slashes",
            "icon" => "code-square",
            "value" => "removeslashes",
            "tooltip" => "Remove slashes"
          ],
          [
            "name" => "Backslashes",
            "icon" => "code-square",
            "value" => "removebackslashes",
            "tooltip" => "Remove backslashes"
          ],
          [
            "name" => "Non-ASCII",
            "icon" => "code-square",
            "value" => "removebackslashes",
            "tooltip" => "Remove non-ASCII characters"
          ],
          [
            "name" => "Non-printable",
            "icon" => "code-square",
            "value" => "removebackslashes",
            "tooltip" => "Remove non-printable characters"
          ],
          [
            "name" => "Whitespace",
            "icon" => "code-square",
            "value" => "removebackslashes",
            "tooltip" => "Remove whitespace characters"
          ],
          [
            "name" => "Numbers",
            "icon" => "code-square",
            "value" => "removebackslashes",
            "tooltip" => "Remove numbers"
          ],
          [
            "name" => "Letters",
            "icon" => "code-square",
            "value" => "removebackslashes",
            "tooltip" => "Remove letters"
          ],
          [
            "name" => "Symbols",
            "icon" => "code-square",
            "value" => "removebackslashes",
            "tooltip" => "Remove symbols"
          ],
          [
            "name" => "Extended symbols",
            "icon" => "code-square",
            "value" => "removebackslashes",
            "tooltip" => "Remove extended symbols"
          ],
          [
            "name" => "Custom characters",
            "icon" => "code-square",
            "value" => "removebackslashes",
            "tooltip" => "Remove custom characters"
          ],
        ],
      ];

      echo "<div class='row'>";
      foreach ($stringTools as $cat => $tool) {
        echo "<h4>$cat</h4>";
        echo "<div class='col'>";
        foreach ($tool as $t) {
          $postvar = strtolower($t["value"]);
          $name = $t["name"];
          $icon = $t["icon"];
          $tooltip = $t["tooltip"];
          echo "
          <span title='$tooltip'>
          ".submitBtn($postvar, "tool", $name, $icon, "sm")."
          </span>
          ";
        }
        echo "</div>";
      }
      echo "</div>";

      ?>
            </form>
        </div>
    </div>
</div>
</div>

</div>

<script>
$(document).ready(function() {

  /* ───────────────────────────────────────────────────────────────────── */
  /*                           updateCharCount();                          */
  /* ───────────────────────────────────────────────────────────────────── */
  function updateCharCount() {
    var charcount = $("#strtoolsinput").val().length;
    var wordcount = $("#strtoolsinput").val().split(" ").length;
    var linecount = $("#strtoolsinput").val().split("\n").length;
    $("#count").html(`
      <div class="d-flex justify-content-evenly">
        <span class="form-text">Characters: ${charcount}</span><br>
        <span class="form-text">Words: ${wordcount}</span><br>
        <span class="form-text">Lines: ${linecount}</span><br>
      </div>
    `);
  }

  /* ───────────────────────────────────────────────────────────────────── */
  /*                             clearInput();                             */
  /* ───────────────────────────────────────────────────────────────────── */
  function clearInput() {
    $("#strtoolsinput").val("");
    $(".clear[data-target='#strtoolsinput']").attr("disabled", true);
  }

/* ───────────────────────────────────────────────────────────────────── */
/*                           // Toggle charset                           */
/* ───────────────────────────────────────────────────────────────────── */
$("#c").change(function() {
    if ($(this).is(":checked")) {
        $("#cchars").fadeIn();
    } else {
        $("#cchars").fadeOut();
    }
});

/* ───────────────────────────────────────────────────────────────────── */
/*                               // Repeat                               */
/* ───────────────────────────────────────────────────────────────────── */
$("#repeat").change(function() {
    if ($(this).is(":checked")) {
        $(".repeatInput").fadeIn();
    } else {
        $(".repeatInput").fadeOut();
    }
});

/* ───────────────────────────────────────────────────────────────────── */
/*                               // Replace                              */
/* ───────────────────────────────────────────────────────────────────── */
$("#replace").change(function() {
    if ($(this).is(":checked")) {
        $(".replaceInput").fadeIn();
    } else {
        $(".replaceInput").fadeOut();
    }
});


/* ───────────────────────────────────────────────────────────────────── */
/*                          // Output to textbox                         */
/* ───────────────────────────────────────────────────────────────────── */
$("#strtools").on("submit", async function() {

  var textbox         = $("#strtoolsinput");
  var outputToTextbox = $("#outputToTextbox").is(":checked");
  var historyDiv      = $(".historyDiv");
  $("#undo").attr("disabled", false);

  var resdiv = $("#strtoolsresponse");

  if (outputToTextbox) {
    resdiv.hide();

    $(this).prepend(`
      <div class="loading">
        <?= alert(spinner("Generating..."), "primary") ?>
      </div>
    `);
    $(this).children().find("button").attr("disabled", true);

    var output = await new Promise(function(resolve, reject) {
      // Simulate an asynchronous operation
      setTimeout(function() {
        resolve(resdiv.text());
        $(".loading").remove();
      }, 1000); // Replace with your actual asynchronous operation
    });

    historyDiv.prepend(`<span class='historyItem'>${output}</span>`); // Use 'output' instead of 'resdiv.text()'

    console.log("Writing output ("+output+") to textbox");
    $(this).children().find("button").attr("disabled", false);
    textbox.val(output);

    updateCharCount();
    return false;
  }

  console.log("Outputting to div");
  resdiv.show();
  return false;
});

/* ───────────────────────────────────────────────────────────────────── */
/*                                  undo                                 */
/* ───────────────────────────────────────────────────────────────────── */
$(".undo").click(function() {
    var textbox     = $("#strtoolsinput");
    var historyDiv  = $(".historyDiv");

    // Only one item
    if (historyDiv.children().length === 1) {
        textbox.val("");
        $("#undo").attr("disabled", true);
        return;
    }

    // No items
    if (historyDiv.children().length < 1) {
        $("#undo").attr("disabled", true);
        $("#strtoolsresponse").html(`<?= alert("No history found", "warning") ?>`);
        $("#strtoolsresponse").fadeIn();
        return;
    }

    var firstHistoryItem = historyDiv.children().eq(1);
    console.log("Undoing: "+firstHistoryItem.text());
    textbox.val(firstHistoryItem.text());
    historyDiv.children().first().remove();
    updateCharCount();
});

/* ───────────────────────────────────────────────────────────────────── */
/*                                 clear                                 */
/* ───────────────────────────────────────────────────────────────────── */
$(".clear").click(function() {
    clearInput();
    updateCharCount();
});

/* ───────────────────────────────────────────────────────────────────── */
/*                            on textbox input                           */
/* ───────────────────────────────────────────────────────────────────── */
$("#strtoolsinput").on("change keyup", function() {
  $(".clear[data-target='#strtoolsinput']").attr("disabled", false);
  updateCharCount();
});

});
</script>