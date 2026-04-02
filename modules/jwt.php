<div id="jwt" class="content">
    <?php
    $jwtMode = $_POST['jwt_mode'] ?? 'decode';
    $jwtAlg = $_POST['jwt_alg'] ?? 'HS256';
    $jwtTokenVal = isset($_POST['jwt_token']) ? htmlspecialchars($_POST['jwt_token']) : "";
    $jwtSecretVal = isset($_POST['jwt_secret']) ? htmlspecialchars($_POST['jwt_secret']) : "";
    $jwtPayloadVal = isset($_POST['jwt_payload']) ? htmlspecialchars($_POST['jwt_payload']) : "{\n  \"sub\": \"1234567890\",\n  \"name\": \"John Doe\",\n  \"iat\": " . time() . "\n}";
    $jwtHeaderVal = isset($_POST['jwt_header']) ? htmlspecialchars($_POST['jwt_header']) : "{\n  \"typ\": \"JWT\"\n}";
    ?>
    <div class="alert alert-info mb-4">
        Decode, verify, or sign JWT tokens with HMAC (HS256/HS384/HS512).
    </div>
    <div class="card card-primary">
        <h1 class="card-header">🔐 JWT Inspector</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="jwtform" data-action="jwt">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label for="jwtMode" class="form-label"><strong>Mode</strong></label>
                        <select name="jwt_mode" id="jwtMode" class="form-select">
                            <option value="decode" <?= $jwtMode === 'decode' ? 'selected' : '' ?>>Decode</option>
                            <option value="verify" <?= $jwtMode === 'verify' ? 'selected' : '' ?>>Verify</option>
                            <option value="sign" <?= $jwtMode === 'sign' ? 'selected' : '' ?>>Sign</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="jwtAlg" class="form-label"><strong>Algorithm</strong></label>
                        <select name="jwt_alg" id="jwtAlg" class="form-select">
                            <option value="HS256" <?= $jwtAlg === 'HS256' ? 'selected' : '' ?>>HS256</option>
                            <option value="HS384" <?= $jwtAlg === 'HS384' ? 'selected' : '' ?>>HS384</option>
                            <option value="HS512" <?= $jwtAlg === 'HS512' ? 'selected' : '' ?>>HS512</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="jwtSecret" class="form-label"><strong>Secret</strong></label>
                        <input type="text" id="jwtSecret" name="jwt_secret" class="form-control" value="<?= $jwtSecretVal ?>" placeholder="Required for verify/sign">
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="jwtToken" class="form-label"><strong>JWT Token</strong></label>
                        <textarea id="jwtToken" name="jwt_token" class="form-control mb-3" style="min-height: 160px; resize: vertical; font-family: monospace;" placeholder="eyJhbGciOi..."><?= $jwtTokenVal ?></textarea>

                        <label for="jwtPayload" class="form-label"><strong>Payload JSON (sign mode)</strong></label>
                        <textarea id="jwtPayload" name="jwt_payload" class="form-control mb-3" style="min-height: 160px; resize: vertical; font-family: monospace;"><?= $jwtPayloadVal ?></textarea>

                        <label for="jwtHeader" class="form-label"><strong>Header JSON (optional for sign)</strong></label>
                        <textarea id="jwtHeader" name="jwt_header" class="form-control" style="min-height: 120px; resize: vertical; font-family: monospace;"><?= $jwtHeaderVal ?></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong>JWT Output</strong></label>
                        <div class="responseDiv flex-grow-1" id="jwtresponse" style="border: 2px solid #495057; padding: 20px; min-height: 480px; max-height: 640px; overflow-y: auto; border-radius: 0.5rem; font-family: monospace; white-space: pre-wrap; word-break: break-word;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 180px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">🔐</div>
                                <div>JWT decode/verify/sign output will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
                <?= submitBtn("jwt", "action", "Run JWT Tool", "shield-lock", "lg") ?>
            </form>
        </div>
    </div>
</div>
