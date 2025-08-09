<div id="calculator" class="content">


    <div class="card card-primary">
        <h1 class="card-header">Calculator</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="calc" data-action="calc">
                <span class="description">Input any calculation in the field below (e.g., 2+2, 5*3, etc.).</span>
                <input type="text" name="calcinput" class="form-control" placeholder="Enter your calculation (e.g., 2+2, 5*3, etc.)" required>
                <br>
                <?= submitBtn(name: "calc", text: "Calculate") ?>
                <div class="responseDiv" id="calcresponse"></div>
            </form>
        </div>
    </div>

</div>