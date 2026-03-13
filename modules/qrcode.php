<?php
$qrDefaults = [
    'qrcode' => $_POST['qrcode'] ?? '',
    'size' => $_POST['size'] ?? '300',
    'ecc' => $_POST['ecc'] ?? 'M',
    'margin' => $_POST['margin'] ?? '4',
    'fg' => $_POST['fg'] ?? '#000000',
    'bg' => $_POST['bg'] ?? '#ffffff',
];

$isSelected = static function(string $current, string $expected): string {
    return $current === $expected ? 'selected' : '';
};
?>

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
                <strong>ℹ️ About QR Codes</strong> Generate QR codes locally from any text, URL, or data. Nothing is sent to an external QR code API.
            </div>

            <form class="form" action="gen.php" method="POST" id="qrcodeForm" data-action="qrcode">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="qrcodeInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input Data</strong></label>
                        <textarea name="qrcode" class="form-control" id="qrcodeInput" style="min-height: 250px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;" placeholder="Enter text, URL, or any data to encode into a QR code..." required><?= htmlspecialchars($qrDefaults['qrcode'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></textarea>
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
                                    <option value="200" <?= $isSelected($qrDefaults['size'], '200') ?>>200x200 (Small)</option>
                                    <option value="300" <?= $isSelected($qrDefaults['size'], '300') ?>>300x300 (Medium)</option>
                                    <option value="400" <?= $isSelected($qrDefaults['size'], '400') ?>>400x400 (Large)</option>
                                    <option value="500" <?= $isSelected($qrDefaults['size'], '500') ?>>500x500 (Extra Large)</option>
                                </select>
                                <div class="form-text">Select the rendered preview and download size</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="qrcodeEcc" class="form-label"><strong>Error Correction Level</strong></label>
                                <select class="form-select form-select-lg" name="ecc" id="qrcodeEcc" style="font-family: monospace; border: 2px solid #0dcaf0;">
                                    <option value="L" <?= $isSelected($qrDefaults['ecc'], 'L') ?>>L - Low (~7% recovery)</option>
                                    <option value="M" <?= $isSelected($qrDefaults['ecc'], 'M') ?>>M - Medium (~15% recovery)</option>
                                    <option value="Q" <?= $isSelected($qrDefaults['ecc'], 'Q') ?>>Q - Quartile (~25% recovery)</option>
                                    <option value="H" <?= $isSelected($qrDefaults['ecc'], 'H') ?>>H - High (~30% recovery)</option>
                                </select>
                                <div class="form-text">Higher levels allow more data recovery if the code is damaged</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="qrcodeMargin" class="form-label"><strong>Margin</strong></label>
                                <select class="form-select form-select-lg" name="margin" id="qrcodeMargin" style="font-family: monospace; border: 2px solid #0dcaf0;">
                                    <option value="0" <?= $isSelected($qrDefaults['margin'], '0') ?>>0 - None</option>
                                    <option value="2" <?= $isSelected($qrDefaults['margin'], '2') ?>>2 - Tight</option>
                                    <option value="4" <?= $isSelected($qrDefaults['margin'], '4') ?>>4 - Default</option>
                                    <option value="8" <?= $isSelected($qrDefaults['margin'], '8') ?>>8 - Spacious</option>
                                </select>
                                <div class="form-text">Controls the quiet zone around the code</div>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="qrcodeFg" class="form-label"><strong>Foreground</strong></label>
                                <input type="color" class="form-control form-control-color w-100" name="fg" id="qrcodeFg" value="<?= htmlspecialchars($qrDefaults['fg'], ENT_QUOTES, 'UTF-8') ?>" style="min-height: 48px; border: 2px solid #0dcaf0;">
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="qrcodeBg" class="form-label"><strong>Background</strong></label>
                                <input type="color" class="form-control form-control-color w-100" name="bg" id="qrcodeBg" value="<?= htmlspecialchars($qrDefaults['bg'], ENT_QUOTES, 'UTF-8') ?>" style="min-height: 48px; border: 2px solid #0dcaf0;">
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
