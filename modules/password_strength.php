<div id="password_strength" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Password Strength Checker</h1>
        <div class="card-body">
            <div class="alert alert-info d-flex align-items-start gap-2" role="note">
                <?= icon("shield-check") ?>
                <div><strong>Your password never leaves this page.</strong> It is checked in your browser with <a href="https://github.com/dropbox/zxcvbn" target="_blank" rel="noopener">zxcvbn</a> and is never sent to the server, saved or added to the history.</div>
            </div>
            <form id="password_strength_form" data-no-random-buttons onsubmit="return false;" autocomplete="off">
                <label class="form-label" for="pwstrength_input"><strong>Password</strong></label>
                <div class="input-group input-group-lg mb-3">
                    <input type="password" id="pwstrength_input" class="form-control font-monospace" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" data-lpignore="true" data-1p-ignore placeholder="Type a password to check">
                    <button type="button" class="btn <?= btnClass('secondary') ?>" id="pwstrength_toggle" aria-pressed="false" title="Show password"><?= icon("eye") ?></button>
                </div>
            </form>

            <div id="pwstrength_status" class="text-muted small mb-3" aria-live="polite"></div>

            <div id="pwstrength_result" class="d-none" aria-live="polite">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="pwstrength-meter flex-grow-1" aria-hidden="true">
                        <span></span><span></span><span></span><span></span><span></span>
                    </div>
                    <strong id="pwstrength_label" class="pwstrength-label"></strong>
                </div>
                <div class="small text-muted mb-3" id="pwstrength_guesses"></div>

                <div id="pwstrength_feedback" class="mb-3"></div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-3">
                        <caption class="text-start fw-bold" style="caption-side: top; color: var(--bs-body-color);">Estimated time to crack</caption>
                        <tbody id="pwstrength_times"></tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <caption class="text-start fw-bold" style="caption-side: top; color: var(--bs-body-color);">What zxcvbn found</caption>
                        <thead><tr><th>Part</th><th>Pattern</th><th>Guesses</th></tr></thead>
                        <tbody id="pwstrength_patterns"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
