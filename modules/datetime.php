<?php
$unitSelector = function($inputName = "time") {
    $units = [
        "s" => "seconds",
        "i" => "minutes",
        "h" => "hours",
        "d" => "days",
        "w" => "weeks",
        "M" => "months",
        "y" => "years"
    ];
    $options = "<select name='$inputName' class='form-select form-select-lg' style='font-family: monospace; border: 2px solid #495057;'>";
    foreach ($units as $key => $value) {
        $options .= '<option value="'.$key.'">'.$value.'</option>';
    }
    $options .= "</select>";
    return $options;
};

$timeZoneSelector = function($inputName = "timezone", $extraClass = "timezone-select") {
    $timezones = DateTimeZone::listIdentifiers();
    $serverTz = date_default_timezone_get() ?: 'UTC';
    $options = "<select name='$inputName' class='form-select form-select-lg $extraClass' style='font-family: monospace; border: 2px solid #495057; max-height: 400px;'>";
    foreach ($timezones as $timezone) {
        $offset_seconds  = (new DateTime("now", new DateTimeZone($timezone)))->getOffset();
        if ($offset_seconds === 0) {
            $offset_format = "UTC";
        } else {
            $offset_hours = $offset_seconds / 3600;
            $sign = $offset_hours > 0 ? '+' : '-';
            $offset_format = "UTC" . $sign . abs($offset_hours);
        }
        $selected = $timezone === $serverTz ? ' selected' : '';
        $options .= '<option value="'.$timezone.'"'.$selected.'>'.$timezone.' ('.$offset_format.')</option>';
    }
    $options .= "</select>";
    return $options;
};

$defaultReferenceLocal = (new DateTimeImmutable('now'))->format('Y-m-d\TH:i');
?>

