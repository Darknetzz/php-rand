<div id="brainfuck" class="content">
    <div class="card card-primary">
        <h1 class="card-header">🧠 Brainfuck Converter</h1>
        <div class="card card-body">
            <div class="alert alert-info mb-4">
                <strong>ℹ️ About Brainfuck</strong><br>
                Brainfuck is an esoteric programming language with only 8 commands: <code>&gt;</code> <code>&lt;</code> <code>+</code> <code>-</code> <code>.</code> <code>,</code> <code>[</code> <code>]</code>.
                This tool converts between text and Brainfuck code, or executes Brainfuck code.
            </div>

            <form class="form" action="gen.php" method="POST" data-action="brainfuck">
                <?php
                  $brainfuckInput = $_POST['brainfuck'] ?? '';
                  $brainfuckMode = $_POST['mode'] ?? 'text2bf';
                ?>

                <!-- Mode Selection -->
                <div class="card border-primary mb-4" style="background-color: rgba(13, 110, 253, 0.05);">
                    <div class="card-header bg-primary text-white">
                        <strong>⚙️ Conversion Mode</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="mode" id="modeText2Bf" value="text2bf" <?= $brainfuckMode === 'text2bf' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="modeText2Bf">
                                <strong>Text → Brainfuck</strong><br>
                                <small class="text-muted">Convert text to Brainfuck code that outputs that text</small>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="mode" id="modeBf2Text" value="bf2text" <?= $brainfuckMode === 'bf2text' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="modeBf2Text">
                                <strong>Brainfuck → Text</strong><br>
                                <small class="text-muted">Execute Brainfuck code and show the output</small>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Input Section -->
                <div class="mb-4">
                    <label for="brainfuckInput" class="form-label mb-3">
                        <strong style="font-size: 1.1rem;">
                            <span id="inputLabel">Input Text</span>
                        </strong>
                    </label>
                    <textarea name="brainfuck" class="form-control" id="brainfuckInput" 
                        style="min-height: 300px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;" 
                        placeholder="Enter text or Brainfuck code..." required><?= htmlspecialchars($brainfuckInput) ?></textarea>
                </div>

                <!-- Action Button -->
                <div class="d-flex gap-3 mb-4">
                    <?= submitBtn("brainfuck", "action", "🧠 Convert", "arrow-repeat", "lg") ?>
                </div>

                <!-- Output Section -->
                <div class="responseDiv" id="brainfuckresponse" style="border: 2px solid #495057; padding: 20px; min-height: 200px; max-height: 500px; overflow-y: auto; background: linear-gradient(135deg, rgba(102, 16, 242, 0.1) 0%, rgba(108, 92, 231, 0.05) 100%); border-radius: 0.5rem; font-family: monospace; font-size: 0.95rem; white-space: pre-wrap; word-break: break-all;">
                    <div style="opacity: 0.5; text-align: center; padding-top: 50px;">
                        <div style="font-size: 3rem; margin-bottom: 10px;">🧠</div>
                        <div>Conversion result will appear here...</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Update input label based on mode
$('input[name="mode"]').change(function() {
    const mode = $(this).val();
    const label = mode === 'text2bf' ? 'Input Text' : 'Brainfuck Code';
    $('#inputLabel').text(label);
    const placeholder = mode === 'text2bf' ? 'Enter text to convert to Brainfuck...' : 'Enter Brainfuck code to execute...';
    $('#brainfuckInput').attr('placeholder', placeholder);
});
</script>
