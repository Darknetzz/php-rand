<div id="ssh_keygen" class="content">
    <div class="card card-primary">
        <h1 class="card-header">🧷 SSH Key Generator</h1>
        <div class="card-body">
            <div class="alert alert-warning mb-4">
                Generate PEM keypairs for SSH usage. You can set an SSH comment and optionally protect the private key with a passphrase.
            </div>

            <form class="form" action="gen.php" method="POST" id="sshKeygenForm" data-action="ssh_keygen">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-3">
                        <label for="sshAlgorithm" class="form-label"><strong>Algorithm</strong></label>
                        <select name="algorithm" id="sshAlgorithm" class="form-select form-select-lg">
                            <option value="all-available">All Available</option>
                            <option value="rsa">RSA</option>
                            <option value="ecdsa">ECDSA</option>
                            <option value="ed25519">Ed25519</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="sshRsaBits" class="form-label"><strong>RSA Bits</strong></label>
                        <select name="rsa_bits" id="sshRsaBits" class="form-select form-select-lg">
                            <option value="2048">2048</option>
                            <option value="3072">3072</option>
                            <option value="4096" selected>4096</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="sshCurve" class="form-label"><strong>ECDSA Curve</strong></label>
                        <select name="ecdsa_curve" id="sshCurve" class="form-select form-select-lg">
                            <option value="prime256v1" selected>prime256v1</option>
                            <option value="secp384r1">secp384r1</option>
                            <option value="secp521r1">secp521r1</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="sshComment" class="form-label"><strong>SSH Comment</strong></label>
                        <input type="text" name="ssh_comment" id="sshComment" class="form-control form-control-lg" value="generated-by-phprand">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="sshPassphrase" class="form-label"><strong>Private Key Passphrase (Optional)</strong></label>
                    <input type="text" name="passphrase" id="sshPassphrase" class="form-control form-control-lg" placeholder="Leave empty for unencrypted private key">
                </div>

                <div class="d-flex gap-3 flex-wrap mb-4">
                    <?= submitBtn("ssh_keygen", "action", "Generate SSH Keys", "key-fill", "lg") ?>
                </div>

                <label class="form-label mb-2"><strong>Output</strong></label>
                <div class="responseDiv" id="sshKeygenFormresponse" style="border: 2px solid #495057; padding: 20px; min-height: 220px; border-radius: 0.5rem;">
                    <div style="opacity: 0.55;">Generated SSH-compatible PEM keys and download buttons will appear here.</div>
                </div>
            </form>
        </div>
    </div>
</div>
