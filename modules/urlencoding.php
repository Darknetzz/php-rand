<div id="urlencoding" class="content">

    <div class="card card-primary">
        <h1 class="card-header">URL Encoding</h1>
        <div class="card-body">
            <span class="description">Input any text or URL encoded string below, and this tool will convert it to all other URL formats.</span>
            <form class="form" action="gen.php" method="POST" id="urlencode" data-action="urlencode">
                <textarea name="urlencode" class="form-control mb-2"
                    placeholder="Enter text or URL encoded string to convert..." value="" required></textarea>
                <?= submitBtn("urlencode", "action", "Convert", "arrow-repeat") ?>
                <div class="responseDiv" id="urlencoderesponse"></div>
            </form>
        </div>
    </div>

</div>
