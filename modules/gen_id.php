<div id="gen_id" class="content">
    <?php
    $idType = $_POST['idtype'] ?? 'uuid4';
    $idQty = isset($_POST['idqty']) ? (int) $_POST['idqty'] : 5;
    $idQty = max(1, min(500, $idQty));
    $nanoLen = isset($_POST['nanoid_length']) ? (int) $_POST['nanoid_length'] : 21;
    $nanoLen = max(6, min(128, $nanoLen));
    ?>
    <div class="card card-primary">
        <h1 class="card-header">🆔 ID Generator</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                Generate UUIDv4, ULID, or NanoID values in bulk.
            </div>
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

                        <div class="alert alert-secondary mb-0" role="status">
                            <strong>Key format:</strong>
                            <span id="idFormatPreview" class="ms-1" style="font-family: monospace;"></span>
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
    var formatPreviewEl = document.getElementById('idFormatPreview');
    if (!idTypeEl || !nanoWrapEl || !formatPreviewEl) return;

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

    function updateFormatPreview() {
        var preview = '';
        if (idTypeEl.value === 'uuid4') {
            preview = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx';
        } else if (idTypeEl.value === 'ulid') {
            preview = '26 chars (Crockford Base32): ' + applyCase('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        } else {
            var len = clampNanoLen(nanoLenEl ? nanoLenEl.value : 21);
            preview = len + ' chars (URL-safe Base64): ' + applyCase('V1StGXR8_Z5jdHi6B-myT');
        }

        if (idTypeEl.value === 'uuid4') {
            formatPreviewEl.textContent = applyCase(preview);
            return;
        }

        formatPreviewEl.textContent = preview;
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

    toggleNanoLength();
    updateFormatPreview();
})();
</script>
