<div id="browser" class="content">
    <div class="card card-primary mb-4">
        <h1 class="card-header"><?= icon("browser-chrome") ?> Browser Client Inspector</h1>
        <div class="card-body">
            <p class="text-muted mb-4">Detects browser/client capabilities and environment details. Advanced probes are optional and may expose additional network/privacy metadata.</p>
            <form class="form" action="gen.php" method="POST" id="browserInspectorForm" data-action="browser_inspect">
                <input type="hidden" name="action" value="browser_inspect">

                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label class="form-label mb-2"><strong>Detection Mode</strong></label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="includePermissions" name="include_permissions" value="1">
                            <label class="form-check-label" for="includePermissions">Include permissions status checks</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="includeWebRtc" name="include_webrtc" value="1">
                            <label class="form-check-label" for="includeWebRtc">Include WebRTC local candidate probe</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="includePublicIp" name="include_public_ip" value="1">
                            <label class="form-check-label" for="includePublicIp">Include public IP lookup (external service)</label>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="form-label mb-2"><strong>Output</strong></label>
                        <div class="small text-muted">
                            Results include:
                            <ul class="mb-0">
                                <li>Human-readable grouped sections</li>
                                <li>Raw pretty JSON (copyable)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100"><?= icon("search") ?> Detect Browser Details</button>

                <div class="responseDiv mt-4" style="min-height: 150px; border: 2px solid #495057; border-radius: 0.5rem; padding: 20px; background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(214, 51, 132, 0.08) 100%);">
                    <div style="opacity: 0.6; text-align: center; padding-top: 24px;">
                        <div style="font-size: 2.5rem;"><?= icon("browser-chrome") ?></div>
                        <div>Click <strong>Detect Browser Details</strong> to inspect this client.</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
