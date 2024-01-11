<div class="content" id="spin_the_wheel">
<div class="card">
    <h1 class="card-header mb-3">
            Spin The Wheel
    </h1>

        <div class="d-flex justify-content-center mb-3">
            <div class="btn-group btn-group-lg">
                <button type="button" class="btn btn-success" id="addtowheel"><?= icon("plus-circle") ?></button>
                <button type="button" class="btn btn-secondary" id="removefromwheel"><?= icon("dash-circle") ?></button>
                <button type="button" class="btn btn-danger" id="clear"><?= icon("trash") ?></button>
            </div>
        </div>

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

        <div class="card boder border-secondary">
            <h4 class="card-header text-bg-secondary">Options</h4>
            <div class="card-body">
                <div class="form-group mb-3">

                    <div class="mb-1 form-check">
                        <label>
                            <input type="hidden" name="morespins" value="0">
                            <input id="morespins" name="morespins" value="1" type="checkbox" class="form-input mb-3"> Spin more than once
                        </label>
                    </div>

                    <div class="" id="spinsopt" style="display:none;">
                        <label for="spinsamt">Number of spins</label>
                        <input 
                            id="spinsamt" name="spinsamt" type="number" class="form-control form-control mb-3"
                            placeholder="Number of spins" min="1" max="100" value="1"
                        >

                        <div class="mb-1 form-check">
                            <label for="unique">Force unique</label>
                                <input type="hidden" name="unique" value="0">
                                <input type="checkbox" id="unique" name="unique" class="form-input mb-3">
                            </label>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <hr>
        
        <div class="btn-group mb-3">
            <button type="submit" class="btn btn-success mb-3" name="spinwheel"><span class="dice"></span> Spin</button>
        </div>

        <div class="responseDiv" id="spinwheelresponse"></div>
        </form>
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

    $("#morespins").on("click", function() {
        if ($(this).is(":checked")) {
            $("#spinsopt").fadeIn();
        } else {
            $("#spinsopt").fadeOut();
        }
    });

</script>