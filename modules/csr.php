<div id="csr" class="content">
    <div class="alert alert-info mb-4">
        Generate a certificate signing request (CSR) and matching private/public key material in PEM format.
    </div>
    <div class="card card-primary">
        <h1 class="card-header">📄 CSR Generator</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="csrForm" data-action="csr_generate" data-no-random-buttons>
                <p class="text-secondary small fw-semibold text-uppercase mb-3 border-bottom pb-2">Key type</p>
                <div class="row row-cols-1 row-cols-lg-2 g-4 mb-4">
                    <div class="col">
                        <label for="csrAlgorithm" class="form-label"><strong>Algorithm</strong></label>
                        <select name="algorithm" id="csrAlgorithm" class="form-select form-select-lg">
                            <option value="rsa" selected>RSA</option>
                            <option value="ecdsa">ECDSA</option>
                            <option value="ed25519">Ed25519</option>
                        </select>
                    </div>
                    <div class="col" id="csrOptRsa">
                        <label for="csrRsaBits" class="form-label"><strong>RSA key size</strong></label>
                        <select name="rsa_bits" id="csrRsaBits" class="form-select form-select-lg">
                            <option value="2048">2048</option>
                            <option value="3072">3072</option>
                            <option value="4096" selected>4096</option>
                        </select>
                    </div>
                    <div class="col d-none" id="csrOptEcdsa">
                        <label for="csrCurve" class="form-label"><strong>ECDSA curve</strong></label>
                        <select name="ecdsa_curve" id="csrCurve" class="form-select form-select-lg">
                            <option value="prime256v1" selected>prime256v1</option>
                            <option value="secp384r1">secp384r1</option>
                            <option value="secp521r1">secp521r1</option>
                        </select>
                    </div>
                </div>

                <p class="text-secondary small fw-semibold text-uppercase mb-3 border-bottom pb-2">Certificate subject (DN)</p>
                <div class="row row-cols-1 row-cols-lg-2 g-4 mb-4">
                    <div class="col">
                        <label for="csrCN" class="form-label"><strong>Common Name (CN)</strong></label>
                        <input type="text" name="csr_cn" id="csrCN" class="form-control form-control-lg" placeholder="example.com" required>
                    </div>
                    <div class="col">
                        <label for="csrEmail" class="form-label"><strong>Email (optional)</strong></label>
                        <input type="email" name="csr_email" id="csrEmail" class="form-control form-control-lg" placeholder="admin@example.com">
                    </div>
                    <div class="col">
                        <label for="csrO" class="form-label"><strong>Organization (O)</strong></label>
                        <input type="text" name="csr_o" id="csrO" class="form-control form-control-lg" placeholder="Example Inc">
                    </div>
                    <div class="col">
                        <label for="csrOU" class="form-label"><strong>Organizational unit (OU)</strong></label>
                        <input type="text" name="csr_ou" id="csrOU" class="form-control form-control-lg" placeholder="Engineering">
                    </div>
                    <div class="col">
                        <label for="csrC" class="form-label"><strong>Country (C)</strong></label>
                        <input type="text" name="csr_c" id="csrC" class="form-control form-control-lg" maxlength="2" placeholder="US">
                    </div>
                    <div class="col">
                        <label for="csrST" class="form-label"><strong>State / province (ST)</strong></label>
                        <input type="text" name="csr_st" id="csrST" class="form-control form-control-lg" placeholder="California">
                    </div>
                    <div class="col">
                        <label for="csrL" class="form-label"><strong>Locality (L)</strong></label>
                        <input type="text" name="csr_l" id="csrL" class="form-control form-control-lg" placeholder="San Francisco">
                    </div>
                </div>

                <p class="text-secondary small fw-semibold text-uppercase mb-3 border-bottom pb-2">Private key protection</p>
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <label for="csrPassphrase" class="form-label"><strong>Passphrase (optional)</strong></label>
                        <input type="text" name="passphrase" id="csrPassphrase" class="form-control form-control-lg" placeholder="Leave empty for an unencrypted private key">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12 pt-2 border-top border-secondary border-opacity-25">
                        <div class="d-grid d-sm-flex justify-content-sm-end">
                            <?= submitBtn("csr_generate", "action", "Generate CSR", "file-earmark-lock", "lg") ?>
                        </div>
                    </div>
                </div>

                <label class="form-label mb-2"><strong>Output</strong></label>
                <div class="responseDiv" id="csrFormresponse" style="border: 2px solid #495057; padding: 20px; min-height: 220px; border-radius: 0.5rem;">
                    <div style="opacity: 0.55;">CSR and key files with download buttons will appear here.</div>
                </div>
            </form>
        </div>
    </div>
</div>
