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
                <table class="form-table">
                    <tr>
                        <th style="padding-right: 0.75rem;"><label for="levenshtein1">String 1</label></th>
                        <td><textarea class="form-control" name="levenshtein1" id="levenshtein1" placeholder="String 1"></textarea></td>
                    </tr>
                    <tr>
                        <th style="padding-right: 0.75rem;"><label for="levenshtein2">String 2</label></th>
                        <td><textarea class="form-control" name="levenshtein2" id="levenshtein2" placeholder="String 2"></textarea></td>
                    </tr>
                    <tr>
                        <th style="padding-right: 0.75rem;"><label for="insertion-cost">Insertion cost</label></th>
                        <td><input type="number" class="form-control" name="insertion_cost" id="insertion-cost" placeholder="Insertion cost (default: 1)"></td>
                    </tr>
                    <tr>
                        <th style="padding-right: 0.75rem;"><label for="replacement-cost">Replacement cost</label></th>
                        <td><input type="number" class="form-control" name="replacement_cost" id="replacement-cost" placeholder="Replacement cost (default: 1)"></td>
                    </tr>
                    <tr>
                        <th style="padding-right: 0.75rem;"><label for="deletion-cost">Deletion cost</label></th>
                        <td><input type="number" class="form-control" name="deletion_cost" id="deletion-cost" placeholder="Deletion cost (default: 1)"></td>
                    </tr>
                </table>
                <?= submitBtn("levenshtein", "action", "Convert", "arrow-repeat") ?>
                <div class="responseDiv" id="levenshteinresponse"></div>
            </form>

        </div>
    </div>
</div>
