<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<?php header('Content-Type: text/html; charset=utf-8'); ?>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="style.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> -->
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>


<!-- /* ────────────────────────────────────────────────────────────────────────── */ -->
<!-- /*                               CODE HIGHLIGHT                               */ -->
<!-- /* ────────────────────────────────────────────────────────────────────────── */ -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/dark.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

<!-- and it's easy to individually load additional languages -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/go.min.js"></script>

<script src="https://cdn.jsdelivr.net/gh/WebCoder49/code-input@2.2/code-input.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/WebCoder49/code-input@2.2/code-input.min.css">

<!-- Plugins -->
<script src="js/hljs_autodetect.js"></script>
<script src="js/hljs_indent.js"></script>
<!-- /* ────────────────────────────────────────────────────────────────────────── */ -->

<!--In the <head>-->

<title>Rand</title>

<body class="theme-dark">
<?php include_once("functions.php"); ?>
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

  /* ───────────────────────────────────────────────────────────────────── */
  /*                               Code Input                              */
  /* ───────────────────────────────────────────────────────────────────── */
  // hljs.highlightAll();
  codeInput.registerTemplate("default", codeInput.templates.hljs(hljs, [
    new codeInput.plugins.Autodetect(),
    new codeInput.plugins.Indent(true, 2) // 2 spaces indentation
  ] /* Array of plugins (see below) */));
  $(".code").on("paste", function() {
    this.style.height = "auto";
  });

  /* ───────────────────────────────────────────────────────────────────── */
  /*                               setAction                               */
  /* ───────────────────────────────────────────────────────────────────── */
  function setFormVal(form, name = "action", value = "") {
    console.log("[setFormVal] Setting form value "+name+" to: "+value);
    $(form).find(".setFormVal[name='"+name+"']").remove();
    var hiddenInput = $("<input>")
      .attr("class", "setFormVal")
      .attr("type", "hidden")
      .attr("name", name).val(value);
    $(form).append(hiddenInput);
  }


  /* ───────────────────────────────────────────────────────────────────── */
  /*                                navigate                               */
  /* ───────────────────────────────────────────────────────────────────── */
  function navigate(to) {

    console.log("[navigate] Navigating to: "+to)

    // Reset all nav links
    var navLinks = $(".link.nav-link");
    navLinks.prop("class", "link nav-link");

    // Set this nav link as active
    var navLink = $(`.link.nav-link[href='${to}']`);
    navLink.prop("class", "link nav-link link-success active");
    
    $(".content").hide();
    $(to).fadeIn();

  }

  /* ───────────────────────────────────────────────────────────────────── */
  /*                             randomizeDice                             */
  /* ───────────────────────────────────────────────────────────────────── */
  function randomizeDice() {
    var dice = [1, 2, 3, 4, 5, 6];
    var diceIcon = dice[Math.floor(Math.random()*dice.length)];
    $(".dice").html('<i class="bi bi-dice-'+diceIcon+'"></i>');
  }
  randomizeDice();

  /* ────────────────────────────────────────────────────────────────────────── */
  /*                               Copy to clipboard                            */
  /* ────────────────────────────────────────────────────────────────────────── */
  $(".copyText").click(function() {
    var copyText = $(this).closest(".responseDiv");
    copyText.select();
    // copyText[0].setSelectionRange(0, 99999); /* For mobile devices */
    document.execCommand("copy");
    $(this).html("<?= icon("check") ?> Copied!");
    $(this).addClass("btn-success");
  });

  /* ───────────────────────────────────────────────────────────────────── */
  /*                              Form submit                              */
  /* ───────────────────────────────────────────────────────────────────── */
    //function submitForm(formname, responseid) {
    $(".form").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
    
        var form          = $(this);

        // Set clicken `.genBtn` as form value
        var clickedGenBtn = $('.genBtn:focus');

        // Set response type
        if (form.data("responsetype")) {
          var responsetype = form.data("responsetype");
        } else {
          var responsetype = "html";
        }
        setFormVal(form, "responsetype", responsetype);

        // Set form action
        if (form.data("action")) {
          var action = form.data("action");
        }
        if (form.find("input[name=action]").length) {
          var action = form.find("input[name=action]").val();
        }
        setFormVal(form, "action", action)

        // Set clicked button as form value
        if (clickedGenBtn.length) {
          var name          = clickedGenBtn.length ? clickedGenBtn.attr("name") : "";
          var value         = clickedGenBtn.length ? clickedGenBtn.attr("value") : "";
          if (name != "action" && name != "") {
            setFormVal(form, name, value);
          }
        } 
        
        // NOTE: This is not needed because we are checking for the response object type
        //       in the showData function.
        // Determine response type (text or HTML)
        // var responseType = form.data("responseType");
        // if (responseType == undefined) {
        //   responseType = "html";
        // }

        // Send form
        var url = form.attr('action');
        var responseObj = form.find(".responseDiv");

        var btnName = $("button[clicked=true]").prop("name");
        var btnValue = $("button[clicked=true]").val();

        var serializeForm = form.serialize()+"&"+btnName+"="+btnValue;
        console.log("[submitForm] Sending form: "+serializeForm);

        function showData(obj, data) {
          if (obj.is("div")) {
            obj.html(data);
          } else if (obj.is("code-input")) {
            obj.val(data.trim());
          } else {
            data = data.replace(/<(.|\n)*?>/g, '');
            obj.val(data.trim());
          }
        }

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
                  if (responsetype == "html") {
                    showData(responseObj, "<div class='alert alert-danger'>Error: "+data.statusText+"</div>");
                  } else {
                    showData(responseObj, "Error: "+data.statusText);
                  }
               },
               success: function(data){
                showData(responseObj, data);
               }
      });
      randomizeDice();
    });

    /* ───────────────────────────────────────────────────────────────────── */
    /*                      Navigation (and hash check)                      */
    /* ───────────────────────────────────────────────────────────────────── */
    if (window.location.hash != '' && window.location.hash != undefined) {
      // Hash is set on page load, so navigate to it
      var hash      = window.location.hash;
      var afterhash = hash.replace('#','');
      navigate(hash);
      console.log("Hash detected: "+hash+", setting nav"+afterhash+" parent as the active tab.");
    } else {
      navigate("#dashboard");
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*                               Click link                              */
    /* ───────────────────────────────────────────────────────────────────── */
    $(".link").click(function() {
        var elementToShow = $(this).attr("href");

        navigate(elementToShow);

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
    $("input[type=checkbox]").addClass("form-check-input");
});
</script>
</html>