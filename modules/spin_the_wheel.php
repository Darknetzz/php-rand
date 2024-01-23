<div class="content" id="spin_the_wheel">
<div class="card">
    <h1 class="card-header mb-3">
            Spin The Wheel
    </h1>

    <div class="card-body">

        <div class="row d-flex justify-content-center">
            <h5>Items</h5>
            <div class="input-group col-3" style="width:30%">
                    <button type="button" class="btn btn-secondary" id="removefromwheel"><?= icon("dash-circle") ?></button>
                    <!-- <div class="form-floating"> -->
                        <input type="number" id="itemsamt" class="form-control" placeholder="Number of items" min="1" max="100" value="2">
                        <!-- <label for="floatingInput">Number of items</label> -->
                    <!-- </div> -->
                    <button type="button" class="btn btn-success" id="addtowheel"><?= icon("plus-circle") ?></button>
            </div>
            <div class="col">
                <button type="button" class="btn btn-danger" class="clear"><?= icon("trash") ?></button>
            </div>
        </div>

        <hr>

        <form class="form" method="POST" action="gen.php" id="spinwheel" data-action="spinwheel">
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

        <!-- OPTIONS -->
        <div class="card boder border-secondary">
            <h4 class="card-header text-bg-secondary">Options</h4>
            <div class="card-body">
                <div class="form-group mb-3">

                    <div class="form-check form-switch">
                        <input type="hidden" name="morespins" value="0">
                        <input id="morespins" name="morespins" value="1" type="checkbox" class="form-check-input mb-3">
                        <label for="morespins" class="form-check-label">Spin more than once</label>
                    </div>

                    <div id="spinsopt" style="display:none;">

                        <div class="form-floating">
                            <input 
                            id="spinsamt" name="spinsamt" type="number" class="form-control mb-3"
                            placeholder="Number of spins" min="1" max="100"
                            >
                            <label for="spinsamt">Number of spins</label>
                        </div>

                        <div class="form-check form-switch">
                            <input type="hidden" name="unique" value="0">
                            <input type="checkbox" id="unique" name="unique" value="1" class="form-check-input mb-3">
                            <label for="unique" class="form-check-label">Force unique</label>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- END OPTIONS -->

        <hr>

        <div class="btn-group mb-3">
            <?= submitBtn("spinwheel") ?>
        </div>

        <div class="responseDiv" id="spinwheelresponse"></div>
        </form>


        <hr>
    </div>
</div>
</div>

<script>

    /* ───────────────────────────────────────────────────────────────────── */
    /*                               addtowheel                              */
    /* ───────────────────────────────────────────────────────────────────── */
    $("#addtowheel").on("click", function(e) {

        var inputCount =($(".wheelitem").length);
        var placeholder = "Item #"+(inputCount+1);
        var input = `
        <div class="form-floating mb-3 wheelitem">
            <input type="text" name="wheelitem[]" class="form-control" name="item[]" placeholder="`+placeholder+`">
            <label for="floatingInput">`+placeholder+`</label>
        </div>
        `;
        $(".wheelitems").append(input);
        inputCount += 1;

        $("#itemsamt").val(inputCount);

    });


    /* ───────────────────────────────────────────────────────────────────── */
    /*                           remove from wheel                           */
    /* ───────────────────────────────────────────────────────────────────── */
    $("#removefromwheel").on("click", function() {

        var inputCount = ($(".wheelitem").length);

        if (inputCount > 2) {
            $(".wheelitem").last().remove();
            inputCount -= 1;
        } else {
            $("#spinwheelresponse").html("<div class='alert alert-danger'>Must have at least 2 items.</div>");
        }

        $("#itemsamt").val(inputCount);

    });

    /* ───────────────────────────────────────────────────────────────────── */
    /*                                 clear                                 */
    /* ───────────────────────────────────────────────────────────────────── */
    $(".clear").on("click", function() {
        $(".wheelitem:gt(1)").remove();

        $("#itemsamt").val($(".wheelitem").length);
    });

    /* ───────────────────────────────────────────────────────────────────── */
    /*                               morespins                               */
    /* ───────────────────────────────────────────────────────────────────── */
    $("#morespins").on("click", function() {
        if ($(this).is(":checked")) {
            $("#spinsopt").fadeIn();
        } else {
            $("#spinsopt").fadeOut();
        }
    });


    /* ───────────────────────────────────────────────────────────────────── */
    /*                                itemsamt                               */
    /* ───────────────────────────────────────────────────────────────────── */
    $("#itemsamt").on("change keyup", function() {
        var itemsamt = $(this).val();
        var inputCount = ($(".wheelitem").length);
        console.log("Itemsamt: "+itemsamt);
        console.log("Inputcount: "+inputCount);

        if (itemsamt > inputCount) {
            var diff = itemsamt - inputCount;
            console.log("Diff: "+diff);
            for (var i = 0; i < diff; i++) {
                $("#addtowheel").trigger("click");
            }
        } else if (itemsamt < inputCount) {
            var diff = inputCount - itemsamt;
            console.log("Diff: "+diff);
            for (var i = 0; i < diff; i++) {
                $("#removefromwheel").trigger("click");
            }
        }
    });

</script>