<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
<link rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<script defer src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> -->
<script defer
  src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js">
</script>

<!-- Marked / Highlight.js / code-input are loaded on demand in js/rand.js -->

<!-- Axios for AJAX requests -->
<script defer src="js/axios.min.js"></script>

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
include_once("modules/dashboard.php");
?>

        <!-------------------------------------------------------------------------------->

    </div>
    </div> <!-- CONTAINER END -->

    <div class="modal fade" tabindex="-1" id="changelogModal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" data-backdrop="static">
                <h1 class="modal-header">Changelog</h1>
                <div class="modal-body" id="changelogMarkdown" style="max-height: 70vh; overflow-y: auto;">
                    <div class="tool-loading">
                        <div class="spinner-border text-primary tool-loading-spinner" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="tool-loading-text">Loading changelog...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="privacyModal" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h4 mb-0" id="privacyModalLabel"><?= icon("shield-lock") ?> Privacy & Data Flow</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Inputs are submitted to the server for processing in most tools, but this app does not persist submitted input data in its own database, files, or sessions.
                    </p>

                    <h4 class="h6">Processed on server (submitted to <code>gen.php</code>)</h4>
                    <p class="text-muted small mb-3">
                        Most tools (generators, crypto, encoding, convert, and misc tools) send input to the server and return results immediately.
                    </p>

                    <h4 class="h6">Client-side only</h4>
                    <ul class="mb-3">
                        <li><code>dashboard</code> (display only)</li>
                        <li><code>markdown</code> (rendered in browser with JS)</li>
                        <li><code>gen_image</code> (opens external image generator page)</li>
                    </ul>

                    <h4 class="h6">Important note</h4>
                    <p class="mb-0">
                        Server/proxy logs may still retain request metadata depending on infrastructure configuration. This modal describes app-level behavior.
                    </p>
                </div>
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

<script defer src="js/rand.js"></script>

<script>
// Copy to clipboard function with fallback and explicit button target
function copyToClipboard(elementId, btnEl) {
    const element = document.getElementById(elementId);
    if (!element) return;
    const text = (element.textContent || "").trim();
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