<div id="datetime" class="content">

    <!-- Subnav -->
    <nav class="module-subnav" aria-label="Date & Time navigation">
        <a class="subnav-pill" data-subtab="converter" href="#datetime/converter"><?= icon("shuffle") ?> Time Converter</a>
        <a class="subnav-pill" data-subtab="relative" href="#datetime/relative"><?= icon("hourglass-split") ?> Relative Time</a>
        <a class="subnav-pill" data-subtab="current" href="#datetime/current"><?= icon("clock") ?> Current Time</a>
    </nav>

    <!-- TIME CONVERTER -->
    <div class="subnav-panel" data-panel="converter">
        <div class="card card-primary">
            <h1 class="card-header">⏱️ Time Unit Converter</h1>
            <div class="card-body">
                <p class="text-muted mb-4">Easily convert time values between seconds, minutes, hours, days, weeks, months, and years.</p>
                <form class="form" action="gen.php" method="POST" id="datetimeForm" data-action="datetime">
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label mb-3"><strong>From</strong></label>
                            <input type="number" name="time" class="form-control form-control-lg" placeholder="Enter a number" style="font-family: monospace; border: 2px solid #495057;" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label mb-3"><strong>Unit</strong></label>
                            <?= $unitSelector("timefrom_unit") ?>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <?= submitBtn("datetime", "action", "⏱️ Convert", "shuffle", "lg") ?>
                    </div>

                    <div class="responseDiv"></div>
                </form>
            </div>
        </div>
    </div>

    <!-- RELATIVE TIME -->
    <div class="subnav-panel" data-panel="relative">
        <div class="card card-primary">
            <h1 class="card-header"><?= icon("hourglass-split") ?> Relative Time Calculator</h1>
            <div class="card-body">
                <p class="text-muted mb-4">Turn absolute dates into “3 hours ago”, evaluate relative expressions like <code>+2 days</code>, or compute the difference between two moments.</p>
                <form class="form" action="gen.php" method="POST" id="relativeTimeForm" data-action="relative_time">
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-lg-6">
                            <label class="form-label mb-2" for="relativeMode"><strong>Mode</strong></label>
                            <select name="relative_mode" id="relativeMode" class="form-select form-select-lg" style="font-family: monospace; border: 2px solid #495057;">
                                <option value="to_relative">Absolute → Relative</option>
                                <option value="from_relative">Relative → Absolute</option>
                                <option value="diff">Difference between two times</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label class="form-label mb-2"><strong>Timezone</strong></label>
                            <?= $timeZoneSelector("relative_timezone", "") ?>
                        </div>
                    </div>

                    <div class="relative-mode-panel" data-relative-mode="to_relative">
                        <div class="row g-4 mb-4">
                            <div class="col-12 col-lg-6">
                                <label class="form-label mb-2" for="relativeInstant"><strong>Date / time or unix timestamp</strong></label>
                                <input type="text" name="relative_instant" id="relativeInstant" class="form-control form-control-lg" placeholder="2026-09-20 12:00:00 or 1726828800" style="font-family: monospace; border: 2px solid #495057;" autocomplete="off">
                                <div class="form-text mt-1">Accepts ISO dates, <code>datetime-local</code> values, unix seconds, or milliseconds.</div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <label class="form-label mb-2" for="relativeReferenceTo"><strong>Reference (optional)</strong></label>
                                <input type="text" name="relative_reference" id="relativeReferenceTo" class="form-control form-control-lg relative-reference-input" placeholder="Leave empty for now" value="" style="font-family: monospace; border: 2px solid #495057;" autocomplete="off">
                                <div class="form-text mt-1">Compare against this instant instead of now. Example: <code><?= htmlspecialchars($defaultReferenceLocal, ENT_QUOTES, 'UTF-8') ?></code></div>
                            </div>
                        </div>
                    </div>

                    <div class="relative-mode-panel d-none" data-relative-mode="from_relative">
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <div class="btn-group" role="group" aria-label="Relative input style">
                                    <input type="radio" class="btn-check" name="relative_input_style" id="relativeStyleExpression" value="expression" checked>
                                    <label class="btn btn-outline-secondary" for="relativeStyleExpression">Expression</label>
                                    <input type="radio" class="btn-check" name="relative_input_style" id="relativeStyleOffset" value="offset">
                                    <label class="btn btn-outline-secondary" for="relativeStyleOffset">Amount + unit</label>
                                </div>
                            </div>
                        </div>
                        <div class="relative-from-style" data-from-style="expression">
                            <div class="row g-4 mb-4">
                                <div class="col-12 col-lg-6">
                                    <label class="form-label mb-2" for="relativeExpression"><strong>Relative expression</strong></label>
                                    <input type="text" name="relative_expression" id="relativeExpression" class="form-control form-control-lg" placeholder="2 days ago / +3 hours / next Monday" style="font-family: monospace; border: 2px solid #495057;" autocomplete="off">
                                    <div class="form-text mt-1">Uses PHP/strtotime-style relative formats.</div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label class="form-label mb-2" for="relativeReferenceFrom"><strong>Reference (optional)</strong></label>
                                    <input type="text" name="relative_reference" id="relativeReferenceFrom" class="form-control form-control-lg relative-reference-input" placeholder="Leave empty for now" disabled style="font-family: monospace; border: 2px solid #495057;" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="relative-from-style d-none" data-from-style="offset">
                            <div class="row g-4 mb-4">
                                <div class="col-6 col-md-3">
                                    <label class="form-label mb-2" for="relativeAmount"><strong>Amount</strong></label>
                                    <input type="number" name="relative_amount" id="relativeAmount" class="form-control form-control-lg" step="any" min="0" value="2" style="font-family: monospace; border: 2px solid #495057;">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label mb-2" for="relativeUnit"><strong>Unit</strong></label>
                                    <select name="relative_unit" id="relativeUnit" class="form-select form-select-lg" style="font-family: monospace; border: 2px solid #495057;">
                                        <option value="seconds">seconds</option>
                                        <option value="minutes">minutes</option>
                                        <option value="hours">hours</option>
                                        <option value="days" selected>days</option>
                                        <option value="weeks">weeks</option>
                                        <option value="months">months</option>
                                        <option value="years">years</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label mb-2" for="relativeDirection"><strong>Direction</strong></label>
                                    <select name="relative_direction" id="relativeDirection" class="form-select form-select-lg" style="font-family: monospace; border: 2px solid #495057;">
                                        <option value="ago">ago</option>
                                        <option value="from_now" selected>from now / after</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label mb-2" for="relativeReferenceOffset"><strong>Reference (optional)</strong></label>
                                    <input type="text" name="relative_reference" id="relativeReferenceOffset" class="form-control form-control-lg relative-reference-input" placeholder="Leave empty for now" disabled style="font-family: monospace; border: 2px solid #495057;" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative-mode-panel d-none" data-relative-mode="diff">
                        <div class="row g-4 mb-4">
                            <div class="col-12 col-lg-6">
                                <label class="form-label mb-2" for="relativeFrom"><strong>From</strong></label>
                                <input type="text" name="relative_from" id="relativeFrom" class="form-control form-control-lg" placeholder="2024-01-01 00:00:00" style="font-family: monospace; border: 2px solid #495057;" autocomplete="off">
                            </div>
                            <div class="col-12 col-lg-6">
                                <label class="form-label mb-2" for="relativeTo"><strong>To</strong></label>
                                <input type="text" name="relative_to" id="relativeTo" class="form-control form-control-lg" placeholder="2026-09-20 16:00:00" style="font-family: monospace; border: 2px solid #495057;" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4 flex-wrap">
                        <?= submitBtn("relative_time", "action", "Calculate", "hourglass-split", "lg") ?>
                    </div>

                    <div class="responseDiv"></div>
                </form>
            </div>
        </div>
    </div>

    <!-- TIMEZONE & CURRENT TIME -->
    <div class="subnav-panel" data-panel="current">
        <div class="card card-primary">
            <h1 class="card-header">🌍 Current Time</h1>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-12 col-lg-8">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Select Timezone</strong></label>
                        <?= $timeZoneSelector("timezone") ?>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Current Time</strong></label>
                        <div style="padding: 15px; background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(0, 184, 255, 0.08) 100%); border: 2px solid #0dcaf0; border-radius: 0.5rem; font-family: monospace; font-size: 1rem;">
                            <div class="timezone" style="font-weight: bold; color: #0dcaf0;"></div>
                            <div class="datetime" style="font-weight: bold; font-size: 1.1rem; margin-top: 8px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
