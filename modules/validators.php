<div id="validators" class="content">
    <div class="mb-4">
        <h1 class="h2 mb-1"><?= icon('patch-check') ?> Validators</h1>
        <p class="text-muted mb-0">JSON, YAML, PHP, and Python syntax checks (no execution).</p>
    </div>
    <?php
    $validatorsEmbed = true;
    require __DIR__ . '/syntax_validate.php';
    ?>
</div>
