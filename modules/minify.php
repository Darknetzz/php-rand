<div id="minify" class="content">
    <div class="card card-primary">
        <h1 class="card-header">Minify</h1>
        <div class="card-body">
            <p class="text-muted">Minify your HTML, CSS, and JavaScript code.</p>
            <form id="minifyForm" class="form" action="gen.php" method="POST" data-action="minify">
                <input type="hidden" name="tool" value="minify">
                <input type="hidden" name="responsetype" value="text">
                <textarea name="input" id="minifyTextarea" class="form-control" rows="10" placeholder="Paste your code here..."></textarea>
                <br>
                <select name="type" id="minifyType" class="form-select mb-3" style="max-width: 200px;">
                    <option value="js">JavaScript</option>
                    <option value="html">HTML</option>
                    <option value="css">CSS</option>
                </select>
                <?= submitBtn("minify", "tool", "Minify", "file-text-fill") ?>
                
                <hr>
                <h1>Minified Output:</h1>
                <!-- <pre class="box"> -->
                    <code id="minifyOutput" class="bg-dark text-light p-3 responseDiv" style="white-space: pre-wrap;" data-formid="minifyForm"></code>
                <!-- </pre> -->
            </form>
        </div>
    </div>
</div>