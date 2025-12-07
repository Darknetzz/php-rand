<div id="gen_number" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Number Generator</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="numgen" data-action="numgen">
                
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label"><strong>Generate a random number</strong></label>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">From</label>
                        <?php
                        $fromValue = isset($_POST['numgenfrom']) ? $_POST['numgenfrom'] : '1';
                        ?>
                        <input type="number" name="numgenfrom" class="form-control form-control-lg" value="<?= $fromValue ?>" placeholder="1" style="font-family: monospace; font-size: 1.5rem;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">To</label>
                        <?php
                        $toValue = isset($_POST['numgento']) ? $_POST['numgento'] : '100';
                        ?>
                        <input type="number" name="numgento" class="form-control form-control-lg" value="<?= $toValue ?>" placeholder="100" style="font-family: monospace; font-size: 1.5rem;">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="number_toolsseedtoggle" name="seed" value="1">
                        <label class="form-check-label" for="number_toolsseedtoggle">Use custom seed (for reproducible results)</label>
                    </div>
                </div>

                <div class="number_toolsseed mb-3" style="display:none;">
                    <label class="form-label">Seed value</label>
                    <input type="text" name="numgenseed" class="form-control" value="" placeholder="Enter a seed value (optional)" style="font-family: monospace;">
                </div>

                <input type="hidden" name="seed" value="0">
                
                <?= submitBtn("numgen") ?>
                
                <div class="responseDiv mt-3" id="numgenresponse" style="border: 1px solid #dee2e6; padding: 20px; min-height: 80px; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; text-align: center; font-size: 2rem; font-weight: bold; display: none;"></div>
            </form>
        </div>
    </div>

</div>

<script>
    // Seed toggle numgen
    $("#number_toolsseedtoggle").change(function() {
        if ($(this).is(":checked")) {
            $(".number_toolsseed").slideDown();
        } else {
            $(".number_toolsseed").slideUp();
        }
    });
    
    // Show response div when result comes in
    $("#numgen").on("submit", function() {
        $("#numgenresponse").show();
    });
</script>