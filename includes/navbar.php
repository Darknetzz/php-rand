<?php

# VAR: $navbarItems
$navbarItems = [
    "dashboard" => [
        "name"       => "dashboard",
        "formalName" => "Dashboard",
        "icon"       => icon('speedometer2')
    ],
    "generators" => [
        "name"       => "generators",
        "formalName" => "Generators",
        "icon"       => icon('braces-asterisk'),
        "subitems" => [
            "gen_number" => [
                "name"       => "gen_number",
                "formalName" => "Number Generator",
                "icon"       => icon('123')
            ],
            "gen_string" => [
                "name"       => "gen_string",
                "formalName" => "String Generator",
                "icon"       => icon('alphabet')
            ],
            "gen_image" => [
                "name"       => "gen_image",
                "formalName" => "Image Generator",
                "icon"       => icon('image')
            ],
            "qrcode" => [
                "name"       => "qrcode",
                "formalName" => "QR Code Generator",
                "icon"       => icon('qr-code')
            ],
            "gen_id" => [
                "name"       => "gen_id",
                "formalName" => "ID Generator",
                "icon"       => icon('hash')
            ],
            "spin_the_wheel" => [
                "name"       => "spin_the_wheel",
                "formalName" => "Spin Wheel",
                "icon"       => icon('slash-circle')
            ],
        ],
    ],
    "cryptography" => [
        "name"       => "cryptography",
        "formalName" => "Cryptography",
        "icon"       => icon('shield-lock'),
        "subitems" => [
            "openssl" => [
                "name"       => "openssl",
                "formalName" => "OpenSSL",
                "icon"       => icon('lock')
            ],
            "hash" => [
                "name"       => "hash",
                "formalName" => "Hashing",
                "icon"       => icon('shield-check')
            ],
            "rot" => [
                "name"       => "rot",
                "formalName" => "ROT Cipher",
                "icon"       => icon('arrow-repeat')
            ],
            "jwt" => [
                "name"       => "jwt",
                "formalName" => "JWT Inspector",
                "icon"       => icon('shield-lock')
            ],
            "keypair" => [
                "name"       => "keypair",
                "formalName" => "Private/Public Keys",
                "icon"       => icon('key-fill')
            ],
            "ssh_keygen" => [
                "name"       => "ssh_keygen",
                "formalName" => "SSH Key Generator",
                "icon"       => icon('terminal-fill')
            ],
            "csr" => [
                "name"       => "csr",
                "formalName" => "CSR Generator",
                "icon"       => icon('file-earmark-lock')
            ],
            "pem_openssh" => [
                "name"       => "pem_openssh",
                "formalName" => "PEM/OpenSSH Converter",
                "icon"       => icon('arrow-left-right')
            ],
            "crypto_diagnostics" => [
                "name"       => "crypto_diagnostics",
                "formalName" => "Crypto Diagnostics",
                "icon"       => icon('activity')
            ],
        ],
    ],
    "encoding" => [
        "name"       => "encoding",
        "formalName" => "Encoding",
        "icon"       => icon('file-binary'),
        "subitems" => [
            "base" => [
                "name"       => "base",
                "formalName" => "Base",
                "icon"       => icon('file-earmark-binary')
            ],
            "binhex" => [
                "name"       => "binhex",
                "formalName" => "Binary/Hexadecimal",
                "icon"       => icon('file-binary')
            ],
            "urlencoding" => [
                "name"       => "urlencoding",
                "formalName" => "URL Encoding",
                "icon"       => icon('link')
            ],
            "htmlentities" => [
                "name"       => "htmlentities",
                "formalName" => "HTML Entities",
                "icon"       => icon('code-slash')
            ],
        ],
    ],
    "convert" => [
        "name"      => "convert",
        "formalName" => "Text & Data",
        "icon"      => icon('arrow-left-right'),
        "subitems" => [
            "string_tools" => [
                "name"       => "string_tools",
                "formalName" => "String Tools",
                "icon"       => icon('type')
            ],
            "serialization" => [
                "name"       => "serialization",
                "formalName" => "Serialization Tools",
                "icon"       => icon('box-arrow-in-right')
            ],
            "markdown" => [
                "name"       => "markdown",
                "formalName" => "Markdown",
                "icon"       => icon('markdown')
            ],
            "minify" => [
                "name"       => "minify",
                "formalName" => "Minify",
                "icon"       => icon('file-earmark-code')
            ],
            "metaphone" => [
                "name"       => "metaphone",
                "formalName" => "Metaphone",
                "icon"       => icon("soundwave")
            ],
            "brainfuck" => [
                "name"       => "brainfuck",
                "formalName" => "Brainfuck Converter",
                "icon"       => icon('code-slash')
            ],
        ],
    ],
    "math" => [
        "name"       => "math",
        "formalName" => "Math",
        "icon"       => icon('infinity'),
        "subitems" => [
            "calculator" => [
                "name"       => "calculator",
                "formalName" => "Calculator",
                "icon"       => icon('calculator')
            ],
            "currency" => [
                "name"       => "currency",
                "formalName" => "Currency Converter",
                "icon"       => icon('currency-exchange')
            ],
            "units" => [
                "name"       => "units",
                "formalName" => "Units",
                "icon"       => icon('rulers')
            ],
            "datetime" => [
                "name"       => "datetime",
                "formalName" => "Date & Time",
                "icon"       => icon('calendar')
            ],
        ],
    ],
    "misc" => [
        "name"       => "misc",
        "formalName" => "Miscellaneous",
        "icon"       => icon('briefcase'),
        "subitems" => [
            "networking" => [
                "name"       => "networking",
                "formalName" => "Network Tools",
                "icon"       => icon('globe')
            ],
            "levenshtein" => [
                "name"       => "levenshtein",
                "formalName" => "Levenshtein",
                "icon"       => icon("intersect")
            ],
            "diff" => [
                "name"       => "diff",
                "formalName" => "Diff",
                "icon"       => icon("file-earmark-diff"),
            ],
            "regex" => [
                "name"       => "regex",
                "formalName" => "Regex Tester",
                "icon"       => icon("regex"),
            ],
            "browser" => [
                "name"       => "browser",
                "formalName" => "Browser",
                "icon"       => icon("browser-chrome"),
            ],
            "crontab" => [
                "name"       => "crontab",
                "formalName" => "Crontab",
                "icon"       => icon("calendar-event"),
            ],
            "shellcheck" => [
                "name"       => "shellcheck",
                "formalName" => "ShellCheck",
                "icon"       => icon("terminal"),
            ],
            "validators" => [
                "name"       => "validators",
                "formalName" => "Validators",
                "icon"       => icon('patch-check'),
            ],
        ],
    ],
];

