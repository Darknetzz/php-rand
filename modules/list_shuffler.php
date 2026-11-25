<div id="list_shuffler" class="content">

    <div class="card card-primary">
        <h1 class="card-header">List Shuffler</h1>
        <div class="card-body">
            <span class="description">Paste a list with one item per line, then shuffle it, pick random winners, or split it into teams. Blank lines are ignored.</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="list_shuffler_form" data-action="list_shuffle" data-no-random-buttons>
                <div class="mb-3">
                    <label class="form-label" for="list_items"><strong>Items</strong> <small class="text-muted">(<span id="list_item_count">0</span> items)</small></label>
                    <textarea name="list_items" id="list_items" class="form-control" rows="10" required placeholder="Alice&#10;Bob&#10;Carol&#10;Dave" style="font-family: monospace;"></textarea>
                    <small class="text-muted">Up to <?= number_format(LIST_SHUFFLE_MAX_ITEMS) ?> items</small>
                </div>

                <div class="form-check form-switch mb-3">
                    <input type="checkbox" class="form-check-input" id="list_dedupe" name="list_dedupe" value="1">
                    <label class="form-check-label" for="list_dedupe">Remove duplicate lines</label>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Mode</label>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="list_mode" id="list_mode_shuffle" value="shuffle" checked>
                        <label class="btn btn-outline-secondary" for="list_mode_shuffle"><?= icon('shuffle') ?> Shuffle</label>
                        <input type="radio" class="btn-check" name="list_mode" id="list_mode_pick" value="pick">
                        <label class="btn btn-outline-secondary" for="list_mode_pick"><?= icon('trophy') ?> Pick</label>
                        <input type="radio" class="btn-check" name="list_mode" id="list_mode_groups" value="groups">
                        <label class="btn btn-outline-secondary" for="list_mode_groups"><?= icon('users') ?> Split into groups</label>
                    </div>
                </div>

                <div class="row g-3 mb-3 list-mode-pick" style="display:none;">
                    <div class="col-md-6">
                        <label class="form-label" for="list_pick">Number to pick</label>
                        <input type="number" name="list_pick" id="list_pick" class="form-control" value="1" min="1" max="<?= LIST_SHUFFLE_MAX_ITEMS ?>">
                        <small class="text-muted">Random winners, no repeats</small>
                    </div>
                </div>

                <div class="row g-3 mb-3 list-mode-groups" style="display:none;">
                    <div class="col-md-6">
                        <label class="form-label" for="list_group_by">Split by</label>
                        <select name="list_group_by" id="list_group_by" class="form-select">
                            <option value="count">Number of groups</option>
                            <option value="size">Items per group</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="list_group_n" id="list_group_n_label">Number of groups</label>
                        <input type="number" name="list_group_n" id="list_group_n" class="form-control" value="2" min="1" max="<?= LIST_SHUFFLE_MAX_ITEMS ?>">
                        <small class="text-muted">Group sizes differ by at most one</small>
                    </div>
                </div>

                <?= seed_field_html("list_", "results") ?>

                <?= submitBtn("list_shuffle", text: "Randomize") ?>

                <div class="responseDiv mt-3" id="list_shuffler_response"></div>
            </form>
        </div>
    </div>

</div>

<script>
    (function() {
        const $mod = $("#list_shuffler");
        const updateMode = function() {
            const mode = $mod.find("[name='list_mode']:checked").val();
            $mod.find(".list-mode-pick").toggle(mode === "pick");
            $mod.find(".list-mode-groups").toggle(mode === "groups");
        };
        const updateCount = function() {
            const count = ($mod.find("#list_items").val() || "").split(/\r\n|\r|\n/).filter(function(l) { return l.trim() !== ""; }).length;
            $mod.find("#list_item_count").text(count);
        };
        $mod.on("change", "[name='list_mode']", updateMode);
        $mod.on("input", "#list_items", updateCount);
        $mod.on("change", "#list_group_by", function() {
            $mod.find("#list_group_n_label").text($(this).val() === "size" ? "Items per group" : "Number of groups");
        });
        updateMode();
        updateCount();
    })();
</script>
