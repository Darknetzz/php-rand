<div id="hash" class="content">
    <div class="card card-primary">
        <h1 class="card-header">🔑 Hash Generator</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong>ℹ️ About Hashing</strong><br>
                Generate cryptographic hashes from any input text. Supports MD5, SHA1, SHA256, SHA512, and many more algorithms.
            </div>

            <form class="form" action="gen.php" method="POST" id="hasher" data-action="hasher">
                <!-- Input/Output Section -->
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="hashInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input Text</strong></label>
                        <textarea name="hash" class="form-control" id="hashInput" style="min-height: 300px; resize: vertical; font-family: monospace; font-size: 0.95rem; border: 2px solid #495057;" placeholder="Enter text to hash..." required><?php if (isset($_POST['hash'])) echo htmlspecialchars($_POST['hash']); ?></textarea>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Hash Output</strong></label>
                        <div class="responseDiv flex-grow-1" id="hasherresponse" style="border: 2px solid #495057; padding: 20px; min-height: 300px; max-height: 500px; overflow-y: auto; background: linear-gradient(135deg, rgba(32, 201, 151, 0.1) 0%, rgba(13, 110, 253, 0.05) 100%); border-radius: 0.5rem; font-family: monospace; font-size: 0.9rem; white-space: pre-wrap; word-break: break-all;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 100px;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">#️⃣</div>
                                <div>Hash values will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Algorithm Selection -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-8">
                        <label for="hashalgoSelect" class="form-label"><strong>Hash Algorithm</strong></label>
                        <select name="hashalgo" class="form-select form-select-lg" id="hashalgoSelect" style="font-family: monospace; border: 2px solid #20c997;">
                            <option value='all'>🔄 All Available Algorithms</option>
                            <option disabled>─────────────────────</option>
                            <?php
                            foreach (hash_algos() as $algo) {
                                echo "<option value='$algo'>$algo</option>";
                            }
                            ?>
                        </select>
                        <div class="form-text">Select a specific algorithm or generate all hashes at once</div>
                    </div>
                    <div class="col-12 col-md-4 d-flex align-items-end">
                        <?= submitBtn("hasher", "action", "🔑 Generate Hash", "key-fill", "lg") ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>