<div id="gen_image" class="content">
    <?php
    $fonts = logo_discover_font_files();
    $fontOptions = "";
    if (!empty($fonts)) {
        foreach ($fonts as $index => $fontPath) {
            $fontName = basename($fontPath);
            $label = preg_replace('/\.(ttf|otf)$/i', '', $fontName);
            $selected = $index === 0 ? "selected" : "";
            $fontOptions .= "<option value='" . htmlspecialchars($fontName, ENT_QUOTES, 'UTF-8') . "' {$selected}>" . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . "</option>";
        }
    } else {
        $fontOptions = "<option value=''>No fonts in fonts/ — add .ttf or .otf files</option>";
    }
    ?>
    <style>
        #gen_image .logo-gen-table tr.logo-gen-section-header > td {
            background-color: rgba(13, 110, 253, 0.12);
            background-color: rgba(var(--tblr-primary-rgb), 0.14);
        }
    </style>
    <div class="card card-primary">
        <h1 class="card-header"><?= icon("image", 1, 2) ?> Logo Generator</h1>
        <div class="card-body">
            <p class="text-muted mb-4">
                Pick a <strong>preset</strong> or adjust the table below — the <strong>preview updates automatically</strong> as you change options.
            </p>
            <form class="form" action="gen.php" method="POST" id="logoGeneratorForm" data-action="logo_generate">
                <div class="mb-4">
                    <label class="form-label mb-2"><strong>1 · Quick presets</strong></label>
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        <button type="button" class="btn btn-outline-primary btn-sm logo-preset-btn" data-preset="app-icon"
                            title="512×512, rounded square, initials, gradient">App icon</button>
                        <button type="button" class="btn btn-outline-primary btn-sm logo-preset-btn" data-preset="banner"
                            title="1200×400, wide rectangle, full text">Banner</button>
                        <button type="button" class="btn btn-outline-primary btn-sm logo-preset-btn" data-preset="initials-badge"
                            title="384×384 circle, solid fill, border">Initials badge</button>
                        <span class="text-muted small mx-1 d-none d-sm-inline">·</span>
                        <button type="button" class="btn btn-outline-warning btn-sm" id="logoRandomizeBtn"
                            title="Random background, accent, text, and border colors">Shuffle colors</button>
                    </div>
                    <small class="form-text text-muted d-block mt-1">Presets set size, shape, style, and text options together. Shuffle only changes colors.</small>
                </div>

                <label class="form-label mb-2"><strong>2 · Options</strong></label>
                <div class="table-responsive border rounded mb-4">
                    <table class="table logo-gen-table table-hover align-middle mb-0">
                        <tbody>
                            <tr class="logo-gen-section-header">
                                <td colspan="4" class="py-2 small text-uppercase fw-semibold text-body-secondary">Content &amp; size</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap" style="width: 11rem;">
                                    Text
                                    <span class="fw-normal text-muted d-block small">Shown on the logo</span>
                                </th>
                                <td colspan="3">
                                    <input type="text" class="form-control" name="logo_text" value="Rand Studio" maxlength="40"
                                        autocomplete="off" placeholder="Your name or brand">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap">
                                    Width × height
                                    <span class="fw-normal text-muted d-block small">128–1600 px</span>
                                </th>
                                <td>
                                    <label class="form-label small text-muted mb-0" for="logo_width">W</label>
                                    <input type="number" class="form-control" id="logo_width" name="logo_width" min="128" max="1600" value="512">
                                </td>
                                <td>
                                    <label class="form-label small text-muted mb-0" for="logo_height">H</label>
                                    <input type="number" class="form-control" id="logo_height" name="logo_height" min="128" max="1600" value="512">
                                </td>
                                <td class="d-none d-md-table-cell text-muted small align-middle">
                                    Use similar values for squares, different for banners.
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap align-top pt-3">
                                    Font &amp; size
                                    <span class="fw-normal text-muted d-block small">TTF/OTF in fonts/</span>
                                </th>
                                <td colspan="3">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-12 col-lg-5">
                                            <label class="form-label small text-muted mb-1" for="logo_font">Typeface</label>
                                            <select class="form-select" id="logo_font" name="logo_font" aria-label="Font file">
                                                <?= $fontOptions ?>
                                            </select>
                                        </div>
                                        <div class="col-6 col-sm-4 col-lg-2">
                                            <label class="form-label small text-muted mb-1" for="logo_font_size">Size (px)</label>
                                            <input type="number" class="form-control" id="logo_font_size" name="logo_font_size" min="12" max="400" value="96"
                                                aria-describedby="logoFontSizeHint">
                                        </div>
                                        <div class="col-12 col-sm-8 col-lg-5">
                                            <label class="form-label small text-muted mb-1" for="logo_font_size_range" id="logoFontSizeRangeLabel">Scale</label>
                                            <input type="range" class="form-range" id="logo_font_size_range" min="12" max="400" value="96"
                                                aria-labelledby="logoFontSizeRangeLabel" aria-describedby="logoFontSizeHint">
                                            <span id="logoFontSizeHint" class="small text-muted">12–400 px — use the slider or type a value</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="logo-gen-section-header">
                                <td colspan="4" class="py-2 small text-uppercase fw-semibold text-body-secondary">Look</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap">Background style</th>
                                <td>
                                    <select class="form-select" name="logo_style" aria-label="Background style">
                                        <option value="gradient" selected>Gradient</option>
                                        <option value="solid">Solid</option>
                                    </select>
                                </td>
                                <td class="text-nowrap fw-medium">Mask shape</td>
                                <td>
                                    <select class="form-select" name="logo_shape" aria-label="Logo outer shape">
                                        <option value="rounded" selected>Rounded square</option>
                                        <option value="rectangle">Rectangle</option>
                                        <option value="circle">Circle</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap">Border</th>
                                <td colspan="3">
                                    <div class="d-flex flex-wrap align-items-center gap-3">
                                        <div style="width: 7rem; max-width: 100%;">
                                            <input type="number" class="form-control" name="logo_border" min="0" max="24" value="0"
                                                aria-describedby="logoBorderHint">
                                        </div>
                                        <span id="logoBorderHint" class="small text-muted">0–24 px (uses border color below)</span>
                                    </div>
                                </td>
                            </tr>
                            <tr class="logo-gen-section-header">
                                <td colspan="4" class="py-2 small text-uppercase fw-semibold text-body-secondary">Colors</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap align-top pt-3">
                                    Palette
                                    <span class="fw-normal text-muted d-block small">Accent blends in gradients</span>
                                </th>
                                <td colspan="3">
                                    <div class="row row-cols-2 row-cols-sm-4 g-3">
                                        <div class="col">
                                            <label class="form-label small mb-1" for="logo_bg_color">Background</label>
                                            <input type="color" class="form-control form-control-color w-100" id="logo_bg_color" name="logo_bg_color" value="#111827" title="Background">
                                        </div>
                                        <div class="col">
                                            <label class="form-label small mb-1" for="logo_accent_color">Accent</label>
                                            <input type="color" class="form-control form-control-color w-100" id="logo_accent_color" name="logo_accent_color" value="#1d4ed8" title="Gradient accent">
                                        </div>
                                        <div class="col">
                                            <label class="form-label small mb-1" for="logo_text_color">Text</label>
                                            <input type="color" class="form-control form-control-color w-100" id="logo_text_color" name="logo_text_color" value="#ffffff" title="Text color">
                                        </div>
                                        <div class="col">
                                            <label class="form-label small mb-1" for="logo_border_color">Border</label>
                                            <input type="color" class="form-control form-control-color w-100" id="logo_border_color" name="logo_border_color" value="#ffffff" title="Border color">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="logo-gen-section-header">
                                <td colspan="4" class="py-2 small text-uppercase fw-semibold text-body-secondary">Text transform</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap align-middle">Case &amp; initials</th>
                                <td colspan="3">
                                    <div class="d-flex flex-wrap gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="logoUppercase" name="logo_uppercase" value="1">
                                            <label class="form-check-label" for="logoUppercase">ALL CAPS</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="logoInitials" name="logo_initials" value="1">
                                            <label class="form-check-label" for="logoInitials">Use initials only</label>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">Initials: first letter of each word (e.g. “Rand Studio” → RS).</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mb-4">
                    <small id="logoHintText" class="text-muted">Tip: use <strong>Initials badge</strong> for circular avatars; <strong>Banner</strong> for wide headers.</small>
                </div>

                <label class="form-label mb-2"><strong>Live preview</strong></label>
                <div class="responseDiv border rounded p-4 bg-body-secondary bg-opacity-10" id="liveLogoPreview" style="min-height: 240px;">
                    <div class="text-muted" style="opacity: 0.75;">Loading preview…</div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
