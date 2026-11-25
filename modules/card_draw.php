<div id="card_draw" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Card Draw</h1>
        <div class="card-body">
            <span class="description">Draw cards from a freshly shuffled deck, without replacement. The deck is shuffled on the server with <code>random_int()</code>.</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="card_draw_form" data-action="card_draw" data-no-random-buttons>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="card_count">Number of cards</label>
                        <input type="number" name="card_count" id="card_count" class="form-control form-control-lg" value="5" min="1" max="52" required style="font-family: monospace; font-size: 1.5rem;">
                        <small class="text-muted" id="card_count_hint">1–52 cards</small>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="card_jokers" name="card_jokers" value="1">
                            <label class="form-check-label" for="card_jokers">Include two jokers (54 cards)</label>
                        </div>
                    </div>
                </div>

                <?= seed_field_html("card_", "draws") ?>

                <?= submitBtn("card_draw", text: "Draw", icon: "spade") ?>

                <div class="responseDiv mt-3" id="card_draw_response"></div>
            </form>

            <?= result_history_html("card_draw") ?>
        </div>
    </div>

</div>

<script>
    $("#card_draw").on("change", "#card_jokers", function() {
        const max = this.checked ? 54 : 52;
        $("#card_count").attr("max", max);
        $("#card_count_hint").text("1–" + max + " cards");
    });
</script>
