<div id="colour_gen" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Colour Generator</h1>
        <div class="card-body">
            <span class="description">Random colours or a 5-colour palette from a colour scheme. Lock the colours you like and re-roll the rest. Generated in your browser with <code>crypto.getRandomValues()</code>.</span>
            <hr>
            <form id="colour_gen_form" data-no-random-buttons onsubmit="return false;">
                <div class="row g-3 mb-3 align-items-end">
                    <div class="col-md-auto">
                        <label class="form-label d-block">Mode</label>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="colour_mode" id="colour_mode_single" value="single">
                            <label class="btn btn-outline-secondary" for="colour_mode_single">Single colour</label>
                            <input type="radio" class="btn-check" name="colour_mode" id="colour_mode_palette" value="palette" checked>
                            <label class="btn btn-outline-secondary" for="colour_mode_palette">Palette</label>
                        </div>
                    </div>
                    <div class="col-md-4 colour-scheme-field">
                        <label class="form-label" for="colour_scheme">Scheme</label>
                        <select id="colour_scheme" class="form-select">
                            <option value="random">Random</option>
                            <option value="analogous" selected>Analogous</option>
                            <option value="complementary">Complementary</option>
                            <option value="triadic">Triadic</option>
                            <option value="monochrome">Monochrome</option>
                        </select>
                    </div>
                </div>

                <button type="button" class="btn btn-lg <?= btnClass('primary') ?> mb-3" id="colour_generate"><?= icon("palette") ?> Generate</button>

                <div class="colour-swatches" id="colour_swatches" aria-live="polite"></div>
            </form>
        </div>
    </div>

</div>
