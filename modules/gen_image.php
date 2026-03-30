<div id="gen_image" class="content">
    <?php
    $fonts = glob(APP_ROOT . DIRSEP . "fonts" . DIRSEP . "*.ttf");
    $fontOptions = "";
    if (is_array($fonts) && !empty($fonts)) {
        foreach ($fonts as $index => $fontPath) {
            $fontName = basename($fontPath);
            $selected = $index === 0 ? "selected" : "";
            $fontOptions .= "<option value='" . htmlspecialchars($fontName, ENT_QUOTES, 'UTF-8') . "' {$selected}>" . htmlspecialchars($fontName, ENT_QUOTES, 'UTF-8') . "</option>";
        }
    } else {
        $fontOptions = "<option value=''>No TTF fonts found (fallback mode)</option>";
    }
    ?>
    <div class="card card-primary">
        <h1 class="card-header"><?= icon("image", 1, 2) ?> Logo Generator</h1>
        <div class="card-body">
            <p class="text-muted mb-4">
                Pick a <strong>preset</strong> to get a sensible starting point, tweak the table below, then <strong>Generate Logo</strong>.
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
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            <tr class="table-light">
                                <td colspan="4" class="py-2 small text-uppercase text-muted fw-semibold">Content &amp; size</td>
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
                            <tr class="table-light">
                                <td colspan="4" class="py-2 small text-uppercase text-muted fw-semibold">Look</td>
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
                                <th scope="row" class="text-nowrap">Type size</th>
                                <td>
                                    <input type="number" class="form-control" name="logo_font_size" min="12" max="220" value="96"
                                        aria-describedby="logoFontSizeHint">
                                    <span id="logoFontSizeHint" class="small text-muted">12–220</span>
                                </td>
                                <td class="text-nowrap fw-medium">Border</td>
                                <td>
                                    <input type="number" class="form-control" name="logo_border" min="0" max="24" value="0"
                                        aria-describedby="logoBorderHint">
                                    <span id="logoBorderHint" class="small text-muted">0–24 px (color below)</span>
                                </td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="4" class="py-2 small text-uppercase text-muted fw-semibold">Colors</td>
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
                            <tr class="table-light">
                                <td colspan="4" class="py-2 small text-uppercase text-muted fw-semibold">Font &amp; text behavior</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap">Typeface</th>
                                <td colspan="3">
                                    <select class="form-select" name="logo_font" aria-label="Font file">
                                        <?= $fontOptions ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap align-middle">Transform</th>
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

                <div class="d-flex gap-3 flex-wrap align-items-center mb-2">
                    <span class="small text-muted fw-semibold">3 ·</span>
                    <?= submitBtn("logo_generate", "action", "Generate Logo", "brush-fill", "lg") ?>
                </div>
                <div class="mb-4">
                    <small id="logoHintText" class="text-muted">Tip: use <strong>Initials badge</strong> for circular avatars; <strong>Banner</strong> for wide headers.</small>
                </div>

                <label class="form-label mb-2"><strong>Output</strong></label>
                <div class="responseDiv border rounded p-4 bg-body-secondary bg-opacity-10" id="logoGeneratorFormresponse" style="min-height: 240px;">
                    <div class="text-muted" style="opacity: 0.75;">Generated logo preview and download will appear here after you generate.</div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
(function() {
    const form = document.getElementById("logoGeneratorForm");
    if (!form) return;

    const hint = document.getElementById("logoHintText");
    const setVal = (name, value) => {
        const el = form.querySelector('[name="' + name + '"]');
        if (!el) return;
        if (el.type === "checkbox") {
            el.checked = !!value;
        } else {
            el.value = value;
        }
    };

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
        }
    };

    const randomHex = () => "#" + Math.floor(Math.random() * 16777215).toString(16).padStart(6, "0");
    const randomizePalette = () => {
        setVal("logo_bg_color", randomHex());
        setVal("logo_accent_color", randomHex());
        setVal("logo_text_color", "#ffffff");
        setVal("logo_border_color", randomHex());
        if (hint) hint.textContent = "Colors shuffled — click Generate Logo to preview.";
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
})();
</script>