$navHTML = '
<nav class="navbar ps-3 navbar-expand-md d-print-none navbar-dark bg-dark sticky-top">
  <a class="navbar-brand text-lime mx-3" href="index.php">' . icon("dice-".mt_rand(1,6)). " " . SITE_TITLE . '</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <div class="d-flex justify-content-between w-100">
    <div class="nav-left">
      <ul class="navbar-nav">';

foreach ($navbarItems as $moduleName => $module) {

    $moduleFile = APP_ROOT . DIRSEP . "modules" . DIRSEP . $moduleName . ".php";

    $name       = $module["name"] ?? $moduleName;
    $formalname = $module["formalName"] ?? ucfirst($name);
    $icon       = $module["icon"] ?? icon('gear');

    # Module has subitems
    if (!empty($module["subitems"])) {
        $navHTML .= '
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="nav' . $name . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . $icon . $formalname . '</a>
            <ul class="dropdown-menu" aria-labelledby="nav' . $name . '">';
        foreach ($module["subitems"] as $subitemName => $subitem) {
            $subName       = $subitem["name"] ?? $subitemName;
            $subFormalName = $subitem["formalName"] ?? ucfirst($subName);
            $subIcon       = $subitem["icon"] ?? icon('gear');
            $navHTML .= '
                <li>
                    <a class="dropdown-item link" href="#' . $subName . '" id="nav' . $subName . '" data-show="' . $subName . '">' . $subIcon . $subFormalName . '</a>
                </li>
            ';
        }
            $navHTML .= '
            </ul>
        </li>
        ';
        continue;
    }
    $navHTML  .= '
      <li class="nav-item">
        <a class="link nav-link" href="#' . $name . '" id="nav' . $name . '" data-show="' . $name . '">' . $icon . $formalname . '</a>
      </li>
    ';
}
$navHTML .= '
      </ul>
    </div>
    <div class="nav-right">
      <ul class="navbar-nav mx-2">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navRandSettings" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">'.icon('sliders').' Settings</a>
          <div class="dropdown-menu dropdown-menu-end p-3 shadow-sm" aria-labelledby="navRandSettings" style="min-width: 280px;">
            <div class="mb-3">
              <label class="form-label mb-1 small opacity-75" for="randPrefTheme">Theme</label>
              <select class="form-select form-select-sm" id="randPrefTheme">
                <option value="dark">Dark</option>
                <option value="light">Light</option>
              </select>
            </div>
            <div class="mb-0">
              <label class="form-label mb-1 small opacity-75" for="randPrefUiScale">Interface size</label>
              <select class="form-select form-select-sm" id="randPrefUiScale">
                <option value="0.8">Compact (80%)</option>
                <option value="0.85">Dense (85%)</option>
                <option value="0.92">Cozy (92%)</option>
                <option value="1">Standard (100%)</option>
                <option value="1.08">Large (108%)</option>
                <option value="1.16">Extra large (116%)</option>
              </select>
            </div>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" target="_blank" href="https://github.com/Darknetzz/phprand">'.icon('github').' GitHub</a>
        </li>
        <li class="nav-item" data-bs-toggle="modal" data-bs-target="#changelogModal">
          <a class="nav-link" href="javascript:void(0);">'.icon('journal-text').' Changelog</a>
        </li>
      </ul>
    </div>
  </div>
</div>
</nav>
';

echo $navHTML;