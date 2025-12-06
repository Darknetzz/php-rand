<div id="htmlentities" class="content">

    <div class="card card-primary">
        <h1 class="card-header">HTML Entities</h1>
        <div class="card-body">
            <span class="description">Input any text or HTML encoded string below, and this tool will convert it to all other HTML formats.</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="htmlentities" data-action="htmlentities">
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <label for="htmlentitiesInput" class="form-label"><strong>Input</strong></label>
                        <textarea name="htmlentities" id="htmlentitiesInput" class="form-control mb-2" style="min-height: 200px; resize: vertical; font-family: monospace;"
                            placeholder="Enter text or HTML encoded string to convert..." required></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label"><strong>Output</strong></label>
                        <div class="responseDiv flex-grow-1" id="htmlentitiesresponse" style="border: 1px solid #dee2e6; padding: 15px; min-height: 200px; max-height: 400px; overflow-y: auto; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; white-space: pre-wrap; word-break: break-word;">Result will appear here...</div>
                    </div>
                </div>
                <hr>
                <?= submitBtn("htmlentities", "action", "Convert", "arrow-repeat") ?>
            </form>
        </div>
    </div>

</div>