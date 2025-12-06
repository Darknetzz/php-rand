<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
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


<!-- <script src="https://cdn.jsdelivr.net/gh/WebCoder49/code-input@2.2/code-input.min.js"></script> -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/WebCoder49/code-input@2.2/code-input.min.css"> -->
<script src="https://cdn.jsdelivr.net/npm/@webcoder49/code-input@2.7.1/code-input.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/@webcoder49/code-input@2.7.1/code-input.min.css" rel="stylesheet">

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

<script>
// Copy to clipboard function with fallback and explicit button target
function copyToClipboard(elementId, btnEl) {
    const element = document.getElementById(elementId);
    if (!element) return;
    const text = element.textContent || "";
    const btn = btnEl || (document.activeElement?.closest && document.activeElement.closest('button')) || null;

    const setFeedback = (ok) => {
        if (!btn) return;
        const originalText = btn.getAttribute('data-original-text') || btn.innerHTML;
        btn.setAttribute('data-original-text', originalText);
        if (ok) {
            btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-light');
        } else {
            btn.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Failed';
            btn.classList.add('btn-danger');
            btn.classList.remove('btn-outline-light');
        }
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success', 'btn-danger');
            btn.classList.add('btn-outline-light');
        }, 1600);
    };

    // Modern API path
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => setFeedback(true)).catch(() => {
            // Fallback if HTTPS or permissions blocked
            fallbackCopy(text, setFeedback);
        });
        return;
    }

    // Fallback for older browsers or non-secure origins
    fallbackCopy(text, setFeedback);
}

function fallbackCopy(text, setFeedback) {
    const ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.top = '-1000px';
    ta.style.left = '-1000px';
    document.body.appendChild(ta);
    ta.select();
    try {
        const ok = document.execCommand('copy');
        setFeedback(ok);
    } catch (e) {
        setFeedback(false);
    }
    document.body.removeChild(ta);
}
</script>

</html>