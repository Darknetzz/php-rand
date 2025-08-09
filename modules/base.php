<div id="base" class="content">

    <?php
$base_options = [
    "text"   => [
      "name" => "Base36 (text)",
      "attr" => null,
    ],
    2  => [
      "name" => "Base 2 (binary)",
      "attr" => null,
    ],
    8  => [
      "name" => "Base 8 (octal)",
      "attr" => null,
    ],
    10 => [
      "name" => "Base 10 (decimal)",
      "attr" => null,
    ],
    16 => [
      "name" => "Base 16 (hexadecimal)",
      "attr" => null,
    ],
    0 => [
      "name" => "---",
      "attr" => "disabled",
    ],
    // Add more base options as needed
];

for ($i = 32; $i <= 64; $i++) {
    $base_options[$i] = [
      "name" => "Base $i",
      "attr" => null,
    ];
}

$base_options_html = "";
foreach ($base_options as $value => $data) {
    $name               = is_array($data) ? $data['name'] : $data;
    $attr               = is_array($data) && isset($data['attr']) ? $data['attr'] : null;
    $base_options_html .= "<option value='$value' $attr>$name</option>";
}
?>

    <!-- Base -->
    <div class="card card-primary">
        <h1 class="card-header">Base</h1>
        <div class="card-body">
            <span class="description">Input any text or base encoded string below, and this tool will convert it to all
                other base formats.</span>
            <form class="form" action="gen.php" method="POST" id="base" data-action="base">
                <textarea name="base" class="form-control mb-2"
                    placeholder="Enter text or base encoded string to convert..." value="" required></textarea>

                <select name="from" class="form-select mb-2">
                    <option value="text" disabled selected>Input type (default: text/base36)...</option>
                    <?= $base_options_html ?>
                </select>

                </select>

                <select name="to" class="form-select mb-2">
                    <option value="text" selected>All</option>
                    <?= $base_options_html ?>
                </select>

                <?= submitBtn("base", "action", "Convert", "arrow-repeat") ?>
                <div class="responseDiv" id="baseresponse"></div>
            </form>
        </div>
    </div>

</div>