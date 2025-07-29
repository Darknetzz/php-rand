<div id="encoding" class="content">

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

    <div class="card card-primary">
        <h1 class="card-header">Binhex</h1>
        <div class="card-body">
            <span class="form-text">
                Binary-to-Text Encodings: These are used to encode binary data, notably when that data needs to be
                stored and transferred over media designed to deal with text. This ensures that the data remains
                intact without modification during transport. Examples include Base64, Base32, Base16 (hexadecimal),
                etc.
            </span>
            <form class="form" action="gen.php" method="POST" id="binhex" data-action="hex">
                <textarea type="text" name="binhex" class="form-control mb-2"
                    placeholder="Binary or Hexadecimal"></textarea>
                <label class="mb-2">
                    <input type="checkbox" name="split" value="1" class="toggledelimiter"> Split output
                </label>
                <br>
                <span class="delimiterinput" style="display:none;">
                    Delimiter: <input class="form-control" type="text" name="delimiter" value=":"
                        placeholder="Set the delimiter string">
                </span>
                <hr>
                <div class="btn-group">
                    <?= submitBtn("bin2hex", "tool", "Bin2Hex", "file-text-fill") ?>
                    <?= submitBtn("hex2bin", "tool", "Hex2Bin", "file-binary-fill") ?>
                </div>
                <div class="responseDiv" data-formid="binhex"></div>
            </form>
        </div>
    </div>


</div>