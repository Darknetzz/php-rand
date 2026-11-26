<?php
// Accent colour per navbar category; tiles, headings and icon boxes derive from it (--dash-accent).
$dashCategoryColors = [
    "generators"   => "#20c997",
    "cryptography" => "#4299e1",
    "encoding"     => "#ae3ec9",
    "convert"      => "#f76707",
    "math"         => "#17a2b8",
    "misc"         => "#d63384",
];

$dashCategories = array_filter($navbarItems, fn($m, $key) => $key !== "dashboard" && !empty($m["subitems"]), ARRAY_FILTER_USE_BOTH);
$dashToolCount  = array_sum(array_map(fn($m) => count($m["subitems"]), $dashCategories));

$dashFacts = [
    ["icon" => "dice-3", "title" => "Pseudo-randomness",
     "text" => "Computers are deterministic, so software usually generates pseudo-random values from a seed. Same seed, same sequence. Security-critical randomness comes from physical entropy sources."],
    ["icon" => "shield-check", "title" => "Hash functions",
     "text" => "A good hash function produces a completely different output when a single character of the input changes, which is what makes hashes useful for checking data integrity."],
    ["icon" => "dices", "title" => "Opposite faces",
     "text" => "On a standard d6 the opposite faces always add up to 7. Most polyhedral dice follow the same rule: opposite faces sum to the number of sides plus one."],
    ["icon" => "key-round", "title" => "Passphrase entropy",
     "text" => "Each word picked from the 7,776-word EFF list adds about 12.9 bits of entropy, so six random words beat most complex-looking 10-character passwords."],
];
$dashFact = $dashFacts[array_rand($dashFacts)];

$dashWhyChips = [
    ["icon" => "lock",     "label" => "Private",     "hint" => "No input data is stored on the server"],
    ["icon" => "zap",      "label" => "Instant",     "hint" => "Real-time processing"],
    ["icon" => "infinity", "label" => "No limits",   "hint" => "Use as much as you need"],
    ["icon" => "git-fork", "label" => "Open source", "hint" => "Free and transparent"],
];

$latestVersion = getLatestChangelogVersion();
$dashWhatsNew  = null;
if ($latestVersion && !empty($latestVersion['features'])) {
    $titles = array_values(array_filter(array_map('trim', array_column($latestVersion['features'], 'title'))));
    if ($titles) {
        $dashWhatsNew = [
            "version" => $latestVersion['version'],
            "text"    => count($titles) > 3
                ? implode(', ', array_slice($titles, 0, 3)) . ' and ' . (count($titles) - 3) . ' more'
                : implode(', ', $titles),
        ];
    }
}

