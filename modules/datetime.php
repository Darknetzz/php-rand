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
        <h1 class="card-header">Current Datetime</h1>
        <div class="card-body">
            <pre><h2 class="datetime-current text-success text-center"></h2></pre>
        </div>
    </div>


    <div class="card card-primary">
        <h1 class="card-header">Time Calculator</h1>
        <div class="card-body">
            <?= alert("Coming soon...", "info") ?>
        </div>
    </div>


    <div class="card card-primary">
        <h1 class="card-header">Datetime</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="datetime" data-action="datetime">

                <h2>Convert Time Units</h2>
                <table class="table table-default w-50">
                    <tr class="tablehead">
                        <th></th>
                        <th>Value</th>
                        <th>Unit</th>
                    </tr>

                    <tr>
                        <td class="tablehead">
                            From
                        </td>
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