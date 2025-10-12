<div id="metaphone" class="content">
    <div class="card card-primary">
        <h1 class="card-header">Metaphone</h1>
        <div class="card-body">

            <form class="form" action="gen.php" method="POST" id="metaphone" data-action="metaphone">
                <textarea class="form-control" name="metaphone"></textarea>
                <?= submitBtn("metaphone", "action", "Convert", "arrow-repeat") ?>
                <div class="responseDiv" id="metaphoneresponse"></div>
            </form>

        </div>
    </div>
</div>