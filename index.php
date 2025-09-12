<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<?php header('Content-Type: text/html; charset=utf-8'); ?>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
<link rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> -->
<script
  src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js">
</script>

<!-- /* =============================== Marked ============================== */ -->
<script src="https://cdn.jsdelivr.net/npm/marked/lib/marked.umd.js"></script>


<!-- /* =====================================================================───── */ -->
<!-- /*                               CODE HIGHLIGHT                               */ -->
<!-- /* =====================================================================───── */ -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/dark.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js"></script>

<!-- and it's easy to individually load additional languages -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/languages/go.min.js"></script> -->


<script src="https://cdn.jsdelivr.net/gh/WebCoder49/code-input@2.2/code-input.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/WebCoder49/code-input@2.2/code-input.min.css">

<!-- Plugins -->
<script src="js/hljs_autodetect.js"></script>
<script src="js/hljs_indent.js"></script>
<!-- /* =====================================================================───── */ -->

<!-- Axios for AJAX requests -->
<script src="js/axios.min.js"></script>

<link rel="stylesheet" href="style.css">

<!--In the <head>-->

<title>Rand</title>

<body class="theme-dark">
    <?php
        require_once("includes/_includes.php");
        require_once("includes/navbar.php");
    ?>
    <br>
    <div class="container pt-5">



        <!-------------------------------------------------------------------------------->

        <?php
foreach (glob("modules/*.php") as $module) {
  include_once($module);
}
?>

        <!-------------------------------------------------------------------------------->

    </div>
    </div> <!-- CONTAINER END -->

    <div class="modal fade" tabindex="-1" id="changelogModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content" data-backdrop="static">
                <h1 class="modal-header">Changelog</h1>
                <div class="modal-body" id="changelogMarkdown"><?= file_get_contents("CHANGELOG.md") ?></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="error"></div>


    <footer>Made with ❤️ by <a href="https://github.com/Darknetzz" target="_blank" style="color:grey;">Darknetzz</a>
    </footer>
</body>

<script src="js/rand.js"></script>



</html>