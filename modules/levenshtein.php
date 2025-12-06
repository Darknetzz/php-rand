<div id="levenshtein" class="content">
    <div class="card card-primary">
        <h1 class="card-header">Levenshtein Distance</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <h4><?= icon("info-circle", color: "cyan") ?> Information</h4>
                <p style="margin-bottom: 0;">Calculate the Levenshtein distance between two strings. This is the minimal number of characters you need to insert, delete, or replace to transform one string into another. The algorithm complexity is O(m×n), where m and n are the string lengths.</p>
            </div>
            <form class="form" action="gen.php" method="POST" id="levenshtein" data-action="levenshtein">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="levenshtein1" class="form-label mb-3"><strong style="font-size: 1.1rem;">String 1</strong></label>
                        <textarea class="form-control" name="levenshtein1" id="levenshtein1" placeholder="Enter first string..." style="min-height: 200px; border: 2px solid #495057; font-family: monospace; font-size: 0.95rem;"></textarea>
                        
                        <label for="levenshtein2" class="form-label mb-3 mt-4"><strong style="font-size: 1.1rem;">String 2</strong></label>
                        <textarea class="form-control" name="levenshtein2" id="levenshtein2" placeholder="Enter second string..." style="min-height: 200px; border: 2px solid #495057; font-family: monospace; font-size: 0.95rem;"></textarea>
                        
                        <div class="row g-3 mt-3">
                            <div class="col-12 col-md-4">
                                <label for="insertion-cost" class="form-label"><strong>Insertion Cost</strong></label>
                                <input type="number" class="form-control form-control-lg" name="insertion_cost" id="insertion-cost" placeholder="1" value="1" min="0" style="border: 2px solid #495057;">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="replacement-cost" class="form-label"><strong>Replacement Cost</strong></label>
                                <input type="number" class="form-control form-control-lg" name="replacement_cost" id="replacement-cost" placeholder="1" value="1" min="0" style="border: 2px solid #495057;">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="deletion-cost" class="form-label"><strong>Deletion Cost</strong></label>
                                <input type="number" class="form-control form-control-lg" name="deletion_cost" id="deletion-cost" placeholder="1" value="1" min="0" style="border: 2px solid #495057;">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Result</strong></label>
                        <div class="responseDiv flex-grow-1" id="levenshteinresponse" style="border: 2px solid #495057; padding: 20px; min-height: 300px; background: linear-gradient(135deg, rgba(255, 87, 34, 0.1) 0%, rgba(255, 152, 0, 0.08) 100%); border-radius: 0.5rem;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 120px;">
                                <div style="font-size: 3rem;">📏</div>
                                <div>Distance will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100"><?= icon("calculator") ?> Calculate Distance</button>
            </form>
        </div>
    </div>
</div>
