<div class="content" id="spin_the_wheel">
<div class="card">
    <h1 class="card-header">
        Spin The Wheel
    </h1>

    <div class="card-body">

    <form class="form" method="POST" action="gen.php" id="spinwheel">
    <div class="wheelitems">
        <input type="text" class="form-control wheelitem" name="wheelitem[]" placeholder="Item 1">
        <input type="text" class="form-control wheelitem" name="wheelitem[]" placeholder="Item 2">
    </div>
    <hr>
    <button type="button" id="addtowheel" class="btn btn-success">+</button>
    <button type="button" id="removefromwheel" class="btn btn-danger">-</button>
    <hr>
    <input type="submit" class="btn btn-success" value="Spin" name="spinwheel">
    <div id="spinwheelresponse"></div>
    </form>


    </div>
</div>
</div>

<script>

    $("#addtowheel").on("click", function(e) {

        var inputCount =($(".wheelitem").length);
        console.log("Adding: "+inputCount);

        var input = '<input type="text" name="wheelitem[]" class="form-control wheelitem" name="item[]" placeholder="Item '+(inputCount+1)+'">';
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

</script>