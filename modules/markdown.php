<div id="markdown" class="content">
    <div class="alert alert-info mb-4">
        <strong>ℹ️ Live Markdown Preview</strong>
        <span>Uses <a href="https://marked.js.org/" target="_blank" class="alert-link">marked.js</a> and <a href="https://highlightjs.org/" target="_blank" class="alert-link">highlight.js</a> for real-time rendering with syntax highlighting.</span>
    </div>
    <div class="card card-primary">
        <h1 class="card-header">📝 Markdown Editor</h1>
        <div class="card-body">
            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-6">
                    <label for="markdownTextarea" class="form-label mb-3"><strong style="font-size: 1.1rem;">Markdown Input</strong></label>
                    <textarea id="markdownTextarea" class="form-control" rows="18" placeholder="# Write your markdown here..." style="font-family: monospace; resize: vertical; font-size: 0.95rem; border: 2px solid #495057; min-height: 400px;"># Marked in the browser

Rendered by marked in real time.

## Example Code

```javascript
console.log('Hello World!');
```

- **Bold**
- *Italic*
- [Link](https://marked.js.org/)</textarea>
                </div>
                <div class="col-12 col-lg-6 d-flex flex-column">
                    <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Live Preview</strong></label>
                    <div id="markdownContent" class="flex-grow-1" style="border: 2px solid #495057; padding: 20px; min-height: 400px; max-height: 600px; overflow-y: auto; background: linear-gradient(135deg, rgba(75, 0, 130, 0.08) 0%, rgba(138, 43, 226, 0.05) 100%); border-radius: 0.5rem; box-shadow: 0 6px 16px rgba(0,0,0,0.25);"></div>
                </div>
            </div>
            
            <hr>
            
            <div class="card border-secondary mt-4">
                <div class="card-header bg-secondary text-white">
                    <strong>🔍 HTML Output</strong>
                </div>
                <div class="card-body" style="padding: 0;">
                    <div class="copyable-content" style="padding: 0; border-radius: 0 0 0.4rem 0.4rem;">
                        <pre class="copyable-body" style="border: none; padding: 20px; margin: 0; background: transparent;"><code id="markdownHtmlOutput" style="white-space: pre-wrap; word-break: break-word; color: #e9ecef; font-family: monospace; font-size: 0.9rem;">&lt;h1&gt;Marked in the browser&lt;/h1&gt;</code></pre>
                        <div class="copyable-actions" style="padding: 0 14px 14px;">
                            <button type="button" class="btn btn-sm btn-outline-light" style="border: 1px solid #e9ecef;" onclick="copyToClipboard('markdownHtmlOutput', this)">
                                <i class="bi bi-files"></i> Copy HTML
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /*
    # ─────────────────────────────────────────────────────────────────────────── //
    #                           FUNCTION: renderMarkdown                          //
    # ─────────────────────────────────────────────────────────────────────────── //
    */
    function renderMarkdown() {
        var markdownText = $("#markdownTextarea").val();
        var htmlContent = marked.parse(markdownText);
        $("#markdownContent").html(htmlContent);
        $("#markdownHtmlOutput").text(htmlContent);
        hljs.highlightAll();
    }
    
    // Initial render
    renderMarkdown();
    
    // Auto-render on input (debounced for performance)
    var renderTimeout;
    $("#markdownTextarea").on('input', function() {
        clearTimeout(renderTimeout);
        renderTimeout = setTimeout(renderMarkdown, 300);
    });
</script>