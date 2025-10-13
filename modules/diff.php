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
                    <label class="form-label" for="diff1">Old data</label>
                    <textarea class="form-control" id="diff1" name="diff1" placeholder="Old data" rows="12"></textarea>
                    </div>
                    <div class="col-12 col-md-6">
                    <label class="form-label" for="diff2">New data</label>
                    <textarea class="form-control" id="diff2" name="diff2" placeholder="New data" rows="12"></textarea>
                    </div>
                </div>

                <hr class="my-4">
                <?= submitBtn("diff") ?>
                <div class="responseDiv" data-formid="diff"></div>
            </form>
        </div>
    </div>

</div>