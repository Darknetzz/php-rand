<div id="gen_image" class="content">
    <?php

    $defaultText = "Text";
    $defaultFontSize = 48;

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
        /* Options: two side-by-side panels (stack on small screens) */
        #gen_image .logo-gen-options-wrap {
            display: flex;
            flex-wrap: wrap;
        }
        #gen_image .logo-gen-options-col {
            flex: 1 1 20rem;
            min-width: min(100%, 20rem);
        }
        #gen_image .logo-gen-options-col + .logo-gen-options-col {
            border-top: 1px solid var(--tblr-border-color, var(--bs-border-color, rgba(0, 0, 0, 0.175)));
        }
        @media (min-width: 992px) {
            #gen_image .logo-gen-options-col {
                flex: 1 1 0;
                max-width: 50%;
            }
            #gen_image .logo-gen-options-col + .logo-gen-options-col {
                border-top: none;
                border-left: 1px solid var(--tblr-border-color, var(--bs-border-color, rgba(0, 0, 0, 0.175)));
            }
        }
        #gen_image .logo-gen-table tbody th[scope="row"] {
            width: 11rem;
            max-width: 11rem;
            vertical-align: middle;
        }
        #gen_image .logo-gen-table tbody tr.logo-gen-row-tall th[scope="row"] {
            vertical-align: top;
        }
        #gen_image .logo-gen-table .logo-gen-size-pair {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }
        #gen_image .logo-gen-table .logo-gen-size-pair > div {
            flex: 1 1 6rem;
            min-width: 5.5rem;
            max-width: 12rem;
        }
        /* Checkerboard + tint so rounded/circle transparency and light theme don’t read as “plain white”. */
        #gen_image #liveLogoPreview.logo-live-preview-host {
            background-color: rgba(32, 32, 40, 0.92) !important;
            background-image:
                linear-gradient(45deg, rgba(255, 255, 255, 0.07) 25%, transparent 25%),
                linear-gradient(-45deg, rgba(255, 255, 255, 0.07) 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, rgba(255, 255, 255, 0.07) 75%),
                linear-gradient(-45deg, transparent 75%, rgba(255, 255, 255, 0.07) 75%);
            background-size: 14px 14px;
            background-position: 0 0, 0 7px, 7px -7px, -7px 0;
        }
        [data-bs-theme="light"] #gen_image #liveLogoPreview.logo-live-preview-host {
            background-color: rgba(0, 0, 0, 0.08) !important;
        }
    </style>
    <div class="card card-primary">
        <h1 class="card-header"><?= icon("image", 1, 2) ?> Logo Generator</h1>
        <div class="card-body">
            <p class="text-muted mb-4">
                Pick a <strong>preset</strong> or adjust the table below — the <strong>live preview updates on every change</strong> (no submit).
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
                <div class="logo-gen-options-wrap border rounded mb-4 overflow-hidden">
                    <div class="logo-gen-options-col">
                        <div class="table-responsive">
                            <table class="table logo-gen-table table-hover align-middle mb-0">
                                <tbody>
                                    <tr class="logo-gen-section-header">
                                        <td colspan="2" class="py-2 small text-uppercase fw-semibold text-body-secondary">Content &amp; size</td>
                                    </tr>
                                    <tr class="logo-gen-row-tall">
                                        <th scope="row" class="text-nowrap">
                                            Text
                                            <span class="fw-normal text-muted d-block small">Shown on the logo</span>
                                        </th>
                                        <td>
                                            <textarea class="form-control" name="logo_text" rows="3" maxlength="500"
                                                autocomplete="off" placeholder="Your name or brand (multiple lines allowed)"><?= $defaultText ?></textarea>
                                            <small class="text-muted">Up to 500 characters. New lines only where you press Enter unless <strong>Autofit</strong> is on (then text also wraps and scales to fit). Best with fonts in <code>fonts/</code>.</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-nowrap">
                                            Width × height
                                            <span class="fw-normal text-muted d-block small">128–1600 px</span>
                                        </th>
                                        <td class="align-middle">
                                            <div class="logo-gen-size-pair">
                                                <div>
                                                    <label class="form-label small text-muted mb-0" for="logo_width">W</label>
                                                    <input type="number" class="form-control" id="logo_width" name="logo_width" min="128" max="1600" value="512">
                                                </div>
                                                <div>
                                                    <label class="form-label small text-muted mb-0" for="logo_height">H</label>
                                                    <input type="number" class="form-control" id="logo_height" name="logo_height" min="128" max="1600" value="512">
                                                </div>
                                            </div>
                                            <small class="text-muted d-md-none d-block mt-2">Use similar W/H for squares; stretch width for banners.</small>
                                            <small class="text-muted d-none d-md-block mt-2 mb-0">Use similar values for squares, different for banners.</small>
                                        </td>
                                    </tr>
                                    <tr class="logo-gen-row-tall">
                                        <th scope="row" class="text-nowrap align-top pt-3">
                                            Font &amp; size
                                            <span class="fw-normal text-muted d-block small">TTF/OTF in fonts/</span>
                                        </th>
                                        <td>
                                            <div class="row g-3 align-items-end">
                                                <div class="col-12">
                                                    <label class="form-label small text-muted mb-1" for="logo_font">Typeface</label>
                                                    <select class="form-select" id="logo_font" name="logo_font" aria-label="Font file">
                                                        <?= $fontOptions ?>
                                                    </select>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <label class="form-label small text-muted mb-1" for="logo_font_size">Size (px)</label>
                                                    <input type="number" class="form-control" id="logo_font_size" name="logo_font_size" min="12" max="400" value="<?= $defaultFontSize ?>"
                                                        aria-describedby="logoFontSizeHint">
                                                </div>
                                                <div class="col-12 col-sm-8">
                                                    <label class="form-label small text-muted mb-1" for="logo_font_size_range" id="logoFontSizeRangeLabel">Scale</label>
                                                    <input type="range" class="form-range" id="logo_font_size_range" min="12" max="400" value="<?= $defaultFontSize ?>"
                                                        aria-labelledby="logoFontSizeRangeLabel" aria-describedby="logoFontSizeHint">
                                                    <span id="logoFontSizeHint" class="small text-muted">12–400 px — use the slider or type a value (max size when Autofit is on)</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="logo-gen-row-tall">
                                        <th scope="row" class="text-nowrap align-top pt-3">
                                            Autofit &amp; nudge
                                            <span class="fw-normal text-muted d-block small">Wrap + optional scale</span>
                                        </th>
                                        <td>
                                            <div class="d-flex flex-wrap gap-4 align-items-center mb-2">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" id="logoAutofit" name="logo_autofit" value="1">
                                                    <label class="form-check-label" for="logoAutofit">Autofit text in canvas</label>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <div class="d-flex align-items-center gap-1">
                                                    <label class="small text-muted text-nowrap mb-0" for="logo_text_offset_x">Nudge X (px)</label>
                                                    <input type="number" class="form-control form-control-sm" id="logo_text_offset_x" name="logo_text_offset_x" value="0" min="-400" max="400" style="width:5.25rem;" title="Horizontal shift; positive = right">
                                                </div>
                                                <div class="d-flex align-items-center gap-1">
                                                    <label class="small text-muted text-nowrap mb-0" for="logo_text_offset_y">Y (px)</label>
                                                    <input type="number" class="form-control form-control-sm" id="logo_text_offset_y" name="logo_text_offset_y" value="0" min="-400" max="400" style="width:5.25rem;" title="Vertical shift; positive = down">
                                                </div>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" id="logoOffsetReset">Reset nudge</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="logo-gen-options-col">
                        <div class="table-responsive">
                            <table class="table logo-gen-table table-hover align-middle mb-0">
                                <tbody>
                                    <tr class="logo-gen-section-header">
                                        <td colspan="2" class="py-2 small text-uppercase fw-semibold text-body-secondary">Look</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-nowrap">Export format</th>
                                        <td>
                                            <div class="btn-group flex-wrap" role="group" aria-label="Output image format">
                                                <input type="radio" class="btn-check" name="logo_format" id="logo_format_png" value="png" autocomplete="off" checked>
                                                <label class="btn btn-outline-primary btn-sm" for="logo_format_png">PNG <span class="text-body-secondary fw-normal small">(transparency)</span></label>
                                                <input type="radio" class="btn-check" name="logo_format" id="logo_format_webp" value="webp" autocomplete="off">
                                                <label class="btn btn-outline-primary btn-sm" for="logo_format_webp">WebP</label>
                                                <input type="radio" class="btn-check" name="logo_format" id="logo_format_jpeg" value="jpeg" autocomplete="off">
                                                <label class="btn btn-outline-primary btn-sm" for="logo_format_jpeg">JPEG <span class="text-body-secondary fw-normal small">(opaque)</span></label>
                                            </div>
                                            <input type="hidden" name="logo_jpeg_quality" value="90">
                                            <small class="text-muted d-block mt-1 mb-0">JPEG flattens onto the background color. WebP/PNG keep transparency where supported.</small>
                                        </td>
                                    </tr>
                                    <tr class="logo-gen-row-tall">
                                        <th scope="row" class="text-nowrap align-top pt-3">Border</th>
                                        <td>
                                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" id="logoBorderEnabled" name="logo_border_enabled" value="1">
                                                    <label class="form-check-label" for="logoBorderEnabled">Enable border</label>
                                                </div>
                                                <div id="logoBorderWidthWrap" class="d-flex flex-wrap align-items-center gap-1 d-none">
                                                    <label class="small text-muted text-nowrap mb-0" for="logo_border">Width (px)</label>
                                                    <input type="number" class="form-control" id="logo_border" name="logo_border" min="0" max="24" value="0"
                                                        aria-describedby="logoBorderHint" style="max-width: 7rem;" disabled>
                                                </div>
                                            </div>
                                            <div id="logoBorderColorWrap" class="mt-2 d-none">
                                                <label class="form-label small mb-1" for="logo_border_color">Color</label>
                                                <div class="d-flex gap-1 align-items-stretch" style="max-width: 16rem;">
                                                    <input type="color" class="form-control form-control-color flex-grow-1" id="logo_border_color" name="logo_border_color" value="#ffffff" title="Border color" disabled>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm logo-color-random px-2" data-target="logo_border_color" title="Random color" disabled><?= icon('shuffle', 0.9) ?></button>
                                                </div>
                                            </div>
                                            <small id="logoBorderHint" class="text-muted d-block mt-1 mb-0">0–24 px when enabled. <strong>Color</strong> appears when the border is enabled.</small>
                                        </td>
                                    </tr>
                                    <tr class="logo-gen-row-tall">
                                        <th scope="row" class="text-nowrap align-top pt-3">Background</th>
                                        <td>
                                            <input type="hidden" name="logo_style" id="logo_style" value="gradient">
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="logoGradientSwitch" checked
                                                    aria-describedby="logoBgGradientHint">
                                                <label class="form-check-label" for="logoGradientSwitch">Gradient background</label>
                                            </div>
                                            <small id="logoBgGradientHint" class="text-muted d-block mb-2">Solid fill uses <strong>Background</strong> only. Turn on for a blend with <strong>Accent</strong>.</small>
                                            <div class="row row-cols-1 row-cols-sm-2 g-2 mt-2 align-items-end">
                                                <div class="col">
                                                    <label class="form-label small mb-1" for="logo_bg_color">Background</label>
                                                    <div class="d-flex gap-1 align-items-stretch">
                                                        <input type="color" class="form-control form-control-color flex-grow-1" id="logo_bg_color" name="logo_bg_color" value="#000000" title="Background">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm logo-color-random px-2" data-target="logo_bg_color" title="Random color"><?= icon('shuffle', 0.9) ?></button>
                                                    </div>
                                                </div>
                                                <div class="col" id="logoBgAccentWrap">
                                                    <label class="form-label small mb-1" for="logo_accent_color">Accent</label>
                                                    <div class="d-flex gap-1 align-items-stretch">
                                                        <input type="color" class="form-control form-control-color flex-grow-1" id="logo_accent_color" name="logo_accent_color" value="#1d4ed8" title="Gradient accent">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm logo-color-random px-2" data-target="logo_accent_color" title="Random color"><?= icon('shuffle', 0.9) ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="logoBgGradientStrengthWrap" class="mt-2">
                                                <label class="form-label small mb-1 d-flex flex-wrap align-items-baseline gap-2" for="logo_bg_gradient_strength">
                                                    Gradient blend
                                                    <span class="text-muted fw-normal" id="logo_bg_gradient_strength_val">100%</span>
                                                </label>
                                                <input type="range" class="form-range" name="logo_bg_gradient_strength" id="logo_bg_gradient_strength"
                                                    min="0" max="100" value="100"
                                                    aria-valuemin="0" aria-valuemax="100" aria-valuenow="100">
                                                <small class="text-muted d-block">0 = flat (background color only) · 100 = full blend to accent at the bottom.</small>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="logo-gen-row-tall">
                                        <th scope="row" class="text-nowrap align-top pt-3">Text fill</th>
                                        <td>
                                            <input type="hidden" name="logo_text_style" id="logo_text_style" value="solid">
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="logoTextGradientSwitch"
                                                    aria-describedby="logoTextGradientHint">
                                                <label class="form-check-label" for="logoTextGradientSwitch">Gradient text fill</label>
                                            </div>
                                            <small id="logoTextGradientHint" class="text-muted d-block mb-2">Solid uses <strong>Text</strong> only. Turn on to blend with <strong>Text accent</strong> (top → bottom).</small>
                                            <div class="row row-cols-1 row-cols-sm-2 g-2 mt-2 align-items-end">
                                                <div class="col">
                                                    <label class="form-label small mb-1" for="logo_text_color">Text</label>
                                                    <div class="d-flex gap-1 align-items-stretch">
                                                        <input type="color" class="form-control form-control-color flex-grow-1" id="logo_text_color" name="logo_text_color" value="#ffffff" title="Text color (gradient start when text fill is gradient)">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm logo-color-random px-2" data-target="logo_text_color" title="Random color"><?= icon('shuffle', 0.9) ?></button>
                                                    </div>
                                                </div>
                                                <div class="col d-none" id="logoTextAccentWrap">
                                                    <label class="form-label small mb-1" for="logo_text_accent_color">Text accent</label>
                                                    <div class="d-flex gap-1 align-items-stretch">
                                                        <input type="color" class="form-control form-control-color flex-grow-1" id="logo_text_accent_color" name="logo_text_accent_color" value="#94a3b8" title="Text gradient end" disabled>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm logo-color-random px-2" data-target="logo_text_accent_color" title="Random color" disabled><?= icon('shuffle', 0.9) ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="logoTextGradientStrengthWrap" class="mt-2 d-none">
                                                <label class="form-label small mb-1 d-flex flex-wrap align-items-baseline gap-2" for="logo_text_gradient_strength">
                                                    Gradient blend
                                                    <span class="text-muted fw-normal" id="logo_text_gradient_strength_val">100%</span>
                                                </label>
                                                <input type="range" class="form-range" name="logo_text_gradient_strength" id="logo_text_gradient_strength"
                                                    min="0" max="100" value="100" disabled
                                                    aria-valuemin="0" aria-valuemax="100" aria-valuenow="100">
                                                <small class="text-muted d-block">0 = solid text color · 100 = full blend to text accent at the bottom.</small>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-nowrap">Mask shape</th>
                                        <td>
                                            <div class="btn-group flex-wrap" role="group" aria-label="Logo outer shape">
                                                <input type="radio" class="btn-check" name="logo_shape" id="logo_shape_rounded" value="rounded" autocomplete="off" checked>
                                                <label class="btn btn-outline-primary btn-sm" for="logo_shape_rounded">Rounded square</label>
                                                <input type="radio" class="btn-check" name="logo_shape" id="logo_shape_rectangle" value="rectangle" autocomplete="off">
                                                <label class="btn btn-outline-primary btn-sm" for="logo_shape_rectangle">Rectangle</label>
                                                <input type="radio" class="btn-check" name="logo_shape" id="logo_shape_circle" value="circle" autocomplete="off">
                                                <label class="btn btn-outline-primary btn-sm" for="logo_shape_circle">Circle</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="logo-gen-section-header">
                                        <td colspan="2" class="py-2 small text-uppercase fw-semibold text-body-secondary">Text transform</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-nowrap align-middle">Case &amp; initials</th>
                                        <td>
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
                    </div>
                </div>

                <div class="mb-4">
                    <small id="logoHintText" class="text-muted">Tip: use <strong>Initials badge</strong> for circular avatars; <strong>Banner</strong> for wide headers.</small>
                </div>

                <label class="form-label mb-2"><strong>Live preview</strong></label>
                <div class="responseDiv border rounded p-4 logo-live-preview-host" id="liveLogoPreview" style="min-height: 240px;">
                    <div class="text-muted" style="opacity: 0.75;">Loading preview…</div>
                </div>
            </form>
        </div>
    </div>
</div>