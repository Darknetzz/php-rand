<!--
// ─────────────────────────────────────────────────────────────────────────────────────────────── #
//                                             QR CODE                                             #
// ─────────────────────────────────────────────────────────────────────────────────────────────── #
-->
<div id="qrcode" class="content">
    <div class="card card-primary">
        <h1 class="card-header">📱 QR Code Generator</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong>ℹ️ About QR Codes</strong><br>
                Generate QR codes from any text, URL, or data. QR codes can be scanned by smartphones and QR code readers to quickly access the encoded information.
            </div>

            <form class="form" action="gen.php" method="POST" id="qrcodeForm" data-action="qrcode">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="qrcodeInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input Data</strong></label>
                        <textarea name="qrcode" class="form-control" id="qrcodeInput" style="min-height: 250px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;" placeholder="Enter text, URL, or any data to encode into a QR code..." required><?php if (isset($_POST['qrcode'])) echo htmlspecialchars($_POST['qrcode'] ?? ''); ?></textarea>
                        <div class="form-text mt-2">Enter any text, URL, email, phone number, or other data to generate a QR code.</div>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">QR Code</strong></label>
                        <div class="responseDiv flex-grow-1 d-flex flex-column align-items-center justify-content-center" id="qrcoderesponse" style="border: 2px solid #495057; padding: 20px; min-height: 250px; background: linear-gradient(135deg, rgba(32, 201, 151, 0.1) 0%, rgba(13, 110, 253, 0.05) 100%); border-radius: 0.5rem;">
                            <div style="opacity: 0.5; text-align: center;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">📱</div>
                                <div>QR code will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code Options -->
                <div class="card border-info mb-4" style="background-color: rgba(13, 202, 240, 0.05);">
                    <div class="card-header bg-info text-dark">
                        <strong>⚙️ QR Code Settings</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="qrcodeSize" class="form-label"><strong>Size</strong></label>
                                <select class="form-select form-select-lg" name="size" id="qrcodeSize" style="font-family: monospace; border: 2px solid #0dcaf0;">
                                    <option value="200" selected>200x200 (Small)</option>
                                    <option value="300">300x300 (Medium)</option>
                                    <option value="400">400x400 (Large)</option>
                                    <option value="500">500x500 (Extra Large)</option>
                                </select>
                                <div class="form-text">Select the QR code image size</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="qrcodeEcc" class="form-label"><strong>Error Correction Level</strong></label>
                                <select class="form-select form-select-lg" name="ecc" id="qrcodeEcc" style="font-family: monospace; border: 2px solid #0dcaf0;">
                                    <option value="L" selected>L - Low (~7% recovery)</option>
                                    <option value="M">M - Medium (~15% recovery)</option>
                                    <option value="Q">Q - Quartile (~25% recovery)</option>
                                    <option value="H">H - High (~30% recovery)</option>
                                </select>
                                <div class="form-text">Higher levels allow more data recovery if the code is damaged</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 flex-wrap">
                    <?= submitBtn("qrcode", "action", "📱 Generate QR Code", "qr-code", "lg") ?>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- // ─────────────────────────────────────────────────────────────────────────────────────────────── # -->
