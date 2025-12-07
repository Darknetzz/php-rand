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
                
                <!-- Input/Output Section -->
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="baseInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input</strong></label>
                        <textarea name="base" id="baseInput" class="form-control" style="min-height: 300px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;"
                            placeholder="Enter text or base encoded string to convert...&#10;&#10;Example: Hello World" required></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Output</strong></label>
                        <div class="responseDiv flex-grow-1" id="baseresponse" style="border: 2px solid #495057; padding: 20px; min-height: 300px; max-height: 500px; overflow-y: auto; background: linear-gradient(135deg, rgba(0,50,0,0.15) 0%, rgba(0,100,0,0.05) 100%); border-radius: 0.5rem; font-family: monospace; font-size: 0.95rem; white-space: pre-wrap; word-break: break-word; color: #2d5016;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 100px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">⟳</div>
                                <div>Result will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conversion Options -->
                <div class="card border-primary mb-4" style="background-color: rgba(13, 110, 253, 0.05);">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="fromBase" class="form-label"><strong>Convert From:</strong></label>
                                <select name="from" id="fromBase" class="form-select form-select-lg" style="font-family: monospace; border: 2px solid #0d6efd;">
                                    <option value="text" selected>📝 Text</option>
                                    <?= $base_options_html ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="toBase" class="form-label"><strong>Convert To:</strong></label>
                                <select name="to" id="toBase" class="form-select form-select-lg" style="font-family: monospace; border: 2px solid #0d6efd;">
                                    <option value="64" selected>🔢 Base 64</option>
                                    <?= $base_options_html ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <?= submitBtn("base", "action", "Convert", "arrow-repeat") ?>
            </form>
        </div>
    </div>

</div>