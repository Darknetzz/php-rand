<div id="levenshtein" class="content">
    <div class="card card-primary">
        <h1 class="card-header">Levenshtein</h1>
        <div class="card-body">
            <span class="description">
                Calculate Levenshtein distance between two strings.
                The Levenshtein distance is defined as the minimal number of characters you have to replace, insert or delete to transform string1 into string2.
                The complexity of the algorithm is O(m*n), where n and m are the length of string1 and string2 (rather good when compared to similar_text(), which is O(max(n,m)**3), but still expensive).
            </span>
            <form class="form" action="gen.php" method="POST" id="levenshtein" data-action="levenshtein">
                <textarea class="form-control" name="levenshtein1" placeholder="String 1"></textarea>
                <textarea class="form-control" name="levenshtein2" placeholder="String 2"></textarea>

                <input type="number" class="form-control" name="insertion_cost" placeholder="Insertion cost (default: 1)">
                <input type="number" class="form-control" name="replacement_cost" placeholder="Replacement cost (default: 1)">
                <input type="number" class="form-control" name="deletion_cost" placeholder="Deletion cost (default: 1)">
                
                <?= submitBtn("levenshtein", "action", "Convert", "arrow-repeat") ?>
                <div class="responseDiv" id="levenshteinresponse"></div>
            </form>

        </div>
    </div>
</div>