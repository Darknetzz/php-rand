<div id="calculator" class="content">


    <div class="card card-primary">
        <h1 class="card-header">Calculator</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="calc" data-action="calc">
                <span class="description">Input any calculation in the field below (e.g., 2+2, 5*3, etc.).</span>
                <hr>
                <label for="calcInput" class="form-label"><strong>Calculation</strong></label>
                <input type="text" id="calcInput" name="calcinput" class="form-control mb-3" placeholder="Enter your calculation (e.g., 2+2, 5*3, etc.)" style="font-family: monospace; font-size: 1.2rem;" required>
                <?= submitBtn(name: "calc", text: "Calculate", icon: "calculator") ?>
                <hr>
                <label class="form-label"><strong>Result</strong></label>
                <div class="responseDiv" id="calcresponse" style="border: 1px solid #dee2e6; padding: 20px; min-height: 80px; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; font-size: 1.5rem; font-weight: bold; text-align: center;">Result will appear here...</div>
            </form>
        </div>
    </div>

</div>