<div id="pem_openssh" class="content">
    <div class="card card-primary">
        <h1 class="card-header">🔁 PEM / OpenSSH Converter</h1>
        <div class="card-body">
            <p class="text-muted mb-4">Convert public keys between PEM and OpenSSH formats. OpenSSH → PEM uses host <code>ssh-keygen</code> when available.</p>
            <form class="form" action="gen.php" method="POST" id="pemOpenSshForm" data-action="pem_openssh_convert">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label for="convertMode" class="form-label"><strong>Conversion Mode</strong></label>
                        <select class="form-select form-select-lg" id="convertMode" name="convert_mode">
                            <option value="pem_to_openssh" selected>PEM -> OpenSSH</option>
                            <option value="openssh_to_pem">OpenSSH -> PEM</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="convertComment" class="form-label"><strong>SSH Comment (PEM -> OpenSSH)</strong></label>
                        <input type="text" class="form-control form-control-lg" id="convertComment" name="ssh_comment" value="generated-by-phprand">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="publicPemInput" class="form-label"><strong>Public Key (PEM)</strong></label>
                    <textarea class="form-control" id="publicPemInput" name="public_pem" rows="8" placeholder="-----BEGIN PUBLIC KEY-----"></textarea>
                </div>
                <div class="mb-4">
                    <label for="opensshInput" class="form-label"><strong>Public Key (OpenSSH)</strong></label>
                    <textarea class="form-control" id="opensshInput" name="openssh_public" rows="4" placeholder="ssh-rsa AAAA... comment"></textarea>
                </div>

                <div class="d-flex gap-3 flex-wrap mb-4">
                    <?= submitBtn("pem_openssh_convert", "action", "Convert Key", "arrow-left-right", "lg") ?>
                </div>

                <label class="form-label mb-2"><strong>Output</strong></label>
                <div class="responseDiv" id="pemOpenSshFormresponse" style="border: 2px solid #495057; padding: 20px; min-height: 220px; border-radius: 0.5rem;">
                    <div style="opacity: 0.55;">Converted key output and download buttons will appear here.</div>
                </div>
            </form>
        </div>
    </div>
</div>
