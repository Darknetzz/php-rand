<div id="coin_flip" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Coin Flip</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="coin_flip_form" data-action="coin_flip" data-no-random-buttons>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="coin_count">Number of coins</label>
                        <input type="number" name="coin_count" id="coin_count" class="form-control form-control-lg" value="1" min="1" max="<?= COIN_MAX_COUNT ?>" required style="font-family: monospace; font-size: 1.5rem;">
                        <small class="text-muted">1–<?= COIN_MAX_COUNT ?> coins</small>
                    </div>
                </div>

                <?= seed_field_html("coin_", "flips") ?>

                <?= submitBtn("coin_flip", text: "Flip") ?>

                <div class="responseDiv mt-3" id="coin_flip_response"></div>
            </form>
        </div>
    </div>

</div>
