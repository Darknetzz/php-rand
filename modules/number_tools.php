<div id="number_tools" class="content">
<div class="card card-primary">
<h1 class="card-header">Number Generator</h1>
    <div class="card card-body">
        <form class="form" action="gen.php" method="POST" id="numgen" data-action="numgen">
            Generate a number between
            <?php
            if (isset($_POST['numgenfrom']) && isset($_POST['numgento'])) {
                echo "<input type='number' name='numgenfrom' class='form-control' value='".$_POST['numgenfrom']."'> and <input type='number' name='numgento' class='form-control' value='".$_POST['numgento']."'>";
            } else {
                echo "<input type='number' name='numgenfrom' class='form-control' value='1'> and <input type='number' name='numgento' class='form-control' value='100'>";
            }
            ?>
            <input type="hidden" name="seed" value="0">
            <label><input type="checkbox" id="number_toolsseedtoggle" name="seed" value="1"> Seed</label><br>
            <div class="number_toolsseed" style="display:none;">
            with seed: 
            <input type="text" name="numgenseed" class="form-control" value="" placeholder="Optional">
            </div>
            <br>
            <?= submitBtn("numgen") ?>
            <div class="responseDiv" id="numgenresponse"></div>
        </form>
    </div>
</div>
</div>

<script>
    // Seed toggle numgen
    $("#number_toolsseedtoggle").change(function() {
        if ($(this).is(":checked")) {
        $(".number_toolsseed").fadeIn();
        } else {
        $(".number_toolsseed").fadeOut();
        }
    });
</script>