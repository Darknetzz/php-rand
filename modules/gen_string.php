<div id="gen_string" class="content">

    <div class="card">
        <h1 class="card-header">String Generator</h1>
        <div class="card-body">
            <span class="description">Generate random strings with customizable character sets.</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="stringgen" data-action="stringgen">

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="stringgenLength" class="form-label"><strong>String Length</strong></label>
                        <input type="number" name="digits" class="form-control form-control-lg" id="stringgenLength" value="10" min="1" style="font-family: monospace; font-size: 1.5rem;">
                    </div>
                    <div class="col-md-6">
                        <label for="strings" class="form-label"><strong>Number of Strings</strong></label>
                        <input type="number" name="strings" class="form-control form-control-lg" id="strings" value="1" min="1" style="font-family: monospace; font-size: 1.5rem;">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="cryptoSafe" name="cryptoSafe" value="1">
                            <label class="form-check-label" for="cryptoSafe">
                                <strong>🔐 Cryptographically Secure</strong>
                                <small class="text-muted d-block">Use random_bytes() for security-critical applications</small>
                            </label>
                        </div>
                    </div>
                </div>

                <?php
                $opts = [
                  "n" => [
                    "desc" =>"Numbers",
                    "checked" => "checked",
                    "chars" => "0-9",
                  ],
                  "l" => [
                    "desc" =>"Lowercase letters",
                    "checked" => "checked",
                    "chars" => "a-z",
                  ],
                  "u" => [
                    "desc" =>"Uppercase letters",
                    "checked" => "checked",
                    "chars" => "A-Z",
                  ],
                  "s" => [
                    "desc" =>"Symbols",
                    "checked" => "",
                    "chars" => "!#¤%&\/() = ?;: -_.,'\"*^<>{}[]@~+´`",
                  ],
                  "e" => [
                    "desc" =>"Extended symbols",
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
                <div class="card border-primary mb-3">
                <h5 class="card-header bg-primary text-white">Character Set Options</h5>
                <div class="card-body" style="padding: 20px;">
                ';
                foreach ($opts as $opt => $data) {
                  $checked  = $data["checked"];
                  $desc     = $data["desc"];
                  $chars    = $data["chars"];

                  echo '<input type="hidden" name="'.$opt.'" value="0">';
                  echo '
                  <div class="d-flex justify-content-between align-items-center mb-3 p-2" style="background-color: rgba(0,0,0,0.03); border-radius: 0.25rem;">
                    <div class="form-check form-switch">
                      <input type="checkbox" class="form-check-input" id="'.$opt.'" name="'.$opt.'" value="1" '.$checked.'>
                      <label class="form-check-label" for="'.$opt.'"><strong>'.$desc.'</strong></label>
                    </div>
                    <div>
                      '.(!empty($chars) ? "<code class='px-3 py-2' style='font-size: 1rem; background-color: #e9ecef; color: #212529; border-radius: 0.25rem;'>".$chars."</code>" : "").'
                    </div>
                  </div>';
                }
                echo '
                <div id="cchars" style="display:none;">
                <label class="form-label mt-2"><strong>Custom Characters</strong></label>
                <textarea class="form-control" name="cchars" placeholder="Enter your custom characters here" rows="3" style="font-family: monospace;"></textarea>
                <div class="form-text mt-2">
                  <small>Your custom characters will be added to the character set. To use only custom characters, uncheck all other options above.</small>
                </div>
                </div>
                </div>
                </div>
                ';
                ?>

                <?= submitBtn("stringgen") ?>
                
                <div class="responseDiv mt-3" id="stringgenresponse" style="border: 1px solid #dee2e6; padding: 20px; min-height: 100px; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; word-break: break-all;"></div>
            </form>
        </div>
    </div>

</div>

<script>
    // Toggle custom characters textarea
    $("#c").change(function() {
        if ($(this).is(":checked")) {
            $("#cchars").slideDown();
        } else {
            $("#cchars").slideUp();
        }
    });
</script>