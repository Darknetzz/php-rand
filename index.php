<?php header('Content-Type: text/html; charset=utf-8'); ?>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<!-- Latest compiled and minified CSS -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->

<!-- Optional theme -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<style>
body {
  background-color:#262626;
  background-color:#111;
  color:#FFF;
}
/* .panel {
  background-color:#202020;
  color:#FFF;
} */

select, select:active,
input, input:active, input:focus,
.card, .jumbotron, 
.form-control, .form-control:active, .form-control:focus,
.form-select, .form-select:active, .form-select:focus {
  background-color:#303030;
  color:#FFF;
}

.description {
  color: #888;
}

li.active {
  background-color:#303030;
}

footer {
  color:grey;
  background-color: rgb(0, 0, 0, 70%);
  text-align:center;
  position:fixed;
  width:100%;
  margin-top:100%;
  bottom:0px;
}

.container {
  margin-bottom: 100px;
}

.content {
  display:none;
}
</style>

<title>Rand</title>

<body>
<?php include_once("navbar.php"); ?>
<br>
<div class="container">



<!-------------------------------------------------------------------------------->

<?php
foreach (glob("modules/*.php") as $module) {
  include_once($module);
}
?>

<!-------------------------------------------------------------------------------->

</div>
</div> <!-- CONTAINER END -->
<div id="error"></div>


<footer>Made with ❤️ by <a href="https://github.com/darknetzz" target="_blank" style="color:grey;">darknetzz</a></footer>
</body>

<script>
$( document ).ready(function() {
    //function submitForm(formname, responseid) {
    $(".form").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
    
        var form = $(this);
        // var form = $("#"+formname);
        var url = form.attr('action');
        var responseid = form.prop('id')+'response';
        console.log("Generating through "+form.prop('id')+" to "+responseid);
        $.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               beforeSend: function()
               {
                  $("#"+responseid).html('<h3>Generating...</h3>'); // show loading
               },
               error: function(data)
               {
                  console.log(data);
                  $("#"+responseid).html("<div class='alert alert-danger'>Error: "+data.statusText+"</div>");
               },
               success: function(data){
                  $("#"+responseid).html(data); // show response from the php script.
               }
    });
    });
    //}


    // submitForm("base64decode", "base64decoderesponse");
    // submitForm("base64encode", "base64encoderesponse");
    // submitForm("sha512hasher", "sha512hasherresponse");
    // submitForm("sha256hasher", "sha256hasherresponse");
    // submitForm("sha1hasher", "sha1hasherresponse");
    // submitForm("md5hasher", "md5hasherresponse");
    // submitForm("numgen", "numgenresponse");
    // submitForm("stringgen", "stringgenresponse");
    // submitForm("hex2bin", "hex2binresponse");
    // submitForm("bin2hex", "bin2hexresponse");
    // submitForm("rot", "bin2hexresponse");

    if (window.location.hash != '' && window.location.hash != undefined) {
    var hash = window.location.hash;
    var afterhash = hash.replace('#','');
    $(".content").hide();
    $(hash).fadeIn(1000);
    $(".nav-link").parent().prop("class", "");
    $("#nav"+afterhash).parent().prop("class", "nav-item active");
    console.log("Hash detected: "+hash+", setting nav"+afterhash+" parent as the active tab.");
    } else {
    // Hide everything, put first to avoid a split second of seeing everything | EDIT: Nevermind, putting hidden in html element works better, so the browser doesn't render it at all.
    $(".content").hide();
    // Start by only showing random string generator
    $("#dashboard").fadeIn(1000);
    console.log("Init with #rsgen");
    }

    // Handle navbar
    $(".nav-link").click(function() {
        var navLink = $(this);
        var navItems = $(".nav-item");
        var navItem = navLink.parent();

        navItems.prop("class", "nav-item");
        navItem.prop("class", "nav-item active");

        // $(".nav-link").prop("class", "nav-link");
        // $(this).prop("class", "nav-link active");

        var elementToShow = $(this).attr("href");
        $(".content").hide();
        $(elementToShow).fadeIn();

        if (elementToShow == undefined) {
            console.log("unable to show "+elementToShow);
            $("#error").html("<br><div class='alert alert-danger'>Failed to show page ("+elementToShow+").</div>");
            $("#error").show();
        } else {
            $("#error").hide();
            console.log("Showing "+elementToShow);
        }
    });

    // turn off all autocomplete
    $(".form-control").prop("autocomplete", "off");






});
</script>