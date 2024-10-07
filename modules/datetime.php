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
?>

<div id="datetime" class="content">
    <div class="card card-primary">
        <h1 class="card-header">Unit converter</h1>
        <div class="card-body">
            <span class="text-muted">Convert time units</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="datetime" data-action="datetime">

                <table class="table table-default w-50">
                    <tr class="table-secondary">
                        <th></th>
                        <th>Value</th>
                        <th>Unit</th>
                    </tr>

                    <tr>
                        <th class="table-secondary">
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
                        <th class="table-secondary">
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