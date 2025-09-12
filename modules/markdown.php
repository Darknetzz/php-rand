<div id="markdown" class="content">
    <div class="card card-primary">
        <h1 class="card-header">Markdown</h1>
        <div class="card-body">
            <p class="text-muted">A simple markdown editor using <a href="https://marked.js.org/" target="_blank">marked.js</a> and <a href="https://highlightjs.org/" target="_blank">highlight.js</a> for syntax highlighting.</p>
            <textarea id="markdownTextarea" class="form-control" rows="10" placeholder="Write your markdown here"># Marked in the browser
Write your markdown here...</textarea>
            <br>
            <button id="markdownRenderBtn" class="btn btn-primary">Render</button>
            <hr>
            <h1>Preview:</h1>
            <div id="markdownContent" class="box"></div>
            <hr>
            <h1>HTML Output:</h1>
            <pre class="box"><code id="markdownHtmlOutput" class="bg-dark text-light p-3" style="white-space: pre-wrap;">&lt;h1&gt;Marked in the browser&lt;/h1&gt;</code></pre>
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