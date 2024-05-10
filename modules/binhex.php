<div id="binhex" class="content">
  <div class="card card-primary">
    <h1 class="card-header">Binhex</h1>
    <div class="card card-body">
      <form class="form" action="gen.php" method="POST" id="binhex" data-action="binhex">
        <input type="text" name="binhex" class="form-control mb-2" placeholder="Binary or Hexadecimal">
        <label class="mb-2">
          <input type="checkbox" name="split" value="1"> Split output
        </label>
        <br>
        <label class="mb-2">
          <input type="checkbox" name="removedelimiters" value="1"> Remove delimiters
        </label>
        <br>
        Delimiter: <input type="text" name="delimiter" value=":">
        <hr>
        <div class="btn-group">
          <?= submitBtn("bin2hex", "tool", "Bin2Hex", "file-text-fill") ?>
          <?= submitBtn("hex2bin", "tool", "Hex2Bin", "file-binary-fill") ?>
        </div>
        <div class="responseDiv" data-formid="binhex"></div>
      </form>
    </div>
  </div>
</div>
