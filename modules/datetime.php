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

$timeZoneSelector = function($inputName = "timezone") {
    $timezones = DateTimeZone::listIdentifiers();
    $options = "<select name='$inputName' class='form-select form-select-lg timezone-select' style='font-family: monospace; border: 2px solid #495057; max-height: 400px;'>";
    foreach ($timezones as $timezone) {
        $offset_seconds  = (new DateTime("now", new DateTimeZone($timezone)))->getOffset();
        if ($offset_seconds === 0) {
            $offset_format = "UTC";
        } else {
            $offset_hours = $offset_seconds / 3600;
            $sign = $offset_hours > 0 ? '+' : '-';
            $offset_format = "UTC" . $sign . abs($offset_hours);
        }
        $options .= '<option value="'.$timezone.'">'.$timezone.' ('.$offset_format.')</option>';
    }
    $options .= "</select>";
    return $options;
};
?>

<div id="datetime" class="content">

    <!-- TIMEZONE & CURRENT TIME -->
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

    <!-- TIME CONVERTER -->
    <div class="card card-primary">
        <h1 class="card-header">⏱️ Time Unit Converter</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong>ℹ️ Convert Between Units</strong><br>
                <span style="display: inline-block; margin-top: 4px;">Easily convert time values between seconds, minutes, hours, days, weeks, months, and years.</span>
            </div>

            <form class="form" action="gen.php" method="POST" id="datetime" data-action="datetime">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-5">
                        <label class="form-label mb-3"><strong>From</strong></label>
                        <input type="number" name="time" class="form-control form-control-lg" placeholder="Enter a number" style="font-family: monospace; border: 2px solid #495057;" required>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label mb-3"><strong>Unit</strong></label>
                        <?= $unitSelector("timefrom_unit") ?>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label mb-3"><strong>To Unit</strong></label>
                        <?= $unitSelector("timeto_unit") ?>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-4">
                    <?= submitBtn("datetime", "action", "⏱️ Convert", "shuffle", "lg") ?>
                </div>

                <div id="datetimeresponse"></div>
            </form>
        </div>
    </div>

</div>