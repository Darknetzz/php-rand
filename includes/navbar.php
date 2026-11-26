<?php

# VAR: $navbarItems
$navbarItems = [
    "dashboard" => [
        "name"       => "dashboard",
        "formalName" => "Dashboard",
        "icon"       => icon('gauge')
    ],
    "generators" => [
        "name"       => "generators",
        "formalName" => "Generators",
        "icon"       => icon('braces'),
        "color"      => "#20c997",
        "subitems" => [
            "gen_number" => [
                "name"       => "gen_number",
                "formalName" => "Number Generator",
                "icon"       => icon('binary')
            ],
            "gen_string" => [
                "name"       => "gen_string",
                "formalName" => "String Generator",
                "icon"       => icon('case-lower')
            ],
            "gen_passphrase" => [
                "name"       => "gen_passphrase",
                "formalName" => "Passphrase Generator",
                "icon"       => icon('key-round')
            ],
            "gen_image" => [
                "name"       => "gen_image",
                "formalName" => "Image Generator",
                "icon"       => icon('image')
            ],
            "colour_gen" => [
                "name"       => "colour_gen",
                "formalName" => "Colour Generator",
                "icon"       => icon('palette')
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
                "icon"       => icon('ban')
            ],
            "dice_roller" => [
                "name"       => "dice_roller",
                "formalName" => "Dice Roller",
                "icon"       => icon('dices')
            ],
            "coin_flip" => [
                "name"       => "coin_flip",
                "formalName" => "Coin Flip",
                "icon"       => icon('coins')
            ],
            "card_draw" => [
                "name"       => "card_draw",
                "formalName" => "Card Draw",
                "icon"       => icon('spade')
            ],
            "magic_8ball" => [
                "name"       => "magic_8ball",
                "formalName" => "Magic 8-Ball",
                "icon"       => icon('circle-question-mark')
            ],
            "placeholder_data" => [
                "name"       => "placeholder_data",
                "formalName" => "Placeholder Data",
                "icon"       => icon('text-quote')
            ],
            "list_shuffler" => [
                "name"       => "list_shuffler",
                "formalName" => "List Shuffler",
                "icon"       => icon('shuffle')
            ],
        ],
    ],
    "cryptography" => [
        "name"       => "cryptography",
        "formalName" => "Cryptography",
        "icon"       => icon('shield-lock'),
        "color"      => "#4299e1",
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
                "icon"       => icon('refresh-cw')
            ],
            "jwt" => [
                "name"       => "jwt",
                "formalName" => "JWT Inspector",
                "icon"       => icon('shield-lock')
            ],
            "keypair" => [
                "name"       => "keypair",
                "formalName" => "Private/Public Keys",
                "icon"       => icon('key-round')
            ],
            "ssh_keygen" => [
                "name"       => "ssh_keygen",
                "formalName" => "SSH Key Generator",
                "icon"       => icon('square-terminal')
            ],
            "csr" => [
                "name"       => "csr",
                "formalName" => "CSR Generator",
                "icon"       => icon('file-lock')
            ],
            "password_strength" => [
                "name"       => "password_strength",
                "formalName" => "Password Strength",
                "icon"       => icon('shield-check')
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
        "icon"       => icon('file-digit'),
        "color"      => "#ae3ec9",
        "subitems" => [
            "base" => [
                "name"       => "base",
                "formalName" => "Base",
                "icon"       => icon('binary')
            ],
            "binhex" => [
                "name"       => "binhex",
                "formalName" => "Binary/Hexadecimal",
                "icon"       => icon('file-digit')
            ],
            "urlencoding" => [
                "name"       => "urlencoding",
                "formalName" => "URL Encoding",
                "icon"       => icon('link')
            ],
            "htmlentities" => [
                "name"       => "htmlentities",
                "formalName" => "HTML Entities",
                "icon"       => icon('code')
            ],
        ],
    ],
    "convert" => [
        "name"      => "convert",
        "formalName" => "Text & Data",
        "icon"      => icon('arrow-left-right'),
        "color"      => "#f76707",
        "subitems" => [
            "string_tools" => [
                "name"       => "string_tools",
                "formalName" => "String Tools",
                "icon"       => icon('type')
            ],
            "serialization" => [
                "name"       => "serialization",
                "formalName" => "Serialization Tools",
                "icon"       => icon('log-in')
            ],
            "markdown" => [
                "name"       => "markdown",
                "formalName" => "Markdown",
                "icon"       => icon('square-m')
            ],
            "syntax_highlight" => [
                "name"       => "syntax_highlight",
                "formalName" => "Syntax Highlighter",
                "icon"       => icon('highlighter')
            ],
            "minify" => [
                "name"       => "minify",
                "formalName" => "Minify",
                "icon"       => icon('file-code')
            ],
            "metaphone" => [
                "name"       => "metaphone",
                "formalName" => "Metaphone",
                "icon"       => icon("audio-lines")
            ],
            "brainfuck" => [
                "name"       => "brainfuck",
                "formalName" => "Brainfuck Converter",
                "icon"       => icon('code')
            ],
        ],
    ],
    "math" => [
        "name"       => "math",
        "formalName" => "Math",
        "icon"       => icon('infinity'),
        "color"      => "#17a2b8",
        "subitems" => [
            "calculator" => [
                "name"       => "calculator",
                "formalName" => "Calculator",
                "icon"       => icon('calculator')
            ],
            "currency" => [
                "name"       => "currency",
                "formalName" => "Currency Converter",
                "icon"       => icon('coins')
            ],
            "units" => [
                "name"       => "units",
                "formalName" => "Units",
                "icon"       => icon('ruler')
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
        "color"      => "#d63384",
        "subitems" => [
            "networking" => [
                "name"       => "networking",
                "formalName" => "Network Tools",
                "icon"       => icon('globe')
            ],
            "levenshtein" => [
                "name"       => "levenshtein",
                "formalName" => "Levenshtein",
                "icon"       => icon("blend")
            ],
            "diff" => [
                "name"       => "diff",
                "formalName" => "Diff",
                "icon"       => icon("file-diff"),
            ],
            "regex" => [
                "name"       => "regex",
                "formalName" => "Regex Tester",
                "icon"       => icon("regex"),
            ],
            "browser" => [
                "name"       => "browser",
                "formalName" => "Browser",
                "icon"       => icon("app-window"),
            ],
            "crontab" => [
                "name"       => "crontab",
                "formalName" => "Crontab",
                "icon"       => icon("calendar-days"),
            ],
            "shellcheck" => [
                "name"       => "shellcheck",
                "formalName" => "ShellCheck",
                "icon"       => icon("terminal"),
            ],
            "chmod" => [
                "name"       => "chmod",
                "formalName" => "Chmod Calculator",
                "icon"       => icon("file-lock"),
            ],
            "validators" => [
                "name"       => "validators",
                "formalName" => "Validators",
                "icon"       => icon('badge-check'),
            ],
        ],
    ],
];

// Sidebar lists tools A–Z within each category (the dashboard and anything else iterating $navbarItems too).
foreach ($navbarItems as &$navCategory) {
    if (!empty($navCategory["subitems"])) {
        uasort($navCategory["subitems"], fn($a, $b) => strnatcasecmp($a["formalName"] ?? $a["name"], $b["formalName"] ?? $b["name"]));
    }
}
unset($navCategory);

$navEsc = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

$navHTML = '
<aside class="rand-sidebar d-print-none" id="randSidebar">
  <div class="rand-sidebar-head">
    <a class="rand-sidebar-brand link" href="#dashboard">' . icon("dice-" . mt_rand(1, 6), 1.4, null, 0) . '<span>' . $navEsc(SITE_TITLE) . '</span></a>
    <button class="rand-sidebar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#randSidebarBody" aria-controls="randSidebarBody" aria-expanded="false" aria-label="Toggle navigation">' . icon("menu", 1.25, null, 0) . '</button>
  </div>
  <div class="collapse rand-sidebar-body" id="randSidebarBody">
    <div class="rand-sidebar-search">
      ' . icon("search", 1, null, 0) . '
      <input type="search" id="navSearch" class="form-control form-control-sm" autocomplete="off" spellcheck="false" placeholder="Filter tools…" aria-label="Filter tools">
      <kbd aria-hidden="true">/</kbd>
    </div>
    <nav class="rand-sidebar-nav" id="randSidebarNav" aria-label="Tools">';

foreach ($navbarItems as $moduleName => $module) {
    $name       = $module["name"] ?? $moduleName;
    $formalname = $module["formalName"] ?? ucfirst($name);
    $icon       = $module["icon"] ?? icon('settings');

    # Top-level module without subitems (Dashboard)
    if (empty($module["subitems"])) {
        $navHTML .= '
      <a class="link rand-nav-link rand-nav-top" href="#' . $navEsc($name) . '" id="nav' . $navEsc($name) . '" data-show="' . $navEsc($name) . '">' . $icon . '<span>' . $navEsc($formalname) . '</span></a>';
        continue;
    }

    $accent = $module["color"] ?? "#6c757d";
    $navHTML .= '
      <div class="rand-nav-group" data-group="' . $navEsc($name) . '" data-accent="' . $navEsc($accent) . '" style="--nav-accent: ' . $navEsc($accent) . ';">
        <button type="button" class="rand-nav-group-toggle" data-nav-group-toggle aria-expanded="true" aria-controls="navGroup-' . $navEsc($name) . '">
          ' . $icon . '<span class="rand-nav-group-name">' . $navEsc($formalname) . '</span>
          <span class="rand-nav-group-count">' . count($module["subitems"]) . '</span>
          ' . icon("chevron-down", 1, null, 0) . '
        </button>
        <ul class="rand-nav-group-items" id="navGroup-' . $navEsc($name) . '">';
    foreach ($module["subitems"] as $subitemName => $subitem) {
        $subName       = $subitem["name"] ?? $subitemName;
        $subFormalName = $subitem["formalName"] ?? ucfirst($subName);
        $subIcon       = $subitem["icon"] ?? icon('settings');
        $search        = strtolower("$subFormalName $subName $formalname");
        $navHTML .= '
          <li><a class="link rand-nav-link" href="#' . $navEsc($subName) . '" id="nav' . $navEsc($subName) . '" data-show="' . $navEsc($subName) . '" data-name="' . $navEsc($subFormalName) . '" data-search="' . $navEsc($search) . '">' . $subIcon . '<span>' . $navEsc($subFormalName) . '</span></a></li>';
    }
    $navHTML .= '
        </ul>
      </div>';
}

$navHTML .= '
      <p class="rand-nav-empty d-none" id="navEmpty">No tools match.</p>
    </nav>
    <div class="rand-sidebar-foot">
      <div class="dropup">
        <button type="button" class="rand-nav-link w-100" id="navRandSettings" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">' . icon('sliders') . '<span>Settings</span></button>
        <div class="dropdown-menu p-3 shadow-sm rand-settings-menu" aria-labelledby="navRandSettings">
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
          <div class="mt-3 mb-0">
            <label class="form-label mb-1 small opacity-75" for="randPrefSpaceScale">Item spacing</label>
            <select class="form-select form-select-sm" id="randPrefSpaceScale">
              <option value="0.85">Tight (85%)</option>
              <option value="0.95">Dense (95%)</option>
              <option value="1">Standard (100%)</option>
              <option value="1.1">Comfortable (110%)</option>
              <option value="1.2">Relaxed (120%)</option>
            </select>
          </div>
        </div>
      </div>
      <a class="rand-nav-link" target="_blank" rel="noopener noreferrer" href="https://github.com/Darknetzz/phprand">' . icon('git-fork') . '<span>GitHub</span></a>
      <a class="rand-nav-link" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#aboutModal" id="navAbout">' . icon('info') . '<span>About</span></a>
    </div>
  </div>
</aside>
';

echo $navHTML;
