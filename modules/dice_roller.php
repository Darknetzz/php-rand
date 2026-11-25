<div id="dice_roller" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Dice Roller</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="dice_roller_form" data-action="dice_roll" data-no-random-buttons>
                <div class="mb-3">
                    <label class="form-label" for="dice_notation">Dice notation <small class="text-muted">(optional, overrides the fields below)</small></label>
                    <input type="text" name="dice_notation" id="dice_notation" class="form-control form-control-lg font-monospace" maxlength="<?= DICE_NOTATION_MAX_LENGTH ?>" placeholder="4d6kh3, 2d20kl1, 1d20+2d6+3, d%" autocomplete="off" spellcheck="false">
                    <small class="text-muted"><code>NdS</code> dice, <code>+K</code>/<code>-K</code> modifiers, <code>khN</code>/<code>klN</code> keep highest/lowest, <code>dhN</code>/<code>dlN</code> drop highest/lowest, <code>d%</code> = d100. Dropped dice show in parentheses.</small>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="dice_count">Number of dice</label>
                        <input type="number" name="dice_count" id="dice_count" class="form-control form-control-lg" value="1" min="1" max="<?= DICE_MAX_COUNT ?>" required style="font-family: monospace; font-size: 1.5rem;">
                        <small class="text-muted">1–<?= DICE_MAX_COUNT ?> dice</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="dice_sides">Sides per die</label>
                        <input type="number" name="dice_sides" id="dice_sides" class="form-control form-control-lg" value="6" min="<?= DICE_MIN_SIDES ?>" max="<?= DICE_MAX_SIDES ?>" required style="font-family: monospace; font-size: 1.5rem;">
                        <small class="text-muted"><?= DICE_MIN_SIDES ?>–<?= DICE_MAX_SIDES ?> sides</small>
                    </div>
                </div>

                <?= seed_field_html("dice_", "rolls") ?>

                <?= submitBtn("dice_roll", text: "Roll") ?>

                <div class="responseDiv mt-3" id="dice_roller_response"></div>
            </form>

            <?= result_history_html("dice_roll") ?>
        </div>
    </div>

</div>
