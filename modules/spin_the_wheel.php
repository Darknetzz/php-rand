<div class="content" id="spin_the_wheel">
<div class="card">
    <h1 class="card-header">
        Spin The Wheel
    </h1>

    <div class="card-body">

    Coming soon!

    <form class="form" mrthod="POST" action="gen.php" id="spinwheel">
    <input type="text" name="item[]" class="form-control">
    </form>

    <button type="button" id="addtowheel" class="btn btn-success">+</button>
    <input type="submit">
    <div id="spinwheelresponse"></div>


    </div>
</div>
</div>

<script>
    $("#addtowheel").on("click", function(e) {
        var inputCount = $("input").count();
        var input = '<input type="text" name="item[]" placeholder="Input '+inputCount+'" class="form-control">';
        $("#spinwheel").append(input);
    });
</script>