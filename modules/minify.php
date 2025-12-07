<div id="minify" class="content">
    <?php $minifyInputVal = isset($_POST['input']) ? htmlspecialchars($_POST['input']) : ""; ?>
    <?php $minifyTypeVal = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : "js"; ?>

    <div class="card card-primary">
        <h1 class="card-header">⚡ Minify</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong>ℹ️ Code Compression</strong><br>
                <span style="display: inline-block; margin-top: 4px;">Minify HTML, CSS, and JavaScript to reduce file size and improve load times.</span>
            </div>

            <form id="minifyForm" class="form" action="gen.php" method="POST" data-action="minify">
                <input type="hidden" name="tool" value="minify">
                <input type="hidden" name="responsetype" value="text">
                
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="minifyTextarea" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input Code</strong></label>
                        <textarea name="input" id="minifyTextarea" class="form-control" placeholder="Paste your HTML, CSS, or JavaScript here..." style="font-family: monospace; resize: vertical; font-size: 0.95rem; border: 2px solid #495057; min-height: 380px;" required><?= $minifyInputVal ?></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Minified Output</strong></label>
                        <div id="minifyOutput" class="flex-grow-1 responseDiv" style="border: 2px solid #495057; padding: 20px; min-height: 380px; max-height: 580px; overflow-y: auto; background: linear-gradient(135deg, rgba(255, 193, 7, 0.12) 0%, rgba(255, 87, 34, 0.08) 100%); border-radius: 0.5rem; font-family: monospace; font-size: 0.9rem; white-space: pre-wrap; word-break: break-word; box-shadow: 0 6px 16px rgba(0,0,0,0.25);" data-formid="minifyForm">
                            <div style="opacity: 0.5; text-align: center; padding-top: 150px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">⚡</div>
                                <div>Minified code will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label"><strong>Code Type</strong></label>
                        <select name="type" id="minifyType" class="form-select form-select-lg" style="font-family: monospace; border: 2px solid #ffc107;">
                            <option value="js" <?= $minifyTypeVal === 'js' ? 'selected' : '' ?>>JavaScript</option>
                            <option value="html" <?= $minifyTypeVal === 'html' ? 'selected' : '' ?>>HTML</option>
                            <option value="css" <?= $minifyTypeVal === 'css' ? 'selected' : '' ?>>CSS</option>
                        </select>
                        <div class="form-text">Select the code type to minify</div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <?= submitBtn("minify", "tool", "⚡ Minify Code", "file-text-fill", "lg") ?>
                </div>
            </form>
        </div>
    </div>
</div>