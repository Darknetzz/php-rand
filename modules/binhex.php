<div id="binhex" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Binhex</h1>
        <div class="card-body">
            <span class="form-text m-3">
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