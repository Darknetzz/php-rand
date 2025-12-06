<div id="encoding" class="content">

    <!-- Base -->
    <div class="card card-primary">
        <h1 class="card-header">Base</h1>
        <div class="card-body">
            <span class="description">Input any text or base encoded string below, and this tool will convert it to all other base formats.</span>
            <form class="form" action="gen.php" method="POST" id="base" data-action="base">
                <textarea name="base" class="form-control mb-2" style="min-height: 150px; resize: vertical; font-family: monospace;"
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
                <div class="responseDiv" id="baseresponse" style="margin-top: 15px; border: 1px solid #dee2e6; padding: 15px; min-height: 100px; max-height: 400px; overflow-y: auto; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; white-space: pre-wrap; word-break: break-word;"></div>
            </form>
        </div>
    </div>

    <!-- Character Encodings -->
    <div class="card card-primary">
        <h1 class="card-header">Character Encodings</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="base">
                <span class="form-text">
                    Character Encodings: These are used to represent text in computers, telecommunications equipment,
                    and other devices that use text. Examples include ASCII, Unicode (UTF-8, UTF-16, UTF-32),
                    ISO-8859-1, etc.
                </span>
                <textarea name="character" class="form-control mb-2"
                    placeholder="Enter text to encode/decode..."></textarea>
                <?= alert("Coming soon...", "info") ?>
            </form>
        </div>
    </div>




</div>