<?php
$serverTimezone = date_default_timezone_get() ?: 'UTC';
$timezoneOptions = '';
foreach (DateTimeZone::listIdentifiers() as $timezone) {
    $selected = $timezone === $serverTimezone ? ' selected' : '';
    $timezoneOptions .= '<option value="' . htmlspecialchars($timezone, ENT_QUOTES, 'UTF-8') . '"' . $selected . '>'
        . htmlspecialchars($timezone, ENT_QUOTES, 'UTF-8') . '</option>';
}

$defaultReference = (new DateTime('now'))->format('Y-m-d\TH:i');
?>
<div id="crontab" class="content">
    <div class="card card-primary">
        <h1 class="card-header"><?= icon("calendar-event") ?> Crontab Explorer</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong>Understand cron schedules quickly.</strong><br>
                Validate standard 5-field cron expressions, see a human-readable explanation, and inspect upcoming run times in your chosen timezone.
            </div>

            <form class="form" action="gen.php" method="POST" id="crontabForm" data-action="crontab">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-xl-5">
                        <label for="crontabExpression" class="form-label mb-3"><strong style="font-size: 1.1rem;">Cron Expression</strong></label>
                        <input
                            type="text"
                            name="cron_expression"
                            id="crontabExpression"
                            class="form-control form-control-lg"
                            placeholder="*/15 9-17 * * MON-FRI"
                            style="font-family: monospace; border: 2px solid #495057;"
                            value="<?= htmlspecialchars($_POST['cron_expression'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >
                        <div class="form-text mt-2">
                            Supports macros like <code>@daily</code> and advanced cron syntax such as <code>L</code>, <code>W</code>, and <code>#</code>.
                        </div>
                        <div class="card border-secondary mt-3" style="background: rgba(255,255,255,0.03);">
                            <div class="card-body py-3">
                                <div class="small text-uppercase text-muted mb-2">Quick Meaning</div>
                                <div class="small" id="crontabLivePreview">
                                    <div><code>0 0 1 * *</code> = once a month</div>
                                    <div><code>0 0 * * 0</code> = once a week</div>
                                    <div><code>0 0 * * *</code> = once a day</div>
                                    <div><code>0 * * * *</code> = once an hour</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-12 col-md-6">
                                <label for="crontabTimezone" class="form-label mb-2"><strong>Timezone</strong></label>
                                <select name="cron_timezone" id="crontabTimezone" class="form-select form-select-lg" style="border: 2px solid #495057; max-height: 350px;">
                                    <?= $timezoneOptions ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="crontabRunCount" class="form-label mb-2"><strong>Next Runs</strong></label>
                                <input
                                    type="number"
                                    min="1"
                                    max="20"
                                    step="1"
                                    name="cron_run_count"
                                    id="crontabRunCount"
                                    class="form-control form-control-lg"
                                    style="border: 2px solid #495057; font-family: monospace;"
                                    value="<?= htmlspecialchars($_POST['cron_run_count'] ?? '8', ENT_QUOTES, 'UTF-8') ?>"
                                >
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="crontabReferenceTime" class="form-label mb-2"><strong>Reference Time (Optional)</strong></label>
                            <input
                                type="datetime-local"
                                name="cron_reference_time"
                                id="crontabReferenceTime"
                                class="form-control form-control-lg"
                                style="border: 2px solid #495057; font-family: monospace;"
                                value="<?= htmlspecialchars($_POST['cron_reference_time'] ?? $defaultReference, ENT_QUOTES, 'UTF-8') ?>"
                            >
                            <div class="form-text mt-2">The schedule is evaluated from this point forward. Leave it as “now” to inspect the current schedule.</div>
                        </div>

                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="cron_include_current" id="crontabIncludeCurrent" value="1" <?= !empty($_POST['cron_include_current']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="crontabIncludeCurrent">
                                <strong>Include the reference time if it already matches</strong>
                            </label>
                        </div>

                        <div class="card border-secondary mt-4" style="background: rgba(255,255,255,0.03);">
                            <div class="card-header">
                                <strong><?= icon("lightbulb") ?> Examples</strong>
                            </div>
                            <div class="card-body" style="font-family: monospace;">
                                <div><code>*/5 * * * *</code> every 5 minutes</div>
                                <div><code>0 0 * * 0</code> every Sunday at midnight</div>
                                <div><code>30 2 1 * *</code> monthly at 02:30 on day 1</div>
                                <div><code>15 14 * * MON-FRI</code> weekdays at 14:15</div>
                                <div><code>@daily</code> once per day at midnight</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-7 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Schedule Analysis</strong></label>
                        <div class="responseDiv flex-grow-1" style="border: 2px solid #495057; padding: 20px; min-height: 480px; max-height: 760px; overflow-y: auto; background: linear-gradient(135deg, rgba(13, 110, 253, 0.08) 0%, rgba(32, 201, 151, 0.08) 100%); border-radius: 0.5rem;">
                            <div style="opacity: 0.55; text-align: center; padding-top: 170px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;"><?= icon("calendar-event", 2) ?></div>
                                <div>Cron validation, explanation, and upcoming run times will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <?= submitBtn("crontab", "action", "Analyze Schedule", "calendar-event", "lg") ?>
                </div>
            </form>
        </div>
    </div>
</div>
