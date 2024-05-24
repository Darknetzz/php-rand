<div id="serialization" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Serialization</h1>
        <div class="card card-body">

            <div class="alert alert-info mx-2">
                <h4 class="text-info"><?= icon("info-circle", color: "cyan") ?> Info</h4>
                <p>
                    Input your serialized data on the left side (JSON, XML or YAML), and choose what type of
                    output you want on the right side. The input will be automatically detected.
                </p>
            </div>

            <hr>

            <form class="form" action="gen.php" method="POST" id="serializationForm" data-action="serialization" data-responsetype="text">
                <div class="d-flex justify-content-evenly mb-2">
                    <div style="width:50%">
                        <code-input name="input" template="default" class="code code-input_registered" id="rotInput" placeholder="Input your valid JSON, YAML or XML here"></code-input>
                    </div>
                    <div style="width:50%">
                        <code-input name="output" template="default" class="code code-input_registered responseDiv" placeholder="Output will show here" readonly></code-input>
                        <button type="button" class="copyText btn btn-primary" data-clipboard-target=".responseDiv"><?= icon("clipboard") ?> Copy</button>
                    </div>
                </div>
                Output type:
                <select class="form-select mb-3" name="type" required>
                        <!-- <option value="" disabled selected>Select output type</option> -->
                        <option value="JSON">JSON</option>
                        <option value="XML">XML</option>
                        <option value="YAML">YAML</option>
                </select>
                <label>
                    <input type="checkbox" name="stripcomments"> Remove comments (lines starting with <code>#</code> or <code>//</code>)
                </label>
                <hr>
                <?= submitBtn("serialization", "action", "Generate", "arrow-repeat") ?>
            </form>
        </div>
    </div>

</div>

<!-- <script>
$("#serializationForm").on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    var data = form.serialize();
    $.ajax({
        type: "POST",
        url: "gen.php",
        data: data,
        success: function(response) {
            form.find("textarea[readonly]").val(response);
        }
    });
});
</script> -->