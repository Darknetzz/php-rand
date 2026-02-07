<!--
// ─────────────────────────────────────────────────────────────────────────────────────────────── #
//                                           REGEX TESTER                                          #
// ─────────────────────────────────────────────────────────────────────────────────────────────── #
-->
<div id="regex" class="content">
    <div class="card card-primary">
        <h1 class="card-header">🔍 Regex Tester</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong>ℹ️ About Regex</strong><br>
                Test and debug regular expressions in real-time. See matches, groups, and replacements instantly. Supports PHP regex syntax (PCRE).
            </div>

            <form class="form" action="gen.php" method="POST" id="regexForm" data-action="regex">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="regexPattern" class="form-label mb-3"><strong style="font-size: 1.1rem;">Regular Expression Pattern</strong></label>
                        <textarea name="pattern" class="form-control" id="regexPattern" style="min-height: 120px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;" placeholder="/your pattern here/" required><?php if (isset($_POST['pattern'])) echo htmlspecialchars($_POST['pattern'] ?? ''); ?></textarea>
                        <div class="form-text mt-2">Enter your regex pattern (with or without delimiters like /pattern/)</div>
                        
                        <div class="mt-3">
                            <label for="regexTestString" class="form-label mb-3"><strong style="font-size: 1.1rem;">Test String</strong></label>
                            <textarea name="teststring" class="form-control" id="regexTestString" style="min-height: 200px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;" placeholder="Enter the text you want to test against the pattern..." required><?php if (isset($_POST['teststring'])) echo htmlspecialchars($_POST['teststring'] ?? ''); ?></textarea>
                        </div>

                        <div class="mt-3">
                            <label for="regexReplace" class="form-label mb-3"><strong style="font-size: 1.1rem;">Replacement (Optional)</strong></label>
                            <input type="text" name="replacement" class="form-control" id="regexReplace" placeholder="Enter replacement string (leave empty to only match)" style="font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;" value="<?php if (isset($_POST['replacement'])) echo htmlspecialchars($_POST['replacement'] ?? ''); ?>">
                            <div class="form-text mt-2">Optional: Replacement string for preg_replace (use $1, $2 for groups)</div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Results</strong></label>
                        <div class="responseDiv flex-grow-1" id="regexresponse" style="border: 2px solid #495057; padding: 20px; min-height: 400px; max-height: 600px; overflow-y: auto; background: linear-gradient(135deg, rgba(32, 201, 151, 0.1) 0%, rgba(13, 110, 253, 0.05) 100%); border-radius: 0.5rem; font-family: monospace; font-size: 0.9rem; white-space: pre-wrap; word-break: break-word;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 150px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">🔍</div>
                                <div>Regex test results will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regex Options -->
                <div class="card border-success mb-4" style="background-color: rgba(25, 135, 84, 0.05);">
                    <div class="card-header bg-success text-dark">
                        <strong>⚙️ Regex Options</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="caseless" id="regexCaseless" value="1" <?= (isset($_POST['caseless']) && $_POST['caseless'] == 1) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="regexCaseless">
                                        <strong>Case Insensitive (i)</strong>
                                    </label>
                                    <div class="form-text">Enable case-insensitive matching</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="multiline" id="regexMultiline" value="1" <?= (isset($_POST['multiline']) && $_POST['multiline'] == 1) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="regexMultiline">
                                        <strong>Multiline (m)</strong>
                                    </label>
                                    <div class="form-text">^ and $ match line breaks</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="global" id="regexGlobal" value="1" checked>
                                    <label class="form-check-label" for="regexGlobal">
                                        <strong>Global Match (g)</strong>
                                    </label>
                                    <div class="form-text">Find all matches, not just the first</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 flex-wrap">
                    <?= submitBtn("regex", "action", "🔍 Test Regex", "search", "lg") ?>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- // ─────────────────────────────────────────────────────────────────────────────────────────────── # -->
