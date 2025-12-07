<div id="urlencoding" class="content">
    <?php $urlInputVal = isset($_POST['urlencode']) ? htmlspecialchars($_POST['urlencode']) : ""; ?>

    <div class="card card-primary">
        <h1 class="card-header">🔗 URL Encoding</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong>ℹ️ Encode & Decode</strong><br>
                Paste text or URL-encoded data to see the original, encoded, and decoded forms side by side.
            </div>

            <form class="form" action="gen.php" method="POST" id="urlencode" data-action="urlencode">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="urlencodeInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input Text</strong></label>
                        <textarea name="urlencode" id="urlencodeInput" class="form-control" style="min-height: 300px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;"
                            placeholder="Enter text or URL-encoded string..." required><?= $urlInputVal ?></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Converted Output</strong></label>
                        <div class="responseDiv flex-grow-1" id="urlencoderesponse" style="border: 2px solid #495057; padding: 20px; min-height: 300px; max-height: 520px; overflow-y: auto; background: linear-gradient(135deg, rgba(32, 201, 151, 0.12) 0%, rgba(13, 110, 253, 0.08) 100%); border-radius: 0.5rem; font-family: monospace; font-size: 0.95rem; white-space: pre-wrap; word-break: break-word;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 110px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">🔗</div>
                                <div>Original, encoded, and decoded values will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <?= submitBtn("urlencode", "action", "🔁 Convert", "arrow-repeat", "lg") ?>
                </div>
            </form>
        </div>
    </div>

</div>
