<div id="gen_string" class="content">

    <!--
    /* ───────────────────────────────────────────────────────────────────── */
    /*                                 GENERATOR                             */
    /* ───────────────────────────────────────────────────────────────────── */
    -->
    <div class="card">
        <h1 class="card-header">String Generator</h1>
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
                    <div class="form-floating">
                        <input type="number" name="strings" class="form-control" id="strings" value="1">
                        <label for="strings">Amount of strings to generate</label>
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
                      '.(!empty($chars) ? "<span class='badge text-bg-secondary'>".$chars."</span>" : "").'
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

</div>