<div id="rot" class="content">
    <div class="card card-primary">
        <h1 class="card-header">🔄 ROT Cipher</h1>
        <div class="card card-body">
            <div class="alert alert-info mb-4">
                <strong>ℹ️ About ROT Ciphers</strong><br>
                ROT (rotation cipher) shifts each letter in the text by a fixed number of positions in the alphabet. ROT13 is the most common variant.
            </div>

            <form class="form" action="gen.php" method="POST" data-action="rot">
                <?php
                  if (isset($_POST['rot'])) {
                    $rot = $_POST['rot'];
                    $rotations = $_POST['rotations'];
                  }
                  else {
                    $rot = NULL;
                    $rotations = NULL;
                  }
                ?>

                <!-- Input/Output Section -->
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="rotInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input Text</strong></label>
                        <textarea name="rot" class="form-control" id="rotInput" style="min-height: 300px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;" placeholder="Enter text to rotate..."><?= htmlspecialchars($rot ?? '') ?></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Rotated Output</strong></label>
                        <div class="responseDiv flex-grow-1" id="rotresponse" style="border: 2px solid #495057; padding: 20px; min-height: 300px; max-height: 500px; overflow-y: auto; background: linear-gradient(135deg, rgba(102, 16, 242, 0.1) 0%, rgba(108, 92, 231, 0.05) 100%); border-radius: 0.5rem; font-family: monospace; font-size: 0.95rem; white-space: pre-wrap; word-break: break-all;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 100px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">🔄</div>
                                <div>Rotated text will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuration -->
                <div class="card border-primary mb-4" style="background-color: rgba(13, 110, 253, 0.05);">
                    <div class="card-header bg-primary text-white">
                        <strong>⚙️ Rotation Settings</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="rotbruteforce" role="switch" checked>
                            <input type="hidden" name="bruteforce" id="bruteforceInput" value="1">
                            <label class="form-check-label" for="rotbruteforce">
                                <strong>Bruteforce Mode</strong><br>
                                <small class="text-muted">Show all possible rotations (26 results)</small>
                            </label>
                        </div>

                        <div id="rotationsContainer" style="display: none;" class="mt-3">
                            <label for="rotations" class="form-label"><strong>Number of Rotations</strong></label>
                            <input type="number" name="rotations" id="rotations" class="form-control form-control-lg" value="<?= htmlspecialchars($rotations ?? '13') ?>" placeholder="13 (standard ROT13)" style="max-width: 300px; border: 2px solid #6610f2;">
                            <small class="form-text">Enter a value between 1-25</small>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="d-flex gap-3">
                    <?= submitBtn("rot", "action", "🔄 Generate ROT", "arrow-repeat", "lg") ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle ROT bruteforce
$("#rotbruteforce").change(function() {
    if ($(this).is(":checked")) {
        $("#rotationsContainer").fadeOut();
        $("#bruteforceInput").val("1");
    } else {
        $("#rotationsContainer").fadeIn();
        $("#bruteforceInput").val("0");
    }
});
</script>