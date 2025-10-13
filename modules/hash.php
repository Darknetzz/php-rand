<div id="hash" class="content">
    <div class="card card-primary">
        <h1 class="card-header">Hasher</h1>
        <div class="card-body">
            <span class="description">Input any text in the input below, and it will generate MD5, SHA1, SHA256, SHA512 hashes for you.</span>
            <form class="form" action="gen.php" method="POST" id="hasher" data-action="hasher">
                <textarea name="hash" class="form-control mb-2" placeholder="Input string here"><?php if (isset($_POST['hash'])) echo htmlspecialchars($_POST['hash']); ?></textarea>
                Algorithm:
                <select name="hashalgo" class="form-select">
                    <option value='all'>All available</option>
                    <option disabled>---------</option>
                    <?php
                    foreach (hash_algos() as $algo) {
                        echo "<option value='$algo'>$algo</option>";
                    }
                    ?>
                </select>
                <?= submitBtn("hasher", "action", "Hash", "key-fill") ?>
                <div class="responseDiv" id="hasherresponse"></div>
            </form>
        </div>
    </div>
</div>