<div class="content" id="spin_the_wheel">
<div class="card">
    <h1 class="card-header">
        Spin The Wheel
    </h1>

    <div class="card-body">
        <form class="form" method="POST" action="gen.php" id="spinwheel">
        <input type="hidden" name="action" value="spinwheel">
        <div class="wheelitems">
            <div class="form-floating mb-3 wheelitem">
                <input type="text" name="wheelitem[0]" class="form-control" placeholder="Item #1">
                <label for="floatingInput">Item #1</label>
            </div>
            <div class="form-floating mb-3 wheelitem">
                <input type="text" name="wheelitem[1]" class="form-control" placeholder="Item #2">
                <label for="floatingInput">Item #2</label>
            </div>
        </div>
        
        <div class="btn-group mb-3">
            <button type="button" class="btn btn-success rounded-circle mb-3" id="addtowheel"><?= icon("plus-circle") ?></button>
            <button type="button" class="btn btn-danger rounded-circle mb-3" id="removefromwheel"><?= icon("dash-circle") ?></button>
            <button type="button" class="btn btn-danger rounded-circle mb-3" id="clear"><?= icon("trash") ?></button>
        </div>

        <h4>Options</h4>
        <div class="form-group mb-3">
            <label>
                <input type="checkbox" class="form-input mb-3"> Spin more than once
            </label>
            <label>
                <input type="checkbox" class="form-input mb-3"> Force unique
            </label>
        </div>

        <hr>

        <div class="btn-group mb-3">
            <button type="submit" class="btn btn-success mb-3" name="spinwheel"><span class="dice"></span> Spin</button>
        </div>
        
        <div class="responseDiv" id="spinwheelresponse"></div>
        </form>
    </div>
</div>
</div>

<script>

    $("#addtowheel").on("click", function(e) {

        var inputCount =($(".wheelitem").length);
        console.log("Adding: "+inputCount);
        var placeholder = "Item #"+(inputCount+1);
        var input = `
        <div class="form-floating mb-3 wheelitem">
            <input type="text" name="wheelitem[]" class="form-control" name="item[]" placeholder="`+placeholder+`">
            <label for="floatingInput">`+placeholder+`</label>
        </div>
        `;
        $(".wheelitems").append(input);
        inputCount += 1;

        console.log("New count: "+inputCount);
    });

    $("#removefromwheel").on("click", function() {

        var inputCount = ($(".wheelitem").length);
        console.log("Removing: "+inputCount);

        if (inputCount > 2) {
            $(".wheelitem").last().remove();
            inputCount -= 1;
        } else {
            $("#spinwheelresponse").html("<div class='alert alert-danger'>Must have at least 2 items.</div>");
        }

        console.log("New count: "+inputCount);
    });

    $("#clear").on("click", function() {
        $(".wheelitem").remove();
    });

</script>