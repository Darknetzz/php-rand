<div id="markdown" class="content">
    <div class="card card-primary">
        <h1 class="card-header">Markdown</h1>
        <div class="card-body">
            <p class="text-muted">A simple markdown editor using <a href="https://marked.js.org/" target="_blank">marked.js</a> and <a href="https://highlightjs.org/" target="_blank">highlight.js</a> for syntax highlighting.</p>
            
            <div class="row g-3">
                <div class="col-12 col-lg-6">
                    <label for="markdownTextarea" class="form-label"><strong>Markdown Input</strong></label>
                    <textarea id="markdownTextarea" class="form-control" rows="15" placeholder="Write your markdown here..." style="font-family: monospace; resize: vertical;"># Marked in the browser
Write your markdown here...</textarea>
                </div>
                <div class="col-12 col-lg-6 d-flex flex-column">
                    <label class="form-label"><strong>Preview</strong></label>
                    <div id="markdownContent" class="flex-grow-1" style="border: 1px solid #dee2e6; padding: 15px; min-height: 300px; max-height: 500px; overflow-y: auto; background-color: rgba(0,0,0,0.05); border-radius: 0.25rem;"></div>
                </div>
            </div>
            
            <hr>
            <button id="markdownRenderBtn" class="btn btn-primary"><?= icon("arrow-repeat") ?> Render</button>
            
            <hr>
            <h4>HTML Output:</h4>
            <pre style="border: 1px solid #dee2e6; padding: 15px; max-height: 300px; overflow-y: auto; background-color: rgba(0,0,0,0.2); border-radius: 0.25rem;"><code id="markdownHtmlOutput" style="white-space: pre-wrap; word-break: break-word;">&lt;h1&gt;Marked in the browser&lt;/h1&gt;</code></pre>
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
        $("#markdownHtmlOutput").text(htmlContent); // htmlContent.replace(/</g, "&lt;").replace(/>/g, "&gt;"
        hljs.highlightAll();
    }
    

    // Initial render
    renderMarkdown();
    // Render on input change
    $("#markdownTextarea").on('input', function() {
        renderMarkdown();
    });
    $("#markdownRenderBtn").on('click', function() {
        renderMarkdown();
    });
</script>