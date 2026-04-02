<div id="shellcheck" class="content">
    <div class="card card-primary">
        <h1 class="card-header"><?= icon("terminal") ?> ShellCheck</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong>Lint shell scripts before you run them.</strong><br>
                Paste a shell script to get structured ShellCheck diagnostics with severity, rule IDs, and highlighted locations.
            </div>

            <form class="form" action="gen.php" method="POST" id="shellcheckForm" data-action="shellcheck">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-xl-6">
                        <label for="shellcheckScript" class="form-label mb-3"><strong style="font-size: 1.1rem;">Shell Script</strong></label>
                        <textarea
                            name="shellcheck_script"
                            id="shellcheckScript"
                            class="form-control"
                            placeholder="#!/usr/bin/env bash&#10;for file in *.txt; do&#10;  echo $file&#10;done"
                            style="min-height: 420px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;"
                            required
                        ><?= htmlspecialchars($_POST['shellcheck_script'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        <div class="form-text mt-2">The script is linted on the server using a temporary file and is not persisted by the app.</div>
                    </div>

                    <div class="col-12 col-xl-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Diagnostics</strong></label>
                        <div class="responseDiv flex-grow-1" style="border: 2px solid #495057; padding: 20px; min-height: 420px; max-height: 760px; overflow-y: auto; background: linear-gradient(135deg, rgba(255, 193, 7, 0.08) 0%, rgba(220, 53, 69, 0.08) 100%); border-radius: 0.5rem;">
                            <div style="opacity: 0.55; text-align: center; padding-top: 150px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;"><?= icon("terminal", 2) ?></div>
                                <div>ShellCheck findings will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-warning mb-4" style="background: rgba(255, 193, 7, 0.05);">
                    <div class="card-header">
                        <strong><?= icon("sliders") ?> Lint Options</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="shellcheckFilename" class="form-label"><strong>Filename (Optional)</strong></label>
                                <input
                                    type="text"
                                    name="shellcheck_filename"
                                    id="shellcheckFilename"
                                    class="form-control"
                                    placeholder="deploy.sh"
                                    style="font-family: monospace; border: 2px solid #495057;"
                                    value="<?= htmlspecialchars($_POST['shellcheck_filename'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                >
                                <div class="form-text">Used for display and temp file extension hints.</div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="shellcheckShell" class="form-label"><strong>Shell Dialect</strong></label>
                                <select name="shellcheck_shell" id="shellcheckShell" class="form-select" style="border: 2px solid #495057;">
                                    <option value="auto">Auto detect</option>
                                    <option value="bash">bash</option>
                                    <option value="sh">sh</option>
                                    <option value="dash">dash</option>
                                    <option value="ksh">ksh</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="shellcheckSeverity" class="form-label"><strong>Minimum Severity</strong></label>
                                <select name="shellcheck_severity" id="shellcheckSeverity" class="form-select" style="border: 2px solid #495057;">
                                    <option value="style">style</option>
                                    <option value="info" selected>info</option>
                                    <option value="warning">warning</option>
                                    <option value="error">error</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <?= submitBtn("shellcheck", "action", "Lint Script", "terminal", "lg") ?>
                </div>
            </form>
        </div>
    </div>
</div>
