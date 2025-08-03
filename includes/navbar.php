<?php

# VAR: $navbarItems
$navbarItems = [
    "dashboard" => [
        "name"       => "dashboard",
        "formalName" => "Dashboard",
        "icon"       => icon('house')
    ],
    "datetime" => [
        "name"       => "datetime",
        "formalName" => "Date & Time",
        "icon"       => icon('calendar')
    ],
    "generators" => [
        "name"       => "generators",
        "formalName" => "Generators",
        "icon"       => icon('gear'),
        "subitems" => [
            "numgen" => [
                "name"       => "numgen",
                "formalName" => "Number Generator",
                "icon"       => icon('123')
            ],
            "stringgen" => [
                "name"       => "stringgen",
                "formalName" => "String Generator",
                "icon"       => icon('text')
            ],
            "logogen" => [
                "name"       => "logogen",
                "formalName" => "Logo Generator",
                "icon"       => icon('image')
            ],
        ],
    ],
    "encoding" => [
        "name"       => "encoding",
        "formalName" => "Encoding",
        "icon"       => icon('file-binary'),
        "subitems" => [
            "base64" => [
                "name"       => "base64",
                "formalName" => "Base64 Encoder/Decoder",
                "icon"       => icon('filetype-b64')
            ],
            "urlencode" => [
                "name"       => "urlencode",
                "formalName" => "URL Encoder/Decoder",
                "icon"       => icon('link')
            ],
            "htmlentities" => [
                "name"       => "htmlentities",
                "formalName" => "HTML Entities Encoder/Decoder",
                "icon"       => icon('code-slash')
            ],
            "hash" => [
                "name"       => "hash",
                "formalName" => "Hash Generator",
                "icon"       => icon('shield-lock')
            ],
        ],
    ],
    "network" => [
        "name"       => "network",
        "formalName" => "Network Tools",
        "icon"       => icon('wifi'),
        "subitems" => [
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