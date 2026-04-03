<?php
    $validatorsEmbed = $validatorsEmbed ?? false;
    $svKind = isset($_POST['syntax_validate_kind']) ? htmlspecialchars((string) $_POST['syntax_validate_kind'], ENT_QUOTES, 'UTF-8') : 'json';
    $svInput = isset($_POST['syntax_validate_input']) ? htmlspecialchars((string) $_POST['syntax_validate_input'], ENT_QUOTES, 'UTF-8') : '';
    if ($svKind !== 'json' && $svKind !== 'yaml' && $svKind !== 'php' && $svKind !== 'python') {
        $svKind = 'json';
    }
    if ($validatorsEmbed) {
        echo '<section class="validators-block mb-0">';
    } else {
        echo '<div id="syntax_validate" class="content">';
    }
?>
    <div class="alert alert-info mb-4">
        <strong>Check syntax without executing code.</strong>
        JSON and YAML are parsed in PHP; PHP uses <code>php -l</code>; Python uses <code>ast.parse</code> via the system interpreter when available.
    </div>
    <div class="card card-primary">
        <h2 class="card-header h3 mb-0"><?= icon('braces') ?> JSON / YAML / PHP / Python</h2>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="syntaxValidateForm" data-action="syntax_validate">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-xl-6">
                        <label for="syntaxValidateKind" class="form-label mb-2"><strong>Language</strong></label>
                        <select name="syntax_validate_kind" id="syntaxValidateKind" class="form-select form-select-lg mb-3" style="border: 2px solid #495057;">
                            <option value="json" <?= $svKind === 'json' ? 'selected' : '' ?>>JSON</option>
                            <option value="yaml" <?= $svKind === 'yaml' ? 'selected' : '' ?>>YAML</option>
                            <option value="php" <?= $svKind === 'php' ? 'selected' : '' ?>>PHP</option>
                            <option value="python" <?= $svKind === 'python' ? 'selected' : '' ?>>Python</option>
                        </select>
                        <label for="syntaxValidateInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input</strong></label>
                        <textarea
                            name="syntax_validate_input"
                            id="syntaxValidateInput"
                            class="form-control"
                            placeholder="Paste content to validate..."
                            style="min-height: 420px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;"
                            required
                        ><?= $svInput ?></textarea>
                        <div class="form-text mt-2">PHP snippets without <code>&lt;?php</code> are validated as if that opening tag were prepended.</div>
                    </div>
                    <div class="col-12 col-xl-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Result</strong></label>
                        <div class="responseDiv flex-grow-1" style="border: 2px solid #495057; padding: 20px; min-height: 420px; max-height: 760px; overflow-y: auto; background: linear-gradient(135deg, rgba(25, 135, 84, 0.08) 0%, rgba(13, 110, 253, 0.08) 100%); border-radius: 0.5rem;">
                            <div style="opacity: 0.55; text-align: center; padding-top: 150px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;"><?= icon('patch-check', 2) ?></div>
                                <div>Validation result will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3 flex-wrap">
                    <?= submitBtn('syntax_validate', 'action', 'Validate', 'check2', 'lg') ?>
                </div>
            </form>
        </div>
    </div>
<?php
if ($validatorsEmbed) {
    echo '</section>';
} else {
    echo '</div>';
}