(function() {
    const form = document.getElementById("logoGeneratorForm");
    if (!form || typeof jQuery === "undefined") return;

    const $form = jQuery(form);
    const hint = document.getElementById("logoHintText");
    let debounceTimer = null;
    let activeXhr = null;
    const DEBOUNCE_MS = 320;

    const setVal = (name, value) => {
        const el = form.querySelector('[name="' + name + '"]');
        if (!el) return;
        if (el.type === "checkbox") {
            el.checked = !!value;
        } else {
            el.value = value;
        }
    };

    const sizeInput = form.querySelector("#logo_font_size");
    const sizeRange = document.getElementById("logo_font_size_range");
    const syncFontSizeUi = () => {
        if (!sizeInput || !sizeRange) return;
        let v = parseInt(sizeInput.value, 10);
        if (Number.isNaN(v)) v = 96;
        v = Math.max(12, Math.min(400, v));
        sizeInput.value = String(v);
        sizeRange.value = String(v);
    };
    if (sizeInput && sizeRange) {
        sizeInput.addEventListener("input", syncFontSizeUi);
        sizeRange.addEventListener("input", function() {
            sizeInput.value = sizeRange.value;
        });
    }

    const runLogoPreview = () => {
        if (typeof setFormVal !== "function" || typeof showData !== "function") return;

        if (activeXhr) {
            activeXhr.abort();
            activeXhr = null;
        }

        const $response = $form.find(".responseDiv");
        setFormVal($form, "responsetype", "html");
        setFormVal($form, "action", $form.data("action") || "logo_generate");

        activeXhr = jQuery.ajax({
            type: "POST",
            url: $form.attr("action") || "gen.php",
            data: $form.serialize(),
            success: function(html) {
                activeXhr = null;
                showData($response, html);
            },
            error: function(jqXHR, textStatus) {
                activeXhr = null;
                if (textStatus === "abort") return;
                const msg = (jqXHR && jqXHR.statusText) ? jqXHR.statusText : "request failed";
                showData($response, "<div class='alert alert-danger'>Preview error: " + msg + "</div>");
            }
        });
    };

    const scheduleLogoPreview = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(runLogoPreview, DEBOUNCE_MS);
    };

    const scheduleLogoPreviewSoon = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(runLogoPreview, 0);
    };

    $form.on("input change", "input:not([type='hidden']), select", scheduleLogoPreview);

    $form.on("submit", function(e) {
        e.preventDefault();
        e.stopPropagation();
        scheduleLogoPreviewSoon();
        return false;
    });

    const setPreset = (preset) => {
        if (preset === "app-icon") {
            setVal("logo_width", 512);
            setVal("logo_height", 512);
            setVal("logo_shape", "rounded");
            setVal("logo_style", "gradient");
            setVal("logo_font_size", 120);
            setVal("logo_border", 0);
            setVal("logo_initials", true);
            setVal("logo_uppercase", true);
            if (hint) hint.textContent = "App icon: square canvas, rounded shape, initials + caps — good for launcher icons.";
            syncFontSizeUi();
            scheduleLogoPreviewSoon();
            return;
        }
        if (preset === "banner") {
            setVal("logo_width", 1200);
            setVal("logo_height", 400);
            setVal("logo_shape", "rectangle");
            setVal("logo_style", "gradient");
            setVal("logo_font_size", 110);
            setVal("logo_border", 0);
            setVal("logo_initials", false);
            setVal("logo_uppercase", false);
            if (hint) hint.textContent = "Banner: wide rectangle with full text — headers and cover images.";
            syncFontSizeUi();
            scheduleLogoPreviewSoon();
            return;
        }
        if (preset === "initials-badge") {
            setVal("logo_width", 384);
            setVal("logo_height", 384);
            setVal("logo_shape", "circle");
            setVal("logo_style", "solid");
            setVal("logo_font_size", 132);
            setVal("logo_border", 8);
            setVal("logo_initials", true);
            setVal("logo_uppercase", true);
            if (hint) hint.textContent = "Initials badge: circle, solid fill, visible border — avatars and seals.";
            syncFontSizeUi();
            scheduleLogoPreviewSoon();
        }
    };

    const randomHex = () => "#" + Math.floor(Math.random() * 16777215).toString(16).padStart(6, "0");
    const randomizePalette = () => {
        setVal("logo_bg_color", randomHex());
        setVal("logo_accent_color", randomHex());
        setVal("logo_text_color", "#ffffff");
        setVal("logo_border_color", randomHex());
        if (hint) hint.textContent = "Colors shuffled — preview updating.";
        scheduleLogoPreviewSoon();
    };

    form.querySelectorAll(".logo-preset-btn").forEach((btn) => {
        btn.addEventListener("click", function() {
            setPreset(this.dataset.preset || "");
        });
    });

    const randomizeBtn = document.getElementById("logoRandomizeBtn");
    if (randomizeBtn) {
        randomizeBtn.addEventListener("click", randomizePalette);
    }

    syncFontSizeUi();
    scheduleLogoPreviewSoon();
})();
</script>