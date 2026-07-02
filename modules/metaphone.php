<div id="metaphone" class="content">
    <?php $metaphoneInputVal = isset($_POST['metaphone']) ? htmlspecialchars($_POST['metaphone']) : ""; ?>

    <div class="card card-primary">
        <h1 class="card-header">🔊 Metaphone</h1>
        <div class="card-body">
            <p class="text-muted mb-4">Metaphone generates phonetic keys for words, useful for fuzzy matching and sound-alike searches.</p>
            <form class="form" action="gen.php" method="POST" id="metaphoneForm" data-action="metaphone">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="metaphoneInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input Text</strong></label>
                        <textarea class="form-control" id="metaphoneInput" name="metaphone" placeholder="Enter text to convert..." style="font-family: monospace; resize: vertical; font-size: 0.95rem; border: 2px solid #495057; min-height: 300px;" required><?= $metaphoneInputVal ?></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Metaphone Key</strong></label>
                        <div class="responseDiv flex-grow-1" id="metaphoneresponse" style="border: 2px solid #495057; padding: 20px; min-height: 300px; max-height: 500px; overflow-y: auto; background: linear-gradient(135deg, rgba(32, 201, 151, 0.12) 0%, rgba(13, 202, 240, 0.08) 100%); border-radius: 0.5rem; font-family: monospace; white-space: pre-wrap; word-break: break-word; box-shadow: 0 6px 16px rgba(0,0,0,0.25);">
                            <div style="opacity: 0.5; text-align: center; padding-top: 110px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">🔊</div>
                                <div>Phonetic key will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <?= submitBtn("metaphone", "action", "🔊 Generate Key", "arrow-repeat", "lg") ?>
                </div>
            </form>

        </div>
    </div>
</div>