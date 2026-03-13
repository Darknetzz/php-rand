<div id="gen_number" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Number Generator</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="numgen" data-action="numgen">
                
                <?php
                $numgenRangeMode = isset($_POST['numgenrangemode']) ? $_POST['numgenrangemode'] : 'numeric';
                $minDig = isset($_POST['numgenmindig']) ? (int)$_POST['numgenmindig'] : 1;
                $maxDig = isset($_POST['numgenmaxdig']) ? (int)$_POST['numgenmaxdig'] : 3;
                $fromValue = isset($_POST['numgenfrom']) ? $_POST['numgenfrom'] : '1';
                $toValue = isset($_POST['numgento']) ? $_POST['numgento'] : '100';
                ?>
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label"><strong>Generate a random number</strong></label>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Range by</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="numgenrangemode" id="numgen_mode_numeric" value="numeric" <?= $numgenRangeMode !== 'digits' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="numgen_mode_numeric">Numeric range (From / To)</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="numgenrangemode" id="numgen_mode_digits" value="digits" <?= $numgenRangeMode === 'digits' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="numgen_mode_digits">Digit range (min–max digits)</label>
                            </div>
                        </div>
                    </div>
                    <div class="numgen-numeric-range col-md-6">
                        <label class="form-label">From</label>
                        <input type="number" name="numgenfrom" class="form-control form-control-lg" value="<?= htmlspecialchars($fromValue) ?>" placeholder="1" style="font-family: monospace; font-size: 1.5rem;">
                    </div>
                    <div class="numgen-numeric-range col-md-6">
                        <label class="form-label">To</label>
                        <input type="number" name="numgento" class="form-control form-control-lg" value="<?= htmlspecialchars($toValue) ?>" placeholder="100" style="font-family: monospace; font-size: 1.5rem;">
                    </div>
                    <div class="numgen-digits-range col-12" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Min digits</label>
                                <input type="number" name="numgenmindig" class="form-control form-control-lg" value="<?= (int)$minDig ?>" min="1" max="20" placeholder="1" style="font-family: monospace; font-size: 1.5rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Max digits</label>
                                <input type="number" name="numgenmaxdig" class="form-control form-control-lg" value="<?= (int)$maxDig ?>" min="1" max="20" placeholder="5" style="font-family: monospace; font-size: 1.5rem;">
                            </div>
                        </div>
                        <small class="text-muted">e.g. 2–4 digits → numbers from 10 to 9,999</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Number type</label>
                        <?php
                        $numgenType = isset($_POST['numgentype']) ? $_POST['numgentype'] : 'any';
                        $allowedTypes = [
                            'any' => 'Any number',
                            'prime' => 'Prime only',
                            'composite' => 'Composite only',
                            'odd' => 'Odd only',
                            'even' => 'Even only',
                            'square' => 'Perfect square only',
                            'palindromic' => 'Palindromic only',
                            'fibonacci' => 'Fibonacci only',
                        ];
                        ?>
                        <select name="numgentype" class="form-select form-select-lg" style="font-family: monospace;">
                            <?php foreach ($allowedTypes as $value => $label): ?>
                                <option value="<?= htmlspecialchars($value) ?>" <?= $numgenType === $value ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="number_toolsseedtoggle" name="seed" value="1">
                        <label class="form-check-label" for="number_toolsseedtoggle">Use custom seed (for reproducible results)</label>
                    </div>
                </div>

                <div class="number_toolsseed mb-3" style="display:none;">
                    <label class="form-label">Seed value</label>
                    <input type="text" name="numgenseed" class="form-control" value="" placeholder="Enter a seed value (optional)" style="font-family: monospace;">
                </div>

                <input type="hidden" name="seed" value="0">
                
                <?= submitBtn("numgen") ?>
                
                <div class="responseDiv mt-3" id="numgenresponse" style="border: 1px solid #dee2e6; padding: 20px; min-height: 80px; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; text-align: center; font-size: 2rem; font-weight: bold; display: none;"></div>
            </form>
        </div>
    </div>

</div>

<script>
    // Seed toggle numgen
    $("#number_toolsseedtoggle").change(function() {
        if ($(this).is(":checked")) {
            $(".number_toolsseed").slideDown();
        } else {
            $(".number_toolsseed").slideUp();
        }
    });

    // Toggle numeric range vs digit range
    function numgenToggleRangeMode() {
        var isDigits = $("#numgen_mode_digits").is(":checked");
        $(".numgen-numeric-range").toggle(!isDigits);
        $(".numgen-digits-range").toggle(isDigits);
    }
    $("input[name='numgenrangemode']").on("change", numgenToggleRangeMode);
    numgenToggleRangeMode();

    // Show response div when result comes in
    $("#numgen").on("submit", function() {
        $("#numgenresponse").show();
    });
</script>