$e = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
?>
<div id="dashboard" class="content dash">

    <!-- Header -->
    <header class="dash-hero">
        <div class="dash-hero-brand">
            <span class="dash-hero-logo"><?= icon("dice-" . mt_rand(1, 6), 2, null, 0) ?></span>
            <div>
                <h1 class="dash-hero-title">RAND<span class="text-muted">OM</span></h1>
                <p class="dash-hero-tagline">Random generation, encoding, cryptography and data tools in one place.</p>
            </div>
        </div>
        <div class="dash-hero-actions">
            <a href="<?= $e(DEMO_URL) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm <?= btnClass('secondary') ?>">
                <?= icon("external-link") ?> Live demo
            </a>
            <a href="https://github.com/Darknetzz/phprand" target="_blank" rel="noopener noreferrer" class="btn btn-sm <?= btnClass('secondary') ?>">
                <?= icon("git-fork") ?> GitHub
            </a>
        </div>
    </header>

    <!-- Search -->
    <div class="dash-search">
        <?= icon("search", 1.25, null, 0) ?>
        <input type="search" id="dashSearch" class="form-control form-control-lg" autocomplete="off" spellcheck="false"
               placeholder="Search <?= (int) $dashToolCount ?> tools…" aria-label="Search tools">
        <kbd class="dash-search-kbd" aria-hidden="true">/</kbd>
    </div>

    <?php if ($dashWhatsNew): ?>
    <!-- What's new -->
    <button type="button" class="dash-whatsnew" data-bs-toggle="modal" data-bs-target="#aboutModal" data-open-changelog>
        <span class="dash-whatsnew-badge"><?= icon("sparkles", 1, null, 0) ?> New in <?= $e($dashWhatsNew["version"]) ?></span>
        <span class="dash-whatsnew-text"><?= $e($dashWhatsNew["text"]) ?></span>
        <span class="dash-whatsnew-more">Changelog <?= icon("arrow-right", 1, null, 0) ?></span>
    </button>
    <?php endif; ?>

    <!-- Recently used (filled by renderRecentTools() in js/rand.js) -->
    <section class="dash-section dash-recent d-none" id="dashRecentSection">
        <h2 class="dash-section-title"><?= icon("history", 1, null, 0) ?> Recently used</h2>
        <div class="dash-grid" id="dashRecent"></div>
    </section>

    <!-- Tools -->
    <div id="dashTools">
    <?php foreach ($dashCategories as $catKey => $cat):
        $catName  = $cat["formalName"] ?? ucfirst($catKey);
        $catColor = $dashCategoryColors[$catKey] ?? "#6c757d";
    ?>
        <section class="dash-section dash-category" style="--dash-accent: <?= $e($catColor) ?>;">
            <h2 class="dash-section-title">
                <?= $cat["icon"] ?? icon('settings') ?> <?= $e($catName) ?>
                <span class="dash-section-count"><?= count($cat["subitems"]) ?></span>
            </h2>
            <div class="dash-grid">
            <?php foreach ($cat["subitems"] as $toolKey => $tool):
                $toolName   = $tool["name"] ?? $toolKey;
                $toolFormal = $tool["formalName"] ?? ucfirst($toolName);
                $search     = strtolower("$toolFormal $toolName $catName");
            ?>
                <a class="link dash-tool" href="#<?= $e($toolName) ?>" data-tool="<?= $e($toolName) ?>" data-search="<?= $e($search) ?>" style="--dash-accent: <?= $e($catColor) ?>;">
                    <span class="dash-tool-icon"><?= $tool["icon"] ?? icon('settings') ?></span>
                    <span class="dash-tool-name"><?= $e($toolFormal) ?></span>
                </a>
            <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
        <p class="dash-empty d-none" id="dashEmpty"><?= icon("search-x") ?> No tools match your search.</p>
    </div>

    <!-- Footer -->
    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="dash-panel h-100">
                <h3 class="dash-panel-title"><?= icon("zap", 1, null, 0) ?> Why rand</h3>
                <div class="dash-chips">
                <?php foreach ($dashWhyChips as $chip): ?>
                    <span class="dash-chip" title="<?= $e($chip["hint"]) ?>"><?= icon($chip["icon"], 1, null, 0) ?> <?= $e($chip["label"]) ?></span>
                <?php endforeach; ?>
                </div>
                <p class="dash-panel-text">
                    No input data is stored on the server.
                    <button type="button" class="btn btn-link btn-sm p-0 align-baseline" data-bs-toggle="modal" data-bs-target="#privacyModal">How data flows</button>
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="dash-panel h-100">
                <h3 class="dash-panel-title"><?= icon("lightbulb", 1, null, 0) ?> Did you know?</h3>
                <p class="dash-panel-text mb-0">
                    <strong><?= icon($dashFact["icon"], 1, null, 0) ?> <?= $e($dashFact["title"]) ?>.</strong>
                    <?= $e($dashFact["text"]) ?>
                </p>
            </div>
        </div>
    </div>

    <p class="dash-disclaimer">
        <?= icon("triangle-alert", 1, null, 0) ?>
        A personal project made for fun and learning. It may contain bugs and isn't meant as a professional tool.
        Contributions welcome on <a href="https://github.com/Darknetzz/phprand" target="_blank" rel="noopener noreferrer">GitHub</a>.
    </p>

</div>
