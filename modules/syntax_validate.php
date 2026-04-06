<?php
    require_once __DIR__ . '/../includes/syntax_validate.php';
    $validatorsEmbed = $validatorsEmbed ?? false;
    $svKind = isset($_POST['syntax_validate_kind']) ? htmlspecialchars((string) $_POST['syntax_validate_kind'], ENT_QUOTES, 'UTF-8') : 'json';
    $svInput = isset($_POST['syntax_validate_input']) ? htmlspecialchars((string) $_POST['syntax_validate_input'], ENT_QUOTES, 'UTF-8') : '';
    if (!in_array($svKind, syntax_validate_allowed_kinds(), true)) {
        $svKind = 'json';
    }
    if ($validatorsEmbed) {
        echo '<section class="validators-block mb-0">';
    } else {
        echo '<div id="syntax_validate" class="content">';
    }
?>
    <div class="card card-primary">
        <?php if ($validatorsEmbed): ?>
        <h2 class="card-header h3 mb-0"><?= icon('braces') ?> Formats &amp; languages</h2>
        <?php else: ?>
        <h1 class="card-header"><?= icon('braces') ?> Syntax validator</h1>
        <?php endif; ?>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="syntaxValidateForm" data-action="syntax_validate">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-xl-6">
                        <label for="syntaxValidateKind" class="form-label mb-2"><strong>Language</strong></label>
                        <select name="syntax_validate_kind" id="syntaxValidateKind" class="form-select form-select-lg mb-3" style="border: 2px solid #495057;">
                            <option value="json" <?= $svKind === 'json' ? 'selected' : '' ?>>JSON</option>
                            <option value="yaml" <?= $svKind === 'yaml' ? 'selected' : '' ?>>YAML</option>
                            <option value="xml" <?= $svKind === 'xml' ? 'selected' : '' ?>>XML</option>
                            <option value="ini" <?= $svKind === 'ini' ? 'selected' : '' ?>>INI</option>
                            <option value="jsonl" <?= $svKind === 'jsonl' ? 'selected' : '' ?>>JSON Lines</option>
                            <option value="cron" <?= $svKind === 'cron' ? 'selected' : '' ?>>Cron</option>
                            <option value="php" <?= $svKind === 'php' ? 'selected' : '' ?>>PHP</option>
                            <option value="python" <?= $svKind === 'python' ? 'selected' : '' ?>>Python</option>
                            <option value="ruby" <?= $svKind === 'ruby' ? 'selected' : '' ?>>Ruby</option>
                            <option value="javascript" <?= $svKind === 'javascript' ? 'selected' : '' ?>>JavaScript</option>
                            <option value="shell" <?= $svKind === 'shell' ? 'selected' : '' ?>>Shell</option>
                        </select>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <label for="syntaxValidateInput" class="form-label mb-0"><strong style="font-size: 1.1rem;">Input</strong></label>
                            <button type="button" class="btn btn-sm btn-outline-secondary syntax-validate-random-sample" title="Insert random sample for the selected language"><?= icon('shuffle', 0.9) ?> Random sample</button>
                        </div>
                        <div data-no-random-buttons>
                            <textarea
                                name="syntax_validate_input"
                                id="syntaxValidateInput"
                                class="rand-code-textarea w-100"
                                placeholder="Paste content to validate..."
                                spellcheck="false"
                                autocomplete="off"
                                autocorrect="off"
                                autocapitalize="off"
                                required
                            ><?= $svInput ?></textarea>
                        </div>
                        <div class="form-text mt-2">
                            PHP snippets without <code>&lt;?php</code> are validated as if that opening tag were prepended.
                            Cron: first non-empty, non-<code>#</code> line is validated (same engine as the Crontab tool). INI uses PHP’s <code>parse_ini_string</code> (classic INI; not all <code>.env</code> dialects).
                        </div>
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
    <div class="alert alert-info mt-4 mb-0">
        <p class="mb-2"><strong>Check syntax without executing code.</strong></p>
        <ul class="small mb-0 ps-3">
            <li class="mb-1">JSON, YAML, XML, INI, and JSON Lines are parsed in PHP.</li>
            <li class="mb-1">Cron uses the same rules as the Crontab tool.</li>
            <li class="mb-1">
                PHP, Python, Ruby, JavaScript, and shell use CLI-backed syntax checks when those tools are available:
                <ul class="mt-1 mb-0 ps-3">
                    <li><strong>PHP</strong> — <code>php -l</code></li>
                    <li><strong>Python</strong> — <code>ast.parse</code></li>
                    <li><strong>Ruby</strong> — <code>ruby -c</code></li>
                    <li><strong>JavaScript</strong> — <code>node --check</code></li>
                    <li><strong>Shell</strong> — <code>bash -n</code> or <code>sh -n</code></li>
                </ul>
            </li>
            <li class="text-muted mb-0">Not full static analysis; for shell scripts, use ShellCheck under Miscellaneous.</li>
        </ul>
    </div>
<?php
if ($validatorsEmbed) {
    echo '</section>';
} else {
    echo '</div>';
}
