<div id="csr" class="content">
    <div class="card card-primary">
        <h1 class="card-header">📄 CSR Generator</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                Generate a certificate signing request (CSR) and matching private/public key material in PEM format.
            </div>

            <form class="form" action="gen.php" method="POST" id="csrForm" data-action="csr_generate">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label for="csrAlgorithm" class="form-label"><strong>Algorithm</strong></label>
                        <select name="algorithm" id="csrAlgorithm" class="form-select form-select-lg">
                            <option value="rsa" selected>RSA</option>
                            <option value="ecdsa">ECDSA</option>
                            <option value="ed25519">Ed25519</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="csrRsaBits" class="form-label"><strong>RSA Bits</strong></label>
                        <select name="rsa_bits" id="csrRsaBits" class="form-select form-select-lg">
                            <option value="2048">2048</option>
                            <option value="3072">3072</option>
                            <option value="4096" selected>4096</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="csrCurve" class="form-label"><strong>ECDSA Curve</strong></label>
                        <select name="ecdsa_curve" id="csrCurve" class="form-select form-select-lg">
                            <option value="prime256v1" selected>prime256v1</option>
                            <option value="secp384r1">secp384r1</option>
                            <option value="secp521r1">secp521r1</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label for="csrCN" class="form-label"><strong>Common Name (CN)</strong></label>
                        <input type="text" name="csr_cn" id="csrCN" class="form-control form-control-lg" placeholder="example.com" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="csrEmail" class="form-label"><strong>Email (Optional)</strong></label>
                        <input type="email" name="csr_email" id="csrEmail" class="form-control form-control-lg" placeholder="admin@example.com">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="csrO" class="form-label"><strong>Organization (O)</strong></label>
                        <input type="text" name="csr_o" id="csrO" class="form-control form-control-lg" placeholder="Example Inc">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="csrOU" class="form-label"><strong>Org Unit (OU)</strong></label>
                        <input type="text" name="csr_ou" id="csrOU" class="form-control form-control-lg" placeholder="Engineering">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="csrC" class="form-label"><strong>Country (C)</strong></label>
                        <input type="text" name="csr_c" id="csrC" class="form-control form-control-lg" maxlength="2" placeholder="US">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="csrST" class="form-label"><strong>State/Province (ST)</strong></label>
                        <input type="text" name="csr_st" id="csrST" class="form-control form-control-lg" placeholder="California">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="csrL" class="form-label"><strong>Locality (L)</strong></label>
                        <input type="text" name="csr_l" id="csrL" class="form-control form-control-lg" placeholder="San Francisco">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="csrPassphrase" class="form-label"><strong>Private Key Passphrase (Optional)</strong></label>
                    <input type="text" name="passphrase" id="csrPassphrase" class="form-control form-control-lg" placeholder="Leave empty for unencrypted private key">
                </div>

                <div class="d-flex gap-3 flex-wrap mb-4">
                    <?= submitBtn("csr_generate", "action", "Generate CSR", "file-earmark-lock", "lg") ?>
                </div>

                <label class="form-label mb-2"><strong>Output</strong></label>
                <div class="responseDiv" id="csrFormresponse" style="border: 2px solid #495057; padding: 20px; min-height: 220px; border-radius: 0.5rem;">
                    <div style="opacity: 0.55;">CSR and key files with download buttons will appear here.</div>
                </div>
            </form>
        </div>
    </div>
</div>
