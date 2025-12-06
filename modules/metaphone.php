<div id="metaphone" class="content">
    <div class="card card-primary">
        <h1 class="card-header">Metaphone</h1>
        <div class="card-body">
            <span class="description">Calculate the metaphone key of a string</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="metaphone" data-action="metaphone">
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <label for="metaphoneInput" class="form-label"><strong>Input Text</strong></label>
                        <textarea class="form-control" id="metaphoneInput" name="metaphone" rows="8" placeholder="Enter text to convert..." style="font-family: monospace; resize: vertical;"></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label"><strong>Metaphone Key</strong></label>
                        <div class="responseDiv flex-grow-1" id="metaphoneresponse" style="border: 1px solid #dee2e6; padding: 15px; min-height: 150px; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; white-space: pre-wrap;">Result will appear here...</div>
                    </div>
                </div>
                <hr>
                <?= submitBtn("metaphone", "action", "Convert", "arrow-repeat") ?>
            </form>

        </div>
    </div>
</div>