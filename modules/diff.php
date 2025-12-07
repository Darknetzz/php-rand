<div id="diff" class="content">

    <div class="card card-primary">
        <h1 class="card-header">Diff (Unified)</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <h4><?= icon("info-circle", color: "cyan") ?> Information</h4>
                <p style="margin-bottom: 0;">Create a unified diff between two text inputs. Shows additions, deletions, and unchanged lines with standard diff formatting.</p>
            </div>
            <form class="form" action="gen.php" method="POST" id="diff" data-action="diff">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label class="form-label mb-3" for="diff1"><strong style="font-size: 1.1rem;">Old Data</strong></label>
                        <textarea class="form-control" id="diff1" name="diff1" placeholder="Enter old/original text..." style="min-height: 350px; border: 2px solid #495057; font-family: monospace; font-size: 0.95rem; resize: vertical;"></textarea>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label class="form-label mb-3" for="diff2"><strong style="font-size: 1.1rem;">New Data</strong></label>
                        <textarea class="form-control" id="diff2" name="diff2" placeholder="Enter new/modified text..." style="min-height: 350px; border: 2px solid #495057; font-family: monospace; font-size: 0.95rem; resize: vertical;"></textarea>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Diff Result</strong></label>
                    <div class="responseDiv" data-formid="diff" style="border: 2px solid #495057; padding: 20px; min-height: 200px; max-height: 600px; overflow-y: auto; background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(168, 85, 247, 0.08) 100%); border-radius: 0.5rem; font-family: monospace; white-space: pre-wrap; word-break: break-word; font-size: 0.9rem;">
                        <div style="opacity: 0.5; text-align: center; padding-top: 70px;">
                            <div style="font-size: 3rem;">📝</div>
                            <div>Diff output will appear here...</div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100"><?= icon("git-compare") ?> Generate Diff</button>
            </form>
        </div>
    </div>

</div>