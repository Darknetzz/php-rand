<div id="keypair" class="content">
    <div class="card card-primary">
        <h1 class="card-header">🔐 Private/Public Key Generator</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                Generate asymmetric keypairs in PEM format. Supports RSA, ECDSA, and Ed25519.
            </div>

            <form class="form" action="gen.php" method="POST" id="keypairForm" data-action="keypair_generate">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label for="keypairGenerationMode" class="form-label"><strong>Generation Mode</strong></label>
                        <select name="generation_mode" id="keypairGenerationMode" class="form-select form-select-lg">
                            <option value="auto" selected>Auto (Client preferred)</option>
                            <option value="client">Client-side only (WebCrypto)</option>
                            <option value="server">Server-side only (OpenSSL)</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="keypairAlgorithm" class="form-label"><strong>Algorithm</strong></label>
                        <select name="algorithm" id="keypairAlgorithm" class="form-select form-select-lg">
                            <option value="rsa">RSA</option>
                            <option value="ecdsa">ECDSA</option>
                            <option value="ed25519" selected>Ed25519 (Recommended)</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="keypairRsaBits" class="form-label"><strong>RSA Bits</strong></label>
                        <select name="rsa_bits" id="keypairRsaBits" class="form-select form-select-lg">
                            <option value="2048">2048</option>
                            <option value="3072">3072</option>
                            <option value="4096" selected>4096</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="keypairCurve" class="form-label"><strong>ECDSA Curve</strong></label>
                        <select name="ecdsa_curve" id="keypairCurve" class="form-select form-select-lg">
                            <option value="prime256v1" selected>prime256v1</option>
                            <option value="secp384r1">secp384r1</option>
                            <option value="secp521r1">secp521r1</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="keypairPassphrase" class="form-label"><strong>Private Key Passphrase (Optional)</strong></label>
                    <input type="text" name="passphrase" id="keypairPassphrase" class="form-control form-control-lg" placeholder="Leave empty for unencrypted private key">
                </div>

                <div class="d-flex gap-3 flex-wrap mb-4">
                    <?= submitBtn("keypair_generate", "action", "Generate Keypair", "key-fill", "lg") ?>
                </div>

                <label class="form-label mb-2"><strong>Output</strong></label>
                <div class="responseDiv" id="keypairFormresponse" style="border: 2px solid #495057; padding: 20px; min-height: 220px; border-radius: 0.5rem;">
                    <div style="opacity: 0.55;">Generated private/public PEM keys and download buttons will appear here.</div>
                </div>
            </form>
        </div>
    </div>
</div>
