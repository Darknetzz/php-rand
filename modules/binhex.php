<div id="binhex" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Binary ⟷ Hexadecimal Converter</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong>ℹ️ Binary-to-Text Encoding</strong><br>
                Convert between binary and hexadecimal representations. These encodings are used to represent binary data in a text format, ensuring data integrity during storage and transmission.
            </div>

            <form class="form" action="gen.php" method="POST" id="binhex" data-action="hex">
                
                <!-- Input/Output Section -->
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="binhexInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input</strong></label>
                        <textarea name="binhex" id="binhexInput" class="form-control" style="min-height: 300px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;"
                            placeholder="Enter binary or hexadecimal data...&#10;&#10;Binary example: 01001000 01100101 01101100 01101100 01101111&#10;Hex example: 48656c6c6f" required></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Output</strong></label>
                        <div class="responseDiv flex-grow-1" data-formid="binhex" style="border: 2px solid #495057; padding: 20px; min-height: 300px; max-height: 500px; overflow-y: auto; background: linear-gradient(135deg, rgba(138, 43, 226, 0.1) 0%, rgba(75, 0, 130, 0.05) 100%); border-radius: 0.5rem; font-family: monospace; font-size: 0.95rem; white-space: pre-wrap; word-break: break-all;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 100px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">⟳</div>
                                <div>Converted result will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Options Card -->
                <div class="card border-primary mb-4" style="background-color: rgba(13, 110, 253, 0.05);">
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" name="split" value="1" id="splitToggle">
                            <label class="form-check-label" for="splitToggle"><strong>Split output with delimiter</strong></label>
                        </div>
                        
                        <div class="delimiterinput" style="display:none;">
                            <label class="form-label"><strong>Delimiter Character</strong></label>
                            <input class="form-control form-control-lg" type="text" name="delimiter" value=":" placeholder=":" style="font-family: monospace; max-width: 200px;">
                            <div class="form-text mt-2">Choose a character to separate the output values (e.g., : or space)</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 flex-wrap">
                    <?= submitBtn("bin2hex", "tool", "Binary → Hex", "file-text-fill") ?>
                    <?= submitBtn("hex2bin", "tool", "Hex → Binary", "file-binary-fill") ?>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    // Toggle delimiter input
    $("#splitToggle").change(function() {
        if ($(this).is(":checked")) {
            $(".delimiterinput").slideDown();
        } else {
            $(".delimiterinput").slideUp();
        }
    });
</script>