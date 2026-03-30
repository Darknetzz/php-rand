<div id="ssh_keygen" class="content">
    <?php
    $sshKeygenPath = '';
    if (function_exists('shell_exec')) {
        $sshKeygenPath = trim((string) @shell_exec('command -v ssh-keygen 2>/dev/null'));
    }
    $sshKeygenAvailable = $sshKeygenPath !== '';
    ?>
    <div class="card card-primary">
        <h1 class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>🧷 SSH Key Generator</span>
            <?php if ($sshKeygenAvailable): ?>
                <span class="badge bg-success text-white" title="<?= htmlspecialchars($sshKeygenPath, ENT_QUOTES, 'UTF-8') ?>">
                    <?= icon('shield-check') ?> ssh-keygen detected
                </span>
            <?php else: ?>
                <span class="badge bg-warning text-dark">
                    <?= icon('shield-exclamation') ?> ssh-keygen not detected
                </span>
            <?php endif; ?>
        </h1>
        <div class="card-body">
            <div class="alert alert-warning mb-4">
                Generate PEM keypairs for SSH usage. Also outputs true OpenSSH public keys when supported by the selected algorithm/runtime.
            </div>

            <div class="client-crypto-generator-banner mb-3" aria-live="polite"></div>

            <form class="form" action="gen.php" method="POST" id="sshKeygenForm" data-action="ssh_keygen">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-3">
                        <label for="sshGenerationMode" class="form-label"><strong>Generation Mode</strong></label>
                        <select name="generation_mode" id="sshGenerationMode" class="form-select form-select-lg">
                            <option value="auto" selected>Auto (Client preferred)</option>
                            <option value="client">Client-side only (WebCrypto)</option>
                            <option value="server">Server-side only (OpenSSL)</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="sshAlgorithm" class="form-label"><strong>Algorithm</strong></label>
                        <select name="algorithm" id="sshAlgorithm" class="form-select form-select-lg">
                            <option value="rsa">RSA</option>
                            <option value="ecdsa">ECDSA</option>
                            <option value="ed25519" selected>Ed25519 (Recommended)</option>
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
                    <input
                        type="text"
                        name="passphrase"
                        id="sshPassphrase"
                        class="form-control form-control-lg"
                        placeholder="Leave empty for unencrypted private key"
                        data-original-placeholder="Leave empty for unencrypted private key"
                    >
                    <div class="form-text">
                        Passphrase protection is applied in server/auto modes. In client-only mode this field is disabled.
                    </div>
                </div>

                <div class="d-flex gap-3 flex-wrap mb-4">
                    <?= submitBtn("ssh_keygen", "action", "Generate SSH Keys", "key-fill", "lg") ?>
                </div>

                <label class="form-label mb-2"><strong>Output</strong></label>
                <div class="responseDiv" id="sshKeygenFormresponse" style="border: 2px solid #495057; padding: 20px; min-height: 220px; border-radius: 0.5rem;">
                    <div style="opacity: 0.55;">Generated PEM keys, OpenSSH public key lines, and download buttons will appear here.</div>
                </div>
            </form>

            <hr class="my-5 border-secondary">

            <h2 class="h4 mb-3">🔎 Verify keys</h2>
            <p class="text-muted mb-4">
                Check that PEM keys parse in OpenSSL, optionally confirm a public key matches a private key, and run <code>ssh-keygen -l</code> on an OpenSSH public line when <code>ssh-keygen</code> is available. Paste any combination of fields.
            </p>
            <form class="form" action="gen.php" method="POST" id="sshVerifyForm" data-action="ssh_key_verify">
                <div class="mb-3">
                    <label for="verifyPublicPem" class="form-label"><strong>Public key (PEM)</strong></label>
                    <textarea class="form-control font-monospace" id="verifyPublicPem" name="verify_public_pem" rows="5" placeholder="-----BEGIN PUBLIC KEY-----"></textarea>
                </div>
                <div class="mb-3">
                    <label for="verifyOpensshPublic" class="form-label"><strong>Public key (OpenSSH, one line)</strong></label>
                    <textarea class="form-control font-monospace" id="verifyOpensshPublic" name="verify_openssh_public" rows="2" placeholder="ssh-ed25519 AAAA... comment"></textarea>
                </div>
                <div class="mb-3">
                    <label for="verifyPrivatePem" class="form-label"><strong>Private key (PEM)</strong></label>
                    <textarea class="form-control font-monospace" id="verifyPrivatePem" name="verify_private_pem" rows="6" placeholder="-----BEGIN PRIVATE KEY-----"></textarea>
                </div>
                <div class="mb-4">
                    <label for="verifyPrivatePassphrase" class="form-label"><strong>Private key passphrase</strong></label>
                    <input type="text" class="form-control form-control-lg" id="verifyPrivatePassphrase" name="verify_private_passphrase" placeholder="If the private key is encrypted" autocomplete="off">
                </div>
                <div class="d-flex gap-3 flex-wrap mb-4">
                    <?= submitBtn("ssh_key_verify", "action", "Verify keys", "check2-circle", "lg") ?>
                </div>
                <label class="form-label mb-2"><strong>Verification output</strong></label>
                <div class="responseDiv" id="sshVerifyFormresponse" style="border: 2px solid #495057; padding: 20px; min-height: 120px; border-radius: 0.5rem;">
                    <div style="opacity: 0.55;">Results appear here.</div>
                </div>
            </form>
        </div>
    </div>
</div>
