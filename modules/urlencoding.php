<div id="urlencoding" class="content">

    <div class="card card-primary">
        <h1 class="card-header">URL Encoding</h1>
        <div class="card-body">
            <span class="description">Input any text or URL encoded string below, and this tool will convert it to all other URL formats.</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="urlencode" data-action="urlencode">
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <label for="urlencodeInput" class="form-label"><strong>Input</strong></label>
                        <textarea name="urlencode" id="urlencodeInput" class="form-control mb-2" style="min-height: 200px; resize: vertical; font-family: monospace;"
                            placeholder="Enter text or URL encoded string to convert..." required></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label"><strong>Output</strong></label>
                        <div class="responseDiv flex-grow-1" id="urlencoderesponse" style="border: 1px solid #dee2e6; padding: 15px; min-height: 200px; max-height: 400px; overflow-y: auto; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; white-space: pre-wrap; word-break: break-word;">Result will appear here...</div>
                    </div>
                </div>
                <hr>
                <?= submitBtn("urlencode", "action", "Convert", "arrow-repeat") ?>
            </form>
        </div>
    </div>

</div>
