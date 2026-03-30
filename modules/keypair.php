<div id="keypair" class="content">
    <div class="card card-primary">
        <h1 class="card-header">🔐 Private/Public Key Generator</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                Generate asymmetric keypairs in PEM format. Supports RSA, ECDSA, and Ed25519.
            </div>

            <div class="client-crypto-generator-banner mb-3" aria-live="polite"></div>

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
                    <input
                        type="text"
                        name="passphrase"
                        id="keypairPassphrase"
                        class="form-control form-control-lg"
                        placeholder="Leave empty for unencrypted private key"
                        data-original-placeholder="Leave empty for unencrypted private key"
                    >
                    <div class="form-text">
                        Passphrase protection is applied in server/auto modes. In client-only mode this field is disabled.
                    </div>
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

    <div class="card card-primary mt-4">
        <div class="card-header">
            <h2 class="card-title mb-0">✍️ Sign or verify a message</h2>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">
                Sign arbitrary text with a PEM private key, or verify a base64 signature with the matching PEM public key. Uses OpenSSL (<span class="text-nowrap">SHA-256</span> for RSA, curve-appropriate hashes for ECDSA, <span class="text-nowrap">SHA-512</span> for Ed25519). Keys are processed on the server only for this request.
            </p>
            <form class="form" action="gen.php" method="POST" id="keypairSignForm" data-action="keypair_sign_verify">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label for="keypairSignMode" class="form-label"><strong>Mode</strong></label>
                        <select name="keypair_sign_mode" id="keypairSignMode" class="form-select form-select-lg">
                            <option value="sign" selected>Sign</option>
                            <option value="verify">Verify</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="keypairMessage" class="form-label"><strong>Message</strong></label>
                    <textarea class="form-control font-monospace" id="keypairMessage" name="keypair_message" rows="6" placeholder="Exact bytes to sign or that were signed"></textarea>
                </div>
                <div class="mb-3 keypair-sign-only">
                    <label for="keypairPrivatePem" class="form-label"><strong>Private key (PEM)</strong></label>
                    <textarea class="form-control font-monospace" id="keypairPrivatePem" name="keypair_private_pem" rows="8" placeholder="-----BEGIN PRIVATE KEY-----"></textarea>
                </div>
                <div class="mb-4 keypair-sign-only">
                    <label for="keypairPrivatePassphrase" class="form-label"><strong>Private key passphrase</strong></label>
                    <input type="text" class="form-control form-control-lg" id="keypairPrivatePassphrase" name="keypair_private_passphrase" placeholder="If encrypted" autocomplete="off">
                </div>
                <div class="mb-3 keypair-verify-only d-none">
                    <label for="keypairPublicPem" class="form-label"><strong>Public key (PEM)</strong></label>
                    <textarea class="form-control font-monospace" id="keypairPublicPem" name="keypair_public_pem" rows="6" placeholder="-----BEGIN PUBLIC KEY-----"></textarea>
                </div>
                <div class="mb-4 keypair-verify-only d-none">
                    <label for="keypairSignatureB64" class="form-label"><strong>Signature (base64)</strong></label>
                    <textarea class="form-control font-monospace" id="keypairSignatureB64" name="keypair_signature_b64" rows="4" placeholder="Paste the base64 signature"></textarea>
                </div>
                <div class="d-flex gap-3 flex-wrap mb-4">
                    <?= submitBtn("keypair_sign_verify", "action", "Run", "pencil-square", "lg") ?>
                </div>
                <label class="form-label mb-2"><strong>Sign / verify output</strong></label>
                <div class="responseDiv" id="keypairSignFormresponse" style="border: 2px solid #495057; padding: 20px; min-height: 120px; border-radius: 0.5rem;">
                    <div style="opacity: 0.55;">Signature or verification result appears here.</div>
                </div>
            </form>
        </div>
    </div>
</div>
