<div id="gen_passphrase" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Passphrase Generator</h1>
        <div class="card-body">
            <span class="description">Diceware-style passphrases such as <code>correct-horse-battery-staple</code>: random words from the <a href="https://www.eff.org/dice" target="_blank" rel="noopener">EFF large wordlist</a> (7,776 words, CC BY 3.0), picked on the server with <code>random_int()</code>.</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="gen_passphrase_form" data-action="passphrase" data-no-random-buttons>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="pp_words"><strong>Number of words</strong></label>
                        <input type="number" name="pp_words" id="pp_words" class="form-control form-control-lg" value="6" min="<?= PASSPHRASE_MIN_WORDS ?>" max="<?= PASSPHRASE_MAX_WORDS ?>" required style="font-family: monospace; font-size: 1.5rem;">
                        <small class="text-muted"><?= PASSPHRASE_MIN_WORDS ?>–<?= PASSPHRASE_MAX_WORDS ?> words; each adds about 12.9 bits</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="pp_count"><strong>Number of passphrases</strong></label>
                        <input type="number" name="pp_count" id="pp_count" class="form-control form-control-lg" value="1" min="1" max="<?= PASSPHRASE_MAX_COUNT ?>" required style="font-family: monospace; font-size: 1.5rem;">
                        <small class="text-muted">1–<?= PASSPHRASE_MAX_COUNT ?></small>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="pp_separator">Separator</label>
                        <select name="pp_separator" id="pp_separator" class="form-select">
                            <option value="hyphen" selected>Hyphen (-)</option>
                            <option value="space">Space</option>
                            <option value="dot">Dot (.)</option>
                            <option value="underscore">Underscore (_)</option>
                            <option value="none">None</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="pp_case">Capitalisation</label>
                        <select name="pp_case" id="pp_case" class="form-select">
                            <option value="none" selected>None (lowercase)</option>
                            <option value="first">First letter of each word</option>
                            <option value="random">Random words (+1 bit per word)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="pp_digit" name="pp_digit" value="1">
                        <label class="form-check-label" for="pp_digit">Append a digit (0–9)</label>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="pp_symbol" name="pp_symbol" value="1">
                        <label class="form-check-label" for="pp_symbol">Append a symbol (<code><?= htmlspecialchars(PASSPHRASE_SYMBOLS) ?></code>)</label>
                    </div>
                    <small class="text-muted d-block">For sites that require a digit or symbol; they add little strength compared with another word.</small>
                </div>

                <?= submitBtn("passphrase", icon: "key-round") ?>

                <div class="responseDiv mt-3" id="gen_passphrase_response"></div>
            </form>
        </div>
    </div>

</div>
