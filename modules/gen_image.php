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
            <div class="alert alert-info mb-4">
                Generate logos directly in this app with custom text, style, shape, and colors.
            </div>
            <form class="form" action="gen.php" method="POST" id="logoGeneratorForm" data-action="logo_generate">
                <div class="mb-4">
                    <label class="form-label"><strong>Quick Presets</strong></label>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-outline-primary btn-sm logo-preset-btn" data-preset="app-icon">App Icon</button>
                        <button type="button" class="btn btn-outline-primary btn-sm logo-preset-btn" data-preset="banner">Banner</button>
                        <button type="button" class="btn btn-outline-primary btn-sm logo-preset-btn" data-preset="initials-badge">Initials Badge</button>
                        <button type="button" class="btn btn-outline-warning btn-sm" id="logoRandomizeBtn">Randomize Palette</button>
                    </div>
                    <small class="form-text text-muted">Presets update dimensions, shape, style, and text behavior in one click.</small>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label"><strong>Logo Text</strong></label>
                        <input type="text" class="form-control form-control-lg" name="logo_text" value="Rand Studio" maxlength="40">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label"><strong>Width</strong></label>
                        <input type="number" class="form-control form-control-lg" name="logo_width" min="128" max="1600" value="512">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label"><strong>Height</strong></label>
                        <input type="number" class="form-control form-control-lg" name="logo_height" min="128" max="1600" value="512">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-3">
                        <label class="form-label"><strong>Style</strong></label>
                        <select class="form-select form-select-lg" name="logo_style">
                            <option value="gradient" selected>Gradient</option>
                            <option value="solid">Solid</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label"><strong>Shape</strong></label>
                        <select class="form-select form-select-lg" name="logo_shape">
                            <option value="rounded" selected>Rounded</option>
                            <option value="rectangle">Rectangle</option>
                            <option value="circle">Circle</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label"><strong>Font Size</strong></label>
                        <input type="number" class="form-control form-control-lg" name="logo_font_size" min="12" max="220" value="96">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label"><strong>Border</strong></label>
                        <input type="number" class="form-control form-control-lg" name="logo_border" min="0" max="24" value="0">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label class="form-label"><strong>Background</strong></label>
                        <input type="color" class="form-control form-control-color" name="logo_bg_color" value="#111827">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label"><strong>Accent</strong></label>
                        <input type="color" class="form-control form-control-color" name="logo_accent_color" value="#1d4ed8">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label"><strong>Text</strong></label>
                        <input type="color" class="form-control form-control-color" name="logo_text_color" value="#ffffff">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label"><strong>Font</strong></label>
                        <select class="form-select form-select-lg" name="logo_font">
                            <?= $fontOptions ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label"><strong>Border Color</strong></label>
                        <input type="color" class="form-control form-control-color" name="logo_border_color" value="#ffffff">
                    </div>
                    <div class="col-6 col-md-1 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="logoUppercase" name="logo_uppercase" value="1">
                            <label class="form-check-label" for="logoUppercase">Upper</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="logoInitials" name="logo_initials" value="1">
                            <label class="form-check-label" for="logoInitials">Initials</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 flex-wrap mb-4">
                    <?= submitBtn("logo_generate", "action", "Generate Logo", "brush-fill", "lg") ?>
                </div>
                <div class="mb-3">
                    <small id="logoHintText" class="text-muted">Tip: Use <strong>Initials Badge</strong> for avatars and app icons.</small>
                </div>

                <label class="form-label mb-2"><strong>Output</strong></label>
                <div class="responseDiv" id="logoGeneratorFormresponse" style="border: 2px solid #495057; padding: 20px; min-height: 240px; border-radius: 0.5rem;">
                    <div style="opacity: 0.55;">Generated logo preview and download button will appear here.</div>
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
            if (hint) hint.textContent = "App Icon preset: square logo with initials and strong contrast.";
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
            if (hint) hint.textContent = "Banner preset: wide layout for headers and social cover images.";
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
            if (hint) hint.textContent = "Initials Badge preset: circular emblem with thicker border.";
        }
    };

    const randomHex = () => "#" + Math.floor(Math.random() * 16777215).toString(16).padStart(6, "0");
    const randomizePalette = () => {
        setVal("logo_bg_color", randomHex());
        setVal("logo_accent_color", randomHex());
        setVal("logo_text_color", "#ffffff");
        setVal("logo_border_color", randomHex());
        if (hint) hint.textContent = "Palette randomized. Generate to preview the new style.";
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