<?php

# VAR: $navbarItems
$navbarItems = [
    // "dashboard" => [
    //     "name"       => "dashboard",
    //     "formalName" => "Dashboard",
    //     "icon"       => icon('speedometer2')
    // ],
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
            "spin_the_wheel" => [
                "name"       => "spin_the_wheel",
                "formalName" => "Spin Wheel",
                "icon"       => icon('slash-circle')
            ],
        ],
    ],
    "encryption" => [
        "name"       => "encryption",
        "formalName" => "Encryption",
        "icon"       => icon('shield-lock'),
        "subitems" => [
            "openssl" => [
                "name"       => "openssl",
                "formalName" => "OpenSSL Encryption/Decryption",
                "icon"       => icon('lock')
            ],
            "hash" => [
                "name"       => "hash",
                "formalName" => "Hashing",
                "icon"       => icon('shield-check')
            ],
            "rot" => [
                "name"       => "rot",
                "formalName" => "ROT Encoder/Decoder",
                "icon"       => icon('arrow-repeat')
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
        "formalName" => "Convert",
        "icon"      => icon('arrow-left-right'),
        "subitems" => [
            "json" => [
                "name"       => "json",
                "formalName" => "JSON Tools",
                "icon"       => icon('file-code')
            ],
            "xml" => [
                "name"       => "xml",
                "formalName" => "XML Tools",
                "icon"       => icon('file-code')
            ],
            "csv" => [
                "name"       => "csv",
                "formalName" => "CSV Tools",
                "icon"       => icon('file-spreadsheet')
            ],
            "yaml" => [
                "name"       => "yaml",
                "formalName" => "YAML Tools",
                "icon"       => icon('file-text')
            ],
        ],
    ],
    "misc" => [
        "name"       => "misc",
        "formalName" => "Miscellaneous",
        "icon"       => icon('briefcase'),
        "subitems" => [
            "calculator" => [
                "name"       => "calculator",
                "formalName" => "Calculator",
                "icon"       => icon('calculator')
            ],
            "serialization" => [
                "name"       => "serialization",
                "formalName" => "Serialization Tools",
                "icon"       => icon('box-arrow-in-right')
            ],
            "string_tools" => [
                "name"       => "string_tools",
                "formalName" => "String Tools",
                "icon"       => icon('type')
            ],
            "datetime" => [
                "name"       => "datetime",
                "formalName" => "Date & Time",
                "icon"       => icon('calendar')
            ],
            "networking" => [
                "name"       => "networking",
                "formalName" => "IP Tools",
                "icon"       => icon('globe')
            ],
        ],
    ],
];

$navHTML = '
<nav class="navbar ps-3 navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">'.SITE_TITLE.'</a>
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
$navHTML .= "</div>
    <div class='nav-right'>";

$navHTML .= '
      <ul class="navbar-nav mx-2">
        <li class="nav-item">
          <a class="nav-link" target="_blank" href="https://github.com/Darknetzz/phprand">'.icon('github').' GitHub</a>
        </li>
        <li class="nav-item" data-bs-toggle="modal" data-bs-target="#changelogModal">
          <a class="nav-link" href="javascript:void(0);">'.icon('journal-text').' Changelog</a>
        </li>
      </ul>
    </ul>
  </div>
</nav>
</div>
';

echo $navHTML;