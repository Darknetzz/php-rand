<div id="syntax_highlight" class="content" data-syntax-theme="dark">
    <div class="card card-primary">
        <h1 class="card-header"><?= icon('highlighter') ?> Syntax Highlighter</h1>
        <div class="card-body">
            <p class="text-muted mb-4">
                Paste code for <a href="https://highlightjs.org/" target="_blank" rel="noopener noreferrer">highlight.js</a> coloring.
                Auto-detects language (overridable). Use <strong>Copy rich</strong> to paste colored code into Word, OneNote, and other rich editors.
            </p>

            <div class="row g-3 mb-3">
                <div class="col-12 col-md-6 col-lg-5">
                    <label for="syntaxHighlightLang" class="form-label"><strong>Language</strong></label>
                    <select id="syntaxHighlightLang" class="form-select form-select-lg" style="border: 2px solid #495057;">
                        <option value="auto" selected>Auto-detect</option>
                    </select>
                    <div class="form-text" id="syntaxHighlightDetected"></div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="syntaxHighlightTheme" class="form-label"><strong>Theme</strong></label>
                    <select id="syntaxHighlightTheme" class="form-select form-select-lg" style="border: 2px solid #495057;">
                        <option value="dark" selected>Dark</option>
                        <option value="light">Light</option>
                    </select>
                    <div class="form-text">Applies to preview and rich copy</div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-6">
                    <label for="syntaxHighlightInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Code</strong></label>
                    <textarea
                        id="syntaxHighlightInput"
                        class="form-control rand-code-textarea"
                        rows="18"
                        placeholder="Paste code here…"
                        spellcheck="false"
                    >function greet(name) {
  console.log(`Hello, ${name}!`);
}

greet("rand");</textarea>
                </div>
                <div class="col-12 col-lg-6 d-flex flex-column">
                    <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Highlighted preview</strong></label>
                    <div class="syntax-highlight-preview flex-grow-1 d-flex flex-column">
                        <div class="syntax-highlight-preview-toolbar">
                            <button type="button" class="btn btn-sm btn-outline-info btn-copy-rich" style="border: 1px solid #0dcaf0;" onclick="copyRichHtmlToClipboard('syntaxHighlightCode', this)" title="Copy HTML with inline colors for Word / OneNote">
                                <i class="bi bi-clipboard-check"></i> Copy rich (Word/OneNote)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-light" style="border: 1px solid #e9ecef;" onclick="copyToClipboard('syntaxHighlightCode', this)">
                                <i class="bi bi-files"></i> Copy plain
                            </button>
                        </div>
                        <div class="syntax-highlight-preview-body flex-grow-1">
                            <pre class="mb-0"><code id="syntaxHighlightCode" class="hljs syntax-highlight-theme-dark">// Paste code to highlight…</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
