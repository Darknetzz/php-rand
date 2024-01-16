<div id="binhex" class="content">
  <div class="card card-primary">
    <h1 class="card-header">Binhex</h1>
    <div class="card card-body">
      <form class="form" action="gen.php" method="POST" id="binhex">
        <input type="text" name="binhex" class="form-control mb-2" placeholder="Binary or Hexadecimal">
        <div class="btn-group">
          <?= submitBtn("bin2hex", "action", "bin2hex", hasDice: False) ?>
          <?= submitBtn("hex2bin", "action", "hex2bin", hasDice: False) ?>
        </div>
        <div class="responseDiv" data-formid="binhex"></div>
      </form>
    </div>
  </div>
</div>
