<div id="serialization" class="content">
    <?php
        $serializationInputVal = isset($_POST['input']) ? htmlspecialchars($_POST['input'] ?? '') : "";
        $serializationTypeVal  = isset($_POST['type']) ? htmlspecialchars($_POST['type'] ?? 'JSON') : "JSON";
        $stripCommentsChecked  = !empty($_POST['stripcomments']) ? 'checked' : '';
    ?>

    <div class="card card-primary">
        <h1 class="card-header">🧾 Serialization</h1>
        <div class="card card-body">
            <div class="alert alert-info mb-4">
                <strong>ℹ️ Auto-detect & Convert</strong><br>
                Paste JSON, YAML, or XML on the left and choose your target format. Comments starting with # or // can be stripped.
            </div>

            <form class="form" action="gen.php" method="POST" id="serializationForm" data-action="serialization" data-responsetype="text">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Input</strong></label>
                        <textarea name="input" id="serializationInput" class="form-control" style="min-height: 320px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;" placeholder="Paste JSON, YAML, or XML here..." required><?= $serializationInputVal ?></textarea>
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="stripcomments" id="stripcomments" value="1" <?= $stripCommentsChecked ?>>
                            <label class="form-check-label" for="stripcomments">Remove comment lines (# or //)</label>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Output</strong></label>
                        <div class="copyable-content flex-grow-1 d-flex flex-column" style="min-height: 320px; padding: 0; background: linear-gradient(135deg, rgba(0, 184, 255, 0.12) 0%, rgba(32, 201, 151, 0.1) 100%); border: 2px solid #495057; border-radius: 0.5rem; box-shadow: 0 6px 16px rgba(0,0,0,0.25);">
                        <div class="responseDiv copyable-body flex-grow-1" id="serializationresponse" style="padding: 20px; min-height: 200px; max-height: 500px; overflow-y: auto; background: transparent; border: none; border-radius: 0; box-shadow: none; font-family: monospace; font-size: 0.95rem; white-space: pre-wrap; word-break: break-word; user-select: all;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 120px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">🧾</div>
                                <div>Converted output will appear here...</div>
                            </div>
                        </div>
                        <div class="copyable-actions" style="padding: 8px 14px; border-top: 1px solid rgba(255,255,255,0.12);">
                        <button type="button" class="btn btn-sm btn-outline-light" style="width: 100%; border: 1px solid #e9ecef;" onclick="copyToClipboard('serializationresponse', this)"><?= icon("files") ?> Copy Output</button>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label"><strong>Output type</strong></label>
                        <select class="form-select form-select-lg" name="type" required style="font-family: monospace; border: 2px solid #0dcaf0;">
                            <option value="JSON" <?= $serializationTypeVal === 'JSON' ? 'selected' : '' ?>>JSON</option>
                            <option value="XML" <?= $serializationTypeVal === 'XML' ? 'selected' : '' ?>>XML (beta)</option>
                            <option value="YAML" <?= $serializationTypeVal === 'YAML' ? 'selected' : '' ?>>YAML (beta)</option>
                        </select>
                        <div class="form-text">Conversion attempts auto-detect input format</div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <?= submitBtn("serialization", "action", "🔁 Convert", "arrow-repeat", "lg") ?>
                </div>
            </form>
        </div>
    </div>

</div>