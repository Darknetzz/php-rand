<div id="dashboard" class="content">

    <!-- Hero Section -->
    <div class="card border-0 mb-4" style="background: linear-gradient(135deg, rgba(32, 201, 151, 0.15) 0%, rgba(13, 110, 253, 0.15) 100%);">
        <div class="card-body text-center py-5">
            <h1 class="display-3 mb-3" style="font-weight: 700;">
                <?= icon("dice-".mt_rand(1,6), 3) ?> RAND
            </h1>
            <p class="lead text-muted mb-4" style="font-size: 1.3rem;">
                Your Swiss Army Knife for Random Generation, Encoding, Cryptography & Data Transformation
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="#gen_string" class="link btn btn-primary btn-lg">
                    <?= icon("braces-asterisk") ?> Start Generating
                </a>
                <a href="#hash" class="link btn btn-outline-primary btn-lg">
                    <?= icon("shield-lock") ?> Explore Crypto
                </a>
                <a href="https://github.com/Darknetzz/phprand" target="_blank" class="btn btn-outline-secondary btn-lg">
                    <?= icon("github") ?> View on GitHub
                </a>
            </div>
        </div>
    </div>

    <!-- New Feature Alert -->
    <?php
    $latestVersion = getLatestChangelogVersion();
    if ($latestVersion && !empty($latestVersion['features'])) {
        $firstFeature = $latestVersion['features'][0];
        $featureTitle = isset($firstFeature['title']) ? trim($firstFeature['title']) : '';
        
        // Build description - if multiple features, list them
        if (count($latestVersion['features']) > 1) {
            $featureTitles = array_filter(array_column($latestVersion['features'], 'title'));
            if (count($featureTitles) <= 3) {
                $featureDesc = implode(', ', $featureTitles);
            } else {
                $featureDesc = implode(', ', array_slice($featureTitles, 0, 2)) . ', and more!';
            }
        } else {
            // Single feature - use its description or title
            $featureDesc = !empty($firstFeature['description']) 
                ? trim($firstFeature['description']) 
                : $featureTitle;
        }
        
        // Only show if we have a valid title
        if (!empty($featureTitle)) {
    ?>
    <div class="alert alert-info d-flex align-items-center mb-4" style="border-left: 4px solid #0dcaf0;">
        <div class="flex-shrink-0">
            <?= icon("stars", 2, "#0dcaf0") ?>
        </div>
        <div class="ms-3">
            <h4 class="alert-heading mb-2">✨ New in <?= htmlspecialchars($latestVersion['version']) ?>: <?= htmlspecialchars($featureTitle) ?>!</h4>
            <p class="mb-0">
                <?= htmlspecialchars($featureDesc) ?>
            </p>
        </div>
    </div>
    <?php 
        }
    } 
    ?>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <?php
        $totalTools = 0;
        foreach ($navbarItems as $module) {
            if (!empty($module["subitems"])) {
                $totalTools += count($module["subitems"]);
            } else {
                $totalTools++;
            }
        }
        
        $stats = [
            ["icon" => "tools", "count" => $totalTools, "label" => "Tools Available", "color" => "primary"],
            ["icon" => "shield-lock", "count" => count($navbarItems["cryptography"]["subitems"]), "label" => "Crypto Tools", "color" => "success"],
            ["icon" => "braces-asterisk", "count" => count($navbarItems["generators"]["subitems"]), "label" => "Generators", "color" => "info"],
            ["icon" => "file-binary", "count" => count($navbarItems["encoding"]["subitems"]), "label" => "Encoders", "color" => "warning"],
        ];
        
        foreach ($stats as $stat) {
            echo '
            <div class="col-6 col-md-3">
                <div class="card text-center border-0" style="background: rgba(var(--tblr-'.$stat["color"].'-rgb), 0.1);">
                    <div class="card-body">
                        <div class="text-'.$stat["color"].'" style="font-size: 2rem; margin-bottom: 0.5rem;">
                            '.icon($stat["icon"], 2).'
                        </div>
                        <h2 class="mb-1" style="font-weight: 700; font-size: 2rem;">'.$stat["count"].'</h2>
                        <div class="text-muted small">'.$stat["label"].'</div>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>

    <!-- Feature Highlights -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100 border-primary" style="border-width: 2px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0"><?= icon("shuffle") ?> Smart Random Data</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Generate contextual test data instantly with our new random data buttons:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><?= icon("envelope", 1, "#20c997") ?> <strong>Emails:</strong> <code>user123@example.com</code></li>
                        <li class="mb-2"><?= icon("link-45deg", 1, "#0dcaf0") ?> <strong>URLs:</strong> <code>https://demo.io/abc123</code></li>
                        <li class="mb-2"><?= icon("hdd-network", 1, "#6f42c1") ?> <strong>IPs:</strong> <code>192.168.1.100</code></li>
                        <li class="mb-2"><?= icon("file-earmark-code", 1, "#fd7e14") ?> <strong>JSON:</strong> Valid structured data</li>
                        <li class="mb-2"><?= icon("code-slash", 1, "#d63384") ?> <strong>Code:</strong> JavaScript snippets</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-success" style="border-width: 2px;">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0"><?= icon("lightning-charge") ?> Key Features</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3" style="background: rgba(32, 201, 151, 0.1); border-radius: 0.5rem;">
                                <div class="mb-2"><?= icon("lock-fill", 2, "#20c997") ?></div>
                                <strong>Client-Side</strong>
                                <div class="small text-muted">No data leaves your browser</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3" style="background: rgba(13, 110, 253, 0.1); border-radius: 0.5rem;">
                                <div class="mb-2"><?= icon("lightning-fill", 2, "#0d6efd") ?></div>
                                <strong>Instant</strong>
                                <div class="small text-muted">Real-time processing</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3" style="background: rgba(253, 126, 20, 0.1); border-radius: 0.5rem;">
                                <div class="mb-2"><?= icon("cpu", 2, "#fd7e14") ?></div>
                                <strong>No Limits</strong>
                                <div class="small text-muted">Use as much as you need</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3" style="background: rgba(111, 66, 193, 0.1); border-radius: 0.5rem;">
                                <div class="mb-2"><?= icon("github", 2, "#6f42c1") ?></div>
                                <strong>Open Source</strong>
                                <div class="small text-muted">Free & transparent</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Tools -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="card-title mb-0"><?= icon("grid-3x3-gap") ?> Available Tools</h2>
        </div>
        <div class="card-body">
            <div class="row g-3">
            <?php
            foreach ($navbarItems as $moduleName => $module) {
                $name       = $module["name"] ?? $moduleName;
                $formalName = $module["formalName"] ?? ucfirst($name);
                $icon       = $module["icon"] ?? icon('gear');

                if ($name === "dashboard") {
                    continue;
                }

                $colors = [
                    "generators" => "#20c997",
                    "cryptography" => "#0d6efd",
                    "encoding" => "#6f42c1",
                    "convert" => "#fd7e14",
                    "misc" => "#d63384"
                ];
                $color = $colors[$name] ?? "#6c757d";

                echo '
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100" style="border-left: 4px solid '.$color.';">
                        <div class="card-body">
                            <h4 class="card-title mb-3" style="color: '.$color.';">'.$icon.' '.$formalName.'</h4>';

                if (!empty($module["subitems"])) {
                    echo '<ul class="list-unstyled mb-0">';
                    foreach ($module["subitems"] as $subitemName => $subitem) {
                        $subName       = $subitem["name"] ?? $subitemName;
                        $subFormalName = $subitem["formalName"] ?? ucfirst($subName);
                        $subIcon       = $subitem["icon"] ?? icon('gear');
                        echo '<li class="mb-2">
                            <a class="link text-decoration-none" href="#'.$subName.'" style="color: inherit;">
                                '.$subIcon.' '.$subFormalName.'
                            </a>
                        </li>';
                    }
                    echo '</ul>';
                }
                echo '
                        </div>
                    </div>
                </div>';
            }
            ?>
            </div>
        </div>
    </div>

    <!-- Fun Facts -->
    <div class="card border-0" style="background: linear-gradient(135deg, rgba(111, 66, 193, 0.1) 0%, rgba(214, 51, 132, 0.1) 100%);">
        <div class="card-body">
            <h3 class="mb-3"><?= icon("lightbulb", 1.5, "#6f42c1") ?> Did You Know?</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3" style="background: rgba(255,255,255,0.05); border-radius: 0.5rem;">
                        <h5 class="text-primary mb-2"><?= icon("dice-3") ?> Pseudo-Randomness</h5>
                        <p class="text-muted mb-0">
                            True randomness doesn't exist in computers. What we call "random" is actually 
                            pseudo-randomness generated by algorithms using seed values. This allows for 
                            reproducible results when using the same seed!
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3" style="background: rgba(255,255,255,0.05); border-radius: 0.5rem;">
                        <h5 class="text-success mb-2"><?= icon("shield-check") ?> Hash Functions</h5>
                        <p class="text-muted mb-0">
                            A good hash function produces vastly different outputs for even tiny input changes. 
                            Changing just one character should completely alter the hash, making them perfect 
                            for verifying data integrity!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disclaimer -->
    <div class="alert alert-warning mt-4 d-flex align-items-start" style="border-left: 4px solid #ffc107;">
        <div class="flex-shrink-0 me-3">
            <?= icon("exclamation-triangle-fill", 2, "#ffc107") ?>
        </div>
        <div>
            <h4 class="alert-heading mb-2">Disclaimer</h4>
            <p class="mb-0">
                This is a personal project created for fun and learning. While functional, it may contain bugs 
                and is not intended as a professional tool. Use at your own discretion, and feel free to contribute 
                on <a href="https://github.com/Darknetzz/phprand" target="_blank" class="alert-link">GitHub</a>!
            </p>
        </div>
    </div>

</div>