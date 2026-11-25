<?php
$chmodClasses = ["u" => "Owner", "g" => "Group", "o" => "Others"];
$chmodPerms = ["r" => "Read", "w" => "Write", "x" => "Execute"];
$chmodSpecial = [
    "suid"   => ["Setuid", "Run as the file's owner (4000)"],
    "sgid"   => ["Setgid", "Run as the file's group; new files in a directory inherit its group (2000)"],
    "sticky" => ["Sticky", "Only the owner can delete or rename files in a directory (1000)"],
];
?>
<div id="chmod" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Chmod Calculator</h1>
        <div class="card-body">
            <span class="description">Tick permissions, or type an octal (<code>755</code>, <code>4755</code>) or symbolic (<code>rwxr-xr-x</code>) mode. All views stay in sync. Runs in your browser.</span>
            <hr>
            <form id="chmod_form" data-no-random-buttons onsubmit="return false;">
                <div class="table-responsive mb-3">
                    <table class="table table-sm chmod-grid mb-0">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <?php foreach ($chmodPerms as $label): ?>
                                    <th scope="col" class="text-center"><?= $label ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chmodClasses as $who => $whoLabel): ?>
                                <tr>
                                    <th scope="row"><?= $whoLabel ?></th>
                                    <?php foreach ($chmodPerms as $perm => $permLabel): ?>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input chmod-bit" id="chmod_<?= $who . $perm ?>" data-who="<?= $who ?>" data-perm="<?= $perm ?>" aria-label="<?= "$whoLabel $permLabel" ?>">
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Special bits</label>
                    <?php foreach ($chmodSpecial as $key => [$label, $hint]): ?>
                        <div class="form-check form-check-inline" title="<?= htmlspecialchars($hint) ?>">
                            <input type="checkbox" class="form-check-input chmod-special" id="chmod_<?= $key ?>" data-special="<?= $key ?>">
                            <label class="form-check-label" for="chmod_<?= $key ?>"><?= $label ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="chmod_octal">Octal</label>
                        <input type="text" id="chmod_octal" class="form-control form-control-lg font-monospace" value="644" maxlength="4" inputmode="numeric" autocomplete="off" spellcheck="false">
                        <div class="invalid-feedback">3 or 4 digits, each 0–7.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="chmod_symbolic">Symbolic</label>
                        <input type="text" id="chmod_symbolic" class="form-control form-control-lg font-monospace" value="rw-r--r--" maxlength="10" autocomplete="off" spellcheck="false">
                        <div class="invalid-feedback">9 characters like <code>rwxr-xr-x</code> (<code>s</code>/<code>S</code> and <code>t</code>/<code>T</code> for special bits).</div>
                    </div>
                </div>

                <div class="chmod-command">
                    <?= copyableOutput("chmod 644 file", "Command") ?>
                </div>
            </form>
        </div>
    </div>

</div>
