<?php
$unitSelector = function($inputName = "time") {
    $units = [
        "s" => "seconds",
        "i" => "minutes",
        "h" => "hours",
        "d" => "days",
        "w" => "weeks",
        "m" => "months",
        "y" => "years"
    ];
    $options = "
        <select name='$inputName' class='form-select'>
            <option value='' disabled selected>Select a unit</option>";
    foreach ($units as $key => $value) {
        $options .= '<option value="'.$key.'">'.$value.'</option>';
    }
    $options .= "</select>";
    return $options;
};

$timeZoneSelector = function($inputName = "timezone") {
    $timezones = DateTimeZone::listIdentifiers();
    $options = "
        <select name='$inputName' class='form-select timezone-select'>
            <option value='' disabled selected>Select a timezone</option>";
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

    <div class="card card-primary">
        <h1 class="card-header">Current Datetime</h1>
        <div class="card-body" style="text-align: center;">
            <table class="table table-default w-100">
                <tr class="tablehead">
                    <th>Timezone</th>
                    <th>Current Time</th>
                    <th class="w-50">Select Timezone</th>
                </tr>
                <tr>
                    <td class="timezone"></td>
                    <td class="datetime text-success"></td>
                    <td><?= $timeZoneSelector("timezone") ?></td>
                </tr>
            </table>
        </div>
    </div>


    <div class="card card-primary">
        <h1 class="card-header">Time Calculator</h1>
        <div class="card-body">
            <p class="text-warning">Work in progress! This tool allows you to add or subtract time from a given date and time.</p>
            <table class="table table-default w-50">
                <tr>
                    <th class="tablehead">
                        Start Time
                    </th>
                    <td>
                        <input type="datetime-local" name="timecalc_start" class="form-control" placeholder="Please enter a date and time" value="<?= date('Y-m-d\TH:i') ?>">
                    </td>
                </tr>
                <tr>
                    <th class="tablehead">
                        Add/Subtract
                    </th>
                    <td>
                        <select name="timecalc_action" class="form-select">
                            <option value="add">add</option>
                            <option value="subtract">subtract</option>
                        </select>
                    </td>
                </tr>
                    <th class="tablehead">
                        Units
                    </th>
                    <td>
                        <input type="number" name="timecalc_value" class="form-control" placeholder="Please enter a number">
                    </td>
                    <td>
                        <?= $unitSelector("timecalc_unit") ?>
                    </td>
                </tr>
            </table>
            <?= submitBtn("timecalc", "action", "Calculate", "calculator") ?>
        </div>
    </div>


    <div class="card card-primary">
        <h1 class="card-header">Convert Time Units</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="datetime" data-action="datetime">

                <table class="table table-default w-50">
                    <tr class="tablehead">
                        <th></th>
                        <th>Value</th>
                        <th>Unit</th>
                    </tr>

                    <tr>
                        <th class="tablehead">
                            From
                        </th>
                        <td>
                            <input type="text" name="time" class="form-control" placeholder="Please enter a number">
                        </td>
                        <td>
                            <?= $unitSelector("timefrom_unit") ?>
                        </td>
                    </tr>

                    <tr>
                        <th class="tablehead">
                            To
                        </th>
                        <!-- <td>
                            <input id="datetimeresponse" type="text" class="form-control text-cyan responseDiv" value="The converted time will appear here" disabled>
                        </td> -->
                        <td colspan="2">
                            <?= $unitSelector("timeto_unit") ?>
                        </td>
                    </tr>
                </table>

                <?= submitBtn("datetime", "action", "Convert", "shuffle") ?>

                <div class="responseDiv" id="datetimeresponse"></div>

            </form>
        </div>
    </div>
</div>