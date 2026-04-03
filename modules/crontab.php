<?php
$serverTimezone = date_default_timezone_get() ?: 'UTC';
$effectiveTimezone = isset($_POST['cron_timezone']) && $_POST['cron_timezone'] !== ''
    ? (string) $_POST['cron_timezone']
    : $serverTimezone;
$timezoneOptions = '';
foreach (DateTimeZone::listIdentifiers() as $timezone) {
    $selected = $timezone === $effectiveTimezone ? ' selected' : '';
    $timezoneOptions .= '<option value="' . htmlspecialchars($timezone, ENT_QUOTES, 'UTF-8') . '"' . $selected . '>'
        . htmlspecialchars($timezone, ENT_QUOTES, 'UTF-8') . '</option>';
}

$defaultReference = (new DateTime('now'))->format('Y-m-d\TH:i');
$postedRun = isset($_POST['cron_run_count']) ? (string) $_POST['cron_run_count'] : '';
$moreOptionsOpen = ($effectiveTimezone !== $serverTimezone)
    || ($postedRun !== '' && $postedRun !== '8')
    || !empty($_POST['cron_include_current']);
?>
<div id="crontab" class="content">
    <div class="alert alert-info mb-4">
        <strong>Cron at a glance.</strong>
        Type a standard 5-field expression (or a macro like <code>@daily</code>); results update shortly after you stop typing.
        Times use the server timezone <code><?= htmlspecialchars($serverTimezone, ENT_QUOTES, 'UTF-8') ?></code>, which is also shown in the analysis panel.
    </div>
    <div class="card card-primary">
        <h1 class="card-header"><?= icon("calendar-event") ?> Crontab Explorer</h1>
        <div class="card-body">
            <form
                class="form"
                action="gen.php"
                method="POST"
                id="crontabForm"
                data-action="crontab"
                data-server-timezone="<?= htmlspecialchars($serverTimezone, ENT_QUOTES, 'UTF-8') ?>"
            >
                <div class="row g-4 mb-4 align-items-xl-start">
                    <div class="col-12 col-xl-5">
                        <label for="crontabExpression" class="form-label mb-2"><strong>Cron expression</strong></label>
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
                        <div class="form-text mt-2 mb-3">
                            Macros (<code>@daily</code>, <code>@reboot</code>, …) and advanced tokens (<code>L</code>, <code>W</code>, <code>#</code>) are supported.
                        </div>

                        <details class="crontab-more-options border border-secondary rounded-3 mb-3" id="crontabMoreDetails"<?= $moreOptionsOpen ? ' open' : '' ?>>
                            <summary class="px-3 py-2 fw-semibold user-select-none" style="cursor: pointer;">
                                More options
                            </summary>
                            <div class="border-top border-secondary px-3 pt-3 pb-2">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="crontabTimezone" class="form-label mb-1"><strong>Timezone</strong></label>
                                        <select name="cron_timezone" id="crontabTimezone" class="form-select" style="max-height: 280px;">
                                            <?= $timezoneOptions ?>
                                        </select>
                                        <div class="form-text mt-1">Defaults to the server zone above; change this to explore another region.</div>
                                    </div>
                                    <div class="col-12">
                                        <label for="crontabRunCount" class="form-label mb-1"><strong>Upcoming runs to list</strong></label>
                                        <input
                                            type="number"
                                            min="1"
                                            max="20"
                                            step="1"
                                            name="cron_run_count"
                                            id="crontabRunCount"
                                            class="form-control"
                                            style="font-family: monospace; max-width: 8rem;"
                                            value="<?= htmlspecialchars($_POST['cron_run_count'] ?? '8', ENT_QUOTES, 'UTF-8') ?>"
                                        >
                                    </div>
                                    <div class="col-12">
                                        <label for="crontabReferenceTime" class="form-label mb-1"><strong>Reference time</strong></label>
                                        <input
                                            type="datetime-local"
                                            name="cron_reference_time"
                                            id="crontabReferenceTime"
                                            class="form-control"
                                            style="font-family: monospace; max-width: 22rem;"
                                            value="<?= htmlspecialchars($_POST['cron_reference_time'] ?? $defaultReference, ENT_QUOTES, 'UTF-8') ?>"
                                        >
                                        <div class="form-text mt-1">Evaluate “next runs” from this instant (usually leave as now).</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="cron_include_current" id="crontabIncludeCurrent" value="1" <?= !empty($_POST['cron_include_current']) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="crontabIncludeCurrent">
                                                Include the reference time when it already matches the schedule
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </details>

                        <div class="card border-secondary" style="background: rgba(255,255,255,0.03);">
                            <div class="card-header py-2">
                                <strong class="small"><?= icon("lightbulb") ?> Examples</strong>
                            </div>
                            <div class="card-body py-2 small" style="font-family: monospace;">
                                <div class="mb-1"><code>*/5 * * * *</code> — every 5 minutes</div>
                                <div class="mb-1"><code>0 0 * * 0</code> — Sundays at midnight</div>
                                <div class="mb-1"><code>30 2 1 * *</code> — 02:30 on the 1st of each month</div>
                                <div class="mb-1"><code>15 14 * * MON-FRI</code> — weekdays 14:15</div>
                                <div class="mb-0"><code>@daily</code> — once per day at midnight</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-7">
                        <label class="form-label mb-2"><strong>Schedule analysis</strong></label>
                        <div class="responseDiv pt-2">
                            <div class="text-muted text-center py-5">
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
