<div id="string_tools" class="content">

    <!--
/* ───────────────────────────────────────────────────────────────────── */
/*                                 TOOLS                                 */
/* ───────────────────────────────────────────────────────────────────── */
-->
    <div class="card">
      <h1 class="card-header">✂️ String Tools</h1>
        <div class="card-body">

            <span class="description">This will perform various operations on the input string.</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="strtools">

                <input type="hidden" name="action" value="stringtools">

                <div class="row">
                    <div class="col-lg-6 d-flex flex-column">
                        <label for="strtoolsinput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input String</strong></label>
                        <textarea type="text" id="strtoolsinput" name="string" class="form-control mb-3 flex-grow-1" style="min-height:320px; font-family: monospace; resize: vertical; font-size: 0.95rem; border: 2px solid #495057;"
                          placeholder="Enter your text here..."></textarea>

                        <div id="count" class="mb-3 p-3 border border-info rounded" style="background-color: rgba(0,123,255,0.05);">
                            <div class="d-flex justify-content-between flex-wrap">
                                <span><strong>Characters:</strong> <span id="charCount">0</span></span>
                                <span><strong>Words:</strong> <span id="wordCount">0</span></span>
                                <span><strong>Lines:</strong> <span id="lineCount">0</span></span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary undo"
                                data-target="#strtoolsinput"><?= icon("arrow-counterclockwise") ?> Undo</button>
                            <button type="button" class="btn btn-outline-secondary redo"
                                data-target="#strtoolsinput"><?= icon("arrow-clockwise") ?> Redo</button>
                            <button type="button" class="btn btn-outline-danger clear" data-target="#strtoolsinput"><?= icon("trash") ?>
                                Clear</button>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Output</strong></label>
                        <div class="responseDiv flex-grow-1" id="strtoolsresponse" style="margin:0; border: 2px solid #495057; padding:20px; min-height: 320px; max-height: 520px; overflow-y: auto; background: linear-gradient(135deg, rgba(108, 92, 231, 0.12) 0%, rgba(13, 110, 253, 0.08) 100%); border-radius: 0.5rem; font-family: monospace; white-space: pre-wrap; word-break: break-word; font-size: 0.95rem; box-shadow: 0 6px 16px rgba(0,0,0,0.25);">
                          <div style="opacity: 0.55; text-align: center; padding-top: 110px;">
                            <div style="font-size: 3rem; margin-bottom: 10px;">🧵</div>
                            <div>Processed output will appear here...</div>
                          </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-light mt-2 copyOutput" data-target="#strtoolsresponse" style="width: 100%; border: 1px solid #e9ecef;"><?= icon("files") ?> Copy Output</button>
                    </div>
                </div>

                <hr>

                <div class="card border border-secondary mt-4">
                    <h4 class="card-header text-bg-secondary">Options</h4>
                    <div class="card-body">

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="outputToTextbox" id="outputToTextbox"
                                value="1" role="switch">
                            <label class="form-check-label" for="outputToTextbox">Auto-apply to input</label>
                        </div>

                        <hr>

                        <h5>Tools</h5>

                        <?php
      $stringTools = [

        "Sanitize"  => [
          [
            "name"    => "Trim",
            "icon"    => "scissors",
            "value"   => "trim",
            "tooltip" => "Remove whitespace from both sides of the string"
          ],
          [
            "name"    => "Remove whitespace",
            "icon"    => "eraser",
            "value"   => "removewhitespace",
            "tooltip" => "Remove whitespace from the string"
          ],
          [
            "name"    => "Slugify",
            "icon"    => "code-slash",
            "value"   => "slugify",
            "tooltip" => "Convert the string to a URL-friendly slug"
          ],
          [
            "name"    => "Kebabcase",
            "icon"    => "dash",
            "value"   => "kebabcase",
            "tooltip" => "Convert the string to kebab case"
          ],
        ],

        "Character" => [
          [
            "name"    => "Reverse",
            "icon"    => "arrow-left",
            "value"   => "reverse",
            "tooltip" => "Reverse the string"
          ],
          [
            "name"    => "Repeat",
            "icon"    => "repeat",
            "value"   => "repeat",
            "tooltip" => "Repeat the string"
          ],
          [
            "name"    => "Shuffle",
            "icon"    => "dice",
            "value"   => "shuffle",
            "tooltip" => "Shuffle the characters in the string"
          ],
        ],

        "Case" => [
          [
            "name"    => "Randomcase",
            "icon"    => "shuffle",
            "value"   => "randomcase",
            "tooltip" => "Randomly change the case of characters in the string"
          ],
          [
            "name"    => "Lowercase",
            "icon"    => "alphabet",
            "value"   => "lowercase",
            "tooltip" => "Convert the string to lowercase"
          ],
          [
            "name"    => "Uppercase",
            "icon"    => "alphabet-uppercase",
            "value"   => "uppercase",
            "tooltip" => "Convert the string to uppercase"
          ],
          [
            "name"    => "Titlecase",
            "icon"    => "type",
            "value"   => "titlecase",
            "tooltip" => "Convert the string to title case"
          ],
          [
            "name"    => "Invertedcase",
            "icon"    => "arrow-down-up",
            "value"   => "invertedcase",
            "tooltip" => "Invert the case of characters in the string"
          ],
          [
            "name"    => "Camelcase",
            "icon"    => "c-square",
            "value"   => "camelcase",
            "tooltip" => "Convert the string to camel case"
          ],
        ],

        "Misc" => [
          [
            "name"    => "L33t5p34k",
            "icon"    => "123",
            "value"   => "l33t5p34k",
            "tooltip" => "Convert the string to l33t5p34k"
          ],
          [
            "name"    => "Regex",
            "icon"    => "regex",
            "value"   => "regex",
            "tooltip" => "Perform regular expression operations on the string"
          ],
        ],

        "Formatting" => [
          [
            "name"    => "CRLF to LF",
            "icon"    => "text-wrap",
            "value"   => "crlf2lf",
            "tooltip" => "Convert DOS-like (\\r\\n) line endings to LF (\\n) line endings"
          ],
          [
            "name"    => "LF to CRLF",
            "icon"    => "text-wrap",
            "value"   => "lf2crlf",
            "tooltip" => "Convert to LF (\\n) line endings DOS-like (\\r\\n) line endings"
          ],
          [
            "name"    => "Format",
            "icon"    => "text-wrap",
            "value"   => "formatlineendings",
            "tooltip" => "Format line endings"
          ],
        ],

        "Remove" => [
          [
            "name"    => "HTML tags",
            "icon"    => "code-square",
            "value"   => "removehtmltags",
            "tooltip" => "Remove HTML tags"
          ],
          [
            "name"    => "Punctuation",
            "icon"    => "dot",
            "value"   => "removepunctuation",
            "tooltip" => "Remove punctuation"
          ],
          [
            "name"    => "Newlines",
            "icon"    => "code-square",
            "value"   => "removenewlines",
            "tooltip" => "Remove newlines"
          ],
          [
            "name"    => "Tabs",
            "icon"    => "code-square",
            "value"   => "removetabs",
            "tooltip" => "Remove tabs"
          ],
          [
            "name"    => "Spaces",
            "icon"    => "code-square",
            "value"   => "removespaces",
            "tooltip" => "Remove spaces"
          ],
          [
            "name"    => "Slashes",
            "icon"    => "code-square",
            "value"   => "removeslashes",
            "tooltip" => "Remove slashes"
          ],
          [
            "name"    => "Backslashes",
            "icon"    => "code-square",
            "value"   => "removebackslashes",
            "tooltip" => "Remove backslashes"
          ],
          [
            "name"    => "Non-ASCII",
            "icon"    => "code-square",
            "value"   => "removenonascii",
            "tooltip" => "Remove non-ASCII characters"
          ],
          [
            "name" => "Non-printable",
            "icon" => "code-square",
            "value"   => "removenonprintable",
            "tooltip" => "Remove non-printable characters"
          ],
          [
            "name"    => "Whitespace",
            "icon"    => "code-square",
            "value"   => "removewhitespaceext",
            "tooltip" => "Remove whitespace characters"
          ],
          [
            "name"    => "Numbers",
            "icon"    => "code-square",
            "value"   => "removenumbers",
            "tooltip" => "Remove numbers"
          ],
          [
            "name"    => "Letters",
            "icon"    => "code-square",
            "value"   => "removeletters",
            "tooltip" => "Remove letters"
          ],
          [
            "name"    => "Symbols",
            "icon"    => "code-square",
            "value"   => "removesymbols",
            "tooltip" => "Remove symbols"
          ],
          [
            "name"    => "Extended symbols",
            "icon"    => "code-square",
            "value"   => "removeextendedsymbols",
            "tooltip" => "Remove extended symbols"
          ],
          [
            "name"    => "Custom characters",
            "icon"    => "code-square",
            "value"   => "removecustomcharacters",
            "tooltip" => "Remove custom characters"
          ],
        ],
      ];

      echo "<div class='row g-2'>";
      foreach ($stringTools as $cat => $tool) {
        echo "<div class='mb-4'>";
        echo "<h6 class='text-muted mb-2'><strong>$cat</strong></h6>";
        echo "<div class='d-flex flex-wrap gap-2'>";
        foreach ($tool as $t) {
          $postvar = strtolower($t["value"]);
          $name    = $t["name"];
          $icon    = $t["icon"];
          $tooltip = $t["tooltip"];
          echo "
          <button type='button' class='btn btn-sm btn-outline-primary stringtoolbtn' data-tool='$postvar' title='$tooltip'>
            ".icon($icon)." $name
          </button>
          ";
        }
        echo "</div>";
        echo "</div>";
      }
      echo "</div>";

      ?>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    var history = [];
    var historyIndex = -1;

    /* ───────────────────────────────────────────────────────────────────── */
    /*                           updateCharCount();                          */
    /* ───────────────────────────────────────────────────────────────────── */
    function updateCharCount() {
        var text = $("#strtoolsinput").val();
        var charcount = text.length;
        var wordcount = text.trim().split(/\s+/).filter(w => w.length > 0).length;
        var linecount = text.split("\n").length;
        
        $("#charCount").text(charcount);
        $("#wordCount").text(wordcount);
        $("#lineCount").text(linecount);
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                         handleToolClick                              */
    /* ───────────────────────────────────────────────────────────────────── */
    $(".stringtoolbtn").click(function(e) {
        e.preventDefault();
        var tool = $(this).data("tool");
        var form = $("#strtools");
        var input = $("#strtoolsinput").val();

        if (!input) {
            $("#strtoolsresponse").html('<div class="alert alert-warning mb-0">Please enter some text first.</div>');
            return;
        }

        // Show loading
        var responseDiv = $("#strtoolsresponse");
        responseDiv.html('<div class="text-center py-5"><div class="spinner-border text-primary mb-3" role="status" style="width: 2rem; height: 2rem;"><span class="visually-hidden">Loading...</span></div></div>');

        // Add delay for visibility
        setTimeout(function() {
            var formData = form.serialize() + "&tool=" + tool;
            
            $.ajax({
                type: "POST",
                url: "gen.php",
                data: formData,
                success: function(data) {
                    responseDiv.html(data);
                    
                    // If auto-apply is checked, update input
                    if ($("#outputToTextbox").is(":checked")) {
                        var outputText = data.replace(/<[^>]*>/g, '').trim();
                        history.push(input);
                        historyIndex++;
                        $("#strtoolsinput").val(outputText);
                        updateCharCount();
                    }
                },
                error: function() {
                    responseDiv.html('<div class="alert alert-danger mb-0">Error processing request</div>');
                }
            });
        }, 300);
    });

    /* ───────────────────────────────────────────────────────────────────── */
    /*                             clearInput();                             */
    /* ───────────────────────────────────────────────────────────────────── */
    function clearInput() {
        $("#strtoolsinput").val("");
        history = [];
        historyIndex = -1;
        updateCharCount();
        $("#strtoolsresponse").html("Output will appear here...");
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                                undo                                   */
    /* ───────────────────────────────────────────────────────────────────── */
    $(".undo").click(function() {
        if (historyIndex > 0) {
            historyIndex--;
            $("#strtoolsinput").val(history[historyIndex]);
            updateCharCount();
        }
    });

    /* ───────────────────────────────────────────────────────────────────── */
    /*                                redo                                   */
    /* ───────────────────────────────────────────────────────────────────── */
    $(".redo").click(function() {
        if (historyIndex < history.length - 1) {
            historyIndex++;
            $("#strtoolsinput").val(history[historyIndex]);
            updateCharCount();
        }
    });

    /* ───────────────────────────────────────────────────────────────────── */
    /*                                clear                                  */
    /* ───────────────────────────────────────────────────────────────────── */
    $(".clear").click(function() {
        clearInput();
    });

    /* ───────────────────────────────────────────────────────────────────── */
    /*                            copy output                                */
    /* ───────────────────────────────────────────────────────────────────── */
    $(".copyOutput").click(function() {
      const targetId = $(this).data('target') || '#strtoolsresponse';
      const el = document.querySelector(targetId);
      copyToClipboard(el?.id || 'strtoolsresponse', this);
    });

    /* ───────────────────────────────────────────────────────────────────── */
    /*                            on textbox input                           */
    /* ───────────────────────────────────────────────────────────────────── */
    $("#strtoolsinput").on("change keyup", function() {
        updateCharCount();
    });

    // Initialize
    updateCharCount();

});
</script>
