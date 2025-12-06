<div id="diff" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Diff</h1>
        <div class="card-body">
            <span class="form-text m-3">
                Make unified diff of two strings
            </span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="diff" data-action="diff">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                    <label class="form-label" for="diff1"><strong>Old data</strong></label>
                    <textarea class="form-control" id="diff1" name="diff1" placeholder="Old data" rows="12" style="font-family: monospace; resize: vertical;"></textarea>
                    </div>
                    <div class="col-12 col-md-6">
                    <label class="form-label" for="diff2"><strong>New data</strong></label>
                    <textarea class="form-control" id="diff2" name="diff2" placeholder="New data" rows="12" style="font-family: monospace; resize: vertical;"></textarea>
                    </div>
                </div>

                <hr class="my-4">
                <?= submitBtn("diff") ?>
                <div class="responseDiv" data-formid="diff" style="margin-top: 15px; border: 1px solid #dee2e6; padding: 15px; min-height: 100px; max-height: 400px; overflow-y: auto; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; white-space: pre-wrap; word-break: break-word;"></div>
            </form>
        </div>
    </div>

</div>