<div id="minify" class="content">
    <div class="card card-primary">
        <h1 class="card-header">Minify</h1>
        <div class="card-body">
            <p class="text-muted">Minify your HTML, CSS, and JavaScript code.</p>
            <form id="minifyForm" class="form" action="gen.php" method="POST" data-action="minify">
                <input type="hidden" name="tool" value="minify">
                <input type="hidden" name="responsetype" value="text">
                
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <label for="minifyTextarea" class="form-label"><strong>Input Code</strong></label>
                        <textarea name="input" id="minifyTextarea" class="form-control" rows="15" placeholder="Paste your code here..." style="font-family: monospace; resize: vertical;"></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label"><strong>Minified Output</strong></label>
                        <code id="minifyOutput" class="flex-grow-1 responseDiv" style="display: block; border: 1px solid #dee2e6; padding: 15px; min-height: 300px; max-height: 500px; overflow-y: auto; background-color: rgba(0,0,0,0.2); border-radius: 0.25rem; white-space: pre-wrap; word-break: break-word;" data-formid="minifyForm">Minified code will appear here...</code>
                    </div>
                </div>
                
                <hr>
                <div class="d-flex gap-3 align-items-center flex-wrap">
                    <select name="type" id="minifyType" class="form-select" style="max-width: 200px;">
                        <option value="js">JavaScript</option>
                        <option value="html">HTML</option>
                        <option value="css">CSS</option>
                    </select>
                    <?= submitBtn("minify", "tool", "Minify", "file-text-fill") ?>
                </div>
            </form>
        </div>
    </div>
</div>