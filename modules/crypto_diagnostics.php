<div id="crypto_diagnostics" class="content">
    <div class="alert alert-warning mb-4">
        Run runtime checks for OpenSSL key generation support and OpenSSH export compatibility, plus basic client-side (browser) crypto capabilities.
    </div>
    <div class="card card-primary">
        <h1 class="card-header">🧪 Crypto Diagnostics</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="cryptoDiagnosticsForm" data-action="crypto_diagnostics">
                <div class="d-flex gap-3 flex-wrap mb-4">
                    <?= submitBtn("crypto_diagnostics", "action", "Run Diagnostics", "activity", "lg") ?>
                </div>
                <label class="form-label mb-2"><strong>Output</strong></label>
                <div class="responseDiv" id="cryptoDiagnosticsFormresponse" style="border: 2px solid #495057; padding: 20px; min-height: 220px; border-radius: 0.5rem;">
                    <div style="opacity: 0.55;">Runtime capability checks will appear here.</div>
                </div>
            </form>
        </div>
    </div>
</div>
