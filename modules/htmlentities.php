<div id="htmlentities" class="content">

    <div class="card card-primary">
        <h1 class="card-header">HTML Entities</h1>
        <div class="card-body">
            <span class="description">Input any text or HTML encoded string below, and this tool will convert it to all other HTML formats.</span>
            <form class="form" action="gen.php" method="POST" id="htmlentities" data-action="htmlentities">
                <textarea name="htmlentities" class="form-control mb-2"
                    placeholder="Enter text or HTML encoded string to convert..." value="" required></textarea>
                <?= submitBtn("htmlentities", "action", "Convert", "arrow-repeat") ?>
                <div class="responseDiv" id="htmlentitiesresponse"></div>
            </form>
        </div>
    </div>

</div>