<div id="gen_id" class="content">
    <?php
    $idType = $_POST['idtype'] ?? 'uuid4';
    $idQty = isset($_POST['idqty']) ? (int) $_POST['idqty'] : 5;
    $idQty = max(1, min(500, $idQty));
    $nanoLen = isset($_POST['nanoid_length']) ? (int) $_POST['nanoid_length'] : 21;
    $nanoLen = max(6, min(128, $nanoLen));
    ?>
    <div class="alert alert-info mb-4">
        Generate UUIDv4, ULID, or NanoID values in bulk.
    </div>
    <div class="card card-primary">
        <h1 class="card-header">🆔 ID Generator</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="genid" data-action="genid">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="idType" class="form-label"><strong>ID Type</strong></label>
                        <select name="idtype" id="idType" class="form-select form-select-lg mb-3">
                            <option value="uuid4" <?= $idType === 'uuid4' ? 'selected' : '' ?>>UUIDv4</option>
                            <option value="ulid" <?= $idType === 'ulid' ? 'selected' : '' ?>>ULID</option>
                            <option value="nanoid" <?= $idType === 'nanoid' ? 'selected' : '' ?>>NanoID</option>
                        </select>

                        <label for="idQty" class="form-label"><strong>Quantity</strong></label>
                        <input type="number" min="1" max="500" name="idqty" id="idQty" class="form-control form-control-lg mb-3" value="<?= htmlspecialchars((string) $idQty) ?>">

                        <div id="nanoidLengthWrap">
                            <label for="nanoidLength" class="form-label"><strong>NanoID length</strong></label>
                            <input type="number" min="6" max="128" name="nanoid_length" id="nanoidLength" class="form-control form-control-lg mb-3" value="<?= htmlspecialchars((string) $nanoLen) ?>">
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="idUppercase" name="id_uppercase" value="1" <?= !empty($_POST['id_uppercase']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="idUppercase">Uppercase output</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="idShowPattern">
                            <label class="form-check-label" for="idShowPattern">Show pattern notation</label>
                        </div>

                        <div class="alert alert-secondary mb-0" role="status">
                            <strong>Key format:</strong>
                            <span id="idFormatPreview" class="ms-1" style="font-family: monospace;"></span>
                            <div id="idPatternPreviewWrap" class="small text-muted mt-2" style="display: none;">
                                <strong>Pattern:</strong>
                                <span id="idPatternPreview" class="ms-1" style="font-family: monospace;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong>Generated IDs</strong></label>
                        <div class="responseDiv flex-grow-1" id="genidresponse" style="border: 2px solid #495057; padding: 20px; min-height: 320px; max-height: 520px; overflow-y: auto; border-radius: 0.5rem; font-family: monospace; white-space: pre-wrap; word-break: break-word;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 120px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">🆔</div>
                                <div>Generated IDs will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
                <?= submitBtn("genid", "action", "Generate IDs", "hash", "lg") ?>
            </form>
        </div>
    </div>
</div>
<script>
(function () {
    var idTypeEl = document.getElementById('idType');
    var nanoWrapEl = document.getElementById('nanoidLengthWrap');
    var nanoLenEl = document.getElementById('nanoidLength');
    var uppercaseEl = document.getElementById('idUppercase');
    var showPatternEl = document.getElementById('idShowPattern');
    var formatPreviewEl = document.getElementById('idFormatPreview');
    var patternWrapEl = document.getElementById('idPatternPreviewWrap');
    var patternPreviewEl = document.getElementById('idPatternPreview');
    if (!idTypeEl || !nanoWrapEl || !formatPreviewEl || !patternWrapEl || !patternPreviewEl) return;

    function toggleNanoLength() {
        nanoWrapEl.style.display = idTypeEl.value === 'nanoid' ? '' : 'none';
    }

    function clampNanoLen(value) {
        var n = parseInt(value, 10);
        if (isNaN(n)) return 21;
        return Math.max(6, Math.min(128, n));
    }

    function applyCase(text) {
        return uppercaseEl && uppercaseEl.checked ? text.toUpperCase() : text.toLowerCase();
    }

    function getPreviewParts() {
        var sample = '';
        var pattern = '';

        if (idTypeEl.value === 'uuid4') {
            sample = '36 chars (8-4-4-4-12 hex, RFC 4122 v4): ' + applyCase('a8213467-a34d-480c-9cc9-3bd359572d30');
            pattern = uppercaseEl && uppercaseEl.checked
                ? '[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}'
                : '[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}';
        } else if (idTypeEl.value === 'ulid') {
            sample = '26 chars (Crockford Base32): ' + applyCase('01ARZ3NDEKTSV4RRFFQ69G5FAV');
            pattern = uppercaseEl && uppercaseEl.checked
                ? '[0-9A-HJKMNP-TV-Z]{26}'
                : '[0-9a-hjkmnp-tv-z]{26}';
        } else {
            var len = clampNanoLen(nanoLenEl ? nanoLenEl.value : 21);
            sample = len + ' chars (URL-safe Base64): ' + applyCase('V1StGXR8_Z5jdHi6B-myT');
            pattern = uppercaseEl && uppercaseEl.checked
                ? '[A-Z0-9_-]{' + len + '}'
                : '[A-Za-z0-9_-]{' + len + '}';
        }

        return { sample: sample, pattern: pattern };
    }

    function updateFormatPreview() {
        var parts = getPreviewParts();
        formatPreviewEl.textContent = parts.sample;

        var showPattern = !!(showPatternEl && showPatternEl.checked);
        patternWrapEl.style.display = showPattern ? '' : 'none';
        patternPreviewEl.textContent = showPattern ? parts.pattern : '';
    }

    idTypeEl.addEventListener('change', function () {
        toggleNanoLength();
        updateFormatPreview();
    });

    if (nanoLenEl) {
        nanoLenEl.addEventListener('input', updateFormatPreview);
        nanoLenEl.addEventListener('change', updateFormatPreview);
    }

    if (uppercaseEl) {
        uppercaseEl.addEventListener('change', updateFormatPreview);
    }

    if (showPatternEl) {
        showPatternEl.addEventListener('change', updateFormatPreview);
    }

    toggleNanoLength();
    updateFormatPreview();
})();
</script>
