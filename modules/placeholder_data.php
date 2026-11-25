<?php
$fakeDefaults = ['name', 'email', 'phone'];
?>
<div id="placeholder_data" class="content">

    <nav class="module-subnav" aria-label="Placeholder Data navigation">
        <a class="subnav-pill" data-subtab="lorem" href="#placeholder_data/lorem"><?= icon("pilcrow") ?> Lorem ipsum</a>
        <a class="subnav-pill" data-subtab="fake" href="#placeholder_data/fake"><?= icon("users") ?> Fake data</a>
    </nav>

    <div class="subnav-panel" data-panel="lorem">
        <div class="card card-primary">
            <h1 class="card-header">Lorem Ipsum</h1>
            <div class="card-body">
                <span class="description">Placeholder text for layouts and test fixtures.</span>
                <hr>
                <form class="form" action="gen.php" method="POST" id="lorem_form" data-action="lorem" data-no-random-buttons>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="lorem_count">How many</label>
                            <input type="number" name="lorem_count" id="lorem_count" class="form-control form-control-lg" value="3" min="1" max="<?= LOREM_MAX['words'] ?>" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label d-block">Of</label>
                            <div class="btn-group" role="group">
                                <?php foreach (LOREM_MAX as $unit => $max): ?>
                                    <input type="radio" class="btn-check" name="lorem_unit" id="lorem_unit_<?= $unit ?>" value="<?= $unit ?>" <?= $unit === 'paragraphs' ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-secondary" for="lorem_unit_<?= $unit ?>" title="Up to <?= $max ?>"><?= ucfirst($unit) ?></label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" class="form-check-input" id="lorem_classic" name="lorem_classic" value="1" checked>
                        <label class="form-check-label" for="lorem_classic">Start with "Lorem ipsum dolor sit amet"</label>
                    </div>

                    <?= seed_field_html("lorem_", "text") ?>

                    <?= submitBtn("lorem", text: "Generate", icon: "pilcrow") ?>

                    <div class="responseDiv mt-3" id="lorem_response"></div>
                </form>
            </div>
        </div>
    </div>

    <div class="subnav-panel" data-panel="fake">
        <div class="card card-primary">
            <h1 class="card-header">Fake Test Data</h1>
            <div class="card-body">
                <span class="description">Made-up rows for test fixtures. Emails and URLs use reserved domains (<code>example.com</code>, <code>.test</code>), IPs come from the documentation ranges (<code>192.0.2.0/24</code>, <code>198.51.100.0/24</code>, <code>203.0.113.0/24</code>, <code>2001:db8::/32</code>), phone numbers from the fictional <code>555-01xx</code> block and MAC addresses are locally administered.</span>
                <hr>
                <form class="form" action="gen.php" method="POST" id="fake_data_form" data-action="fake_data" data-no-random-buttons>
                    <div class="mb-3">
                        <label class="form-label d-block">Fields</label>
                        <?php foreach (fake_data_fields() as $key => $label): ?>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="fake_fields[]" id="fake_field_<?= $key ?>" value="<?= $key ?>" <?= in_array($key, $fakeDefaults, true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="fake_field_<?= $key ?>"><?= $label ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="fake_rows">Rows</label>
                            <input type="number" name="fake_rows" id="fake_rows" class="form-control form-control-lg" value="10" min="1" max="<?= FAKE_DATA_MAX_ROWS ?>" required>
                            <small class="text-muted">1–<?= number_format(FAKE_DATA_MAX_ROWS) ?></small>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label d-block">Format</label>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="fake_format" id="fake_format_lines" value="lines" checked>
                                <label class="btn btn-outline-secondary" for="fake_format_lines">Plain lines</label>
                                <input type="radio" class="btn-check" name="fake_format" id="fake_format_csv" value="csv">
                                <label class="btn btn-outline-secondary" for="fake_format_csv">CSV</label>
                                <input type="radio" class="btn-check" name="fake_format" id="fake_format_json" value="json">
                                <label class="btn btn-outline-secondary" for="fake_format_json">JSON</label>
                            </div>
                        </div>
                    </div>

                    <?= seed_field_html("fake_", "rows") ?>

                    <?= submitBtn("fake_data", text: "Generate", icon: "users") ?>

                    <div class="responseDiv mt-3" id="fake_data_response"></div>
                </form>
            </div>
        </div>
    </div>

</div>
