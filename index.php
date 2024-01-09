<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<?php header('Content-Type: text/html; charset=utf-8'); ?>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="style.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

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
        var responseObj = $("#"+responseid);

        var btnName = $("button[clicked=true]").prop("name");
        var btnValue = $("button[clicked=true]").val();

        var serializeForm = form.serialize()+"&"+btnName+"="+btnValue;

        function showData(obj, data) {
          if (obj.is("div")) {
            obj.html(data);
          } else {
            data = data.replace(/<(.|\n)*?>/g, '');
            obj.val(data);
          }
        }

        console.log("Form data: "+serializeForm);
        console.log("Generating through "+form.prop('id')+" to "+responseid);
        $.ajax({
               type: "POST",
               url: url,
               data: serializeForm, // serializes the form's elements.
               beforeSend: function()
               {
                  showData(responseObj, 'Generating...'); // show loading
               },
               error: function(data)
               {
                  console.log(data);
                  showData(responseObj, "<div class='alert alert-danger'>Error: "+data.statusText+"</div>");
               },
               success: function(data){
                showData(responseObj, data);
               }
    });
    });

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
</html>