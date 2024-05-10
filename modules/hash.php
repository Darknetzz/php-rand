<div id="hash" class="content">
<div class="card card-primary">
<h1 class="card-header">Hasher</h1>
<div class="card card-body">
<form class="form" action="gen.php" method="POST" id="hasher" data-action="hasher">
  <input type="text" name="hash" class="form-control mb-2" placeholder="Input string here">
  <?= submitBtn("hasher", "action", "Hash", "key-fill") ?>
  <div class="responseDiv" id="hasherresponse"></div>
</form>
</div>
</div>
</div>