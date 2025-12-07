<!--
// ─────────────────────────────────────────────────────────────────────────────────────────────── #
//                                             OPENSSL                                             #
// ─────────────────────────────────────────────────────────────────────────────────────────────── #
-->
<div id="openssl" class="content">
    <?php
  $ciphers = "";
  foreach (openssl_get_cipher_methods() as $thiscipher) {
    $ciphers .= "<option value='$thiscipher'>$thiscipher</option>";
  }
?>

    <div class="card card-primary">
        <h1 class="card-header">🔐 OpenSSL Encryption</h1>
        <div class="card card-body">
            <div class="alert alert-warning mb-4">
                <strong>⚠️ Security Notice</strong><br>
                Encrypt and decrypt data using OpenSSL. Always use secure keys and initialization vectors in production environments.
            </div>

            <form class="form" action="gen.php" method="POST" id="openssl" data-action="openssl">
                <?php
                  $openssl   = Null;
                  $key       = Null;
                  $iv        = Null;
                  $stringVal = '';
                  if (isset($_POST['openssl'])) {
                    $openssl   = $_POST['openssl'];
                    $key       = $_POST['key'];
                    $iv        = $_POST['iv'];
                    $stringVal = htmlspecialchars($openssl);
                  }
                ?>

                <!-- Input/Output Section -->
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="opensslInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input Data</strong></label>
                        <textarea name="openssl" class="form-control" id="opensslInput" style="min-height: 250px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;" placeholder="Enter text to encrypt or decrypt..." required><?= $stringVal ?></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Output</strong></label>
                        <div class="responseDiv flex-grow-1" id="opensslresponse" style="border: 2px solid #495057; padding: 20px; min-height: 250px; max-height: 500px; overflow-y: auto; background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 152, 0, 0.05) 100%); border-radius: 0.5rem; font-family: monospace; font-size: 0.95rem; white-space: pre-wrap; word-break: break-all;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 80px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">🔒</div>
                                <div>Encrypted/Decrypted result will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuration Card -->
                <div class="card border-warning mb-4" style="background-color: rgba(255, 193, 7, 0.05);">
                    <div class="card-header bg-warning text-dark">
                        <strong>⚙️ Encryption Settings</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="cipherSelect" class="form-label"><strong>Cipher Method</strong></label>
                                <select class="form-select form-select-lg" name="cipher" id="cipherSelect" style="font-family: monospace; border: 2px solid #ffc107;">
                                    <?= $ciphers ?>
                                </select>
                                <div class="form-text">Select the encryption algorithm to use</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="keyInput" class="form-label"><strong>Encryption Key</strong></label>
                                <input type="text" name="key" class="form-control form-control-lg" id="keyInput" value="<?= htmlspecialchars($key) ?>" placeholder="Leave empty for random key" style="font-family: monospace;">
                                <div class="form-text">Optional: Custom encryption key (auto-generated if empty)</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="ivInput" class="form-label"><strong>Initialization Vector (IV)</strong></label>
                                <input type="text" name="iv" class="form-control form-control-lg" id="ivInput" value="<?= htmlspecialchars($iv) ?>" placeholder="Leave empty for random IV" style="font-family: monospace;">
                                <div class="form-text">Optional: Custom IV (auto-generated if empty)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 flex-wrap">
                    <?= submitBtn("encrypt", "tool", "🔒 Encrypt Data", "lock", "lg") ?>
                    <?= submitBtn("decrypt", "tool", "🔓 Decrypt Data", "unlock", "lg") ?>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- // ─────────────────────────────────────────────────────────────────────────────────────────────── # -->