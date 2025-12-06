<div id="base" class="content">

    <?php
$base_options = [
    "text"   => [
      "name" => "Text",
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
    32 => [
      "name" => "Base 32",
      "attr" => null,
    ],
    64 => [
      "name" => "Base 64",
      "attr" => null,
    ],
    0 => [
      "name" => "---",
      "attr" => "disabled",
    ],
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
        <h1 class="card-header">Base Converter</h1>
        <div class="card-body">
            <span class="description">Convert between different base systems (binary, octal, decimal, hexadecimal, base64, etc.)</span>
            <hr>
            <form class="form" action="gen.php" method="POST" id="base" data-action="base">
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <label for="baseInput" class="form-label"><strong>Input</strong></label>
                        <textarea name="base" id="baseInput" class="form-control" style="min-height: 200px; resize: vertical; font-family: monospace;"
                            placeholder="Enter text or base encoded string to convert..." required></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label"><strong>Output</strong></label>
                        <div class="responseDiv flex-grow-1" id="baseresponse" style="border: 1px solid #dee2e6; padding: 15px; min-height: 200px; max-height: 500px; overflow-y: auto; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; white-space: pre-wrap; word-break: break-word;">Result will appear here...</div>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="fromBase" class="form-label"><strong>Input Type</strong></label>
                        <select name="from" id="fromBase" class="form-select">
                            <option value="text" selected>Text</option>
                            <?= $base_options_html ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="toBase" class="form-label"><strong>Output Type</strong></label>
                        <select name="to" id="toBase" class="form-select">
                            <option value="64" selected>Base 64</option>
                            <?= $base_options_html ?>
                        </select>
                    </div>
                </div>

                <hr>

                <?= submitBtn("base", "action", "Convert", "arrow-repeat") ?>
            </form>
        </div>
    </div>

</div>