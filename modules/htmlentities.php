<div id="htmlentities" class="content">
    <?php $htmlInputVal = isset($_POST['htmlentities']) ? htmlspecialchars($_POST['htmlentities']) : ""; ?>

    <div class="card card-primary">
        <h1 class="card-header">🧩 HTML Entities</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong>ℹ️ Convert & Decode</strong><br>
                Paste plain text or HTML-encoded strings to get the raw input, encoded entities, and decoded output side by side.
            </div>

            <form class="form" action="gen.php" method="POST" id="htmlentities" data-action="htmlentities">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="htmlentitiesInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input Text</strong></label>
                        <textarea name="htmlentities" id="htmlentitiesInput" class="form-control" style="min-height: 320px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;"
                            placeholder="Enter text or HTML-encoded string to convert..." required><?= $htmlInputVal ?></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Converted Output</strong></label>
                        <div class="responseDiv flex-grow-1" id="htmlentitiesresponse" style="border: 2px solid #495057; padding: 20px; min-height: 320px; max-height: 540px; overflow-y: auto; background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(32, 201, 151, 0.08) 100%); border-radius: 0.5rem; font-family: monospace; font-size: 0.95rem; white-space: pre-wrap; word-break: break-word;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 120px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">🧩</div>
                                <div>Encoded and decoded outputs will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <?= submitBtn("htmlentities", "action", "🔁 Convert", "arrow-repeat", "lg") ?>
                </div>
            </form>
        </div>
    </div>

</div>