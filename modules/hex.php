<div id="hex" class="content">
  <div class="card card-primary">
    <h1 class="card-header">Binhex</h1>
    <div class="card card-body">
      <form class="form" action="gen.php" method="POST" id="binhex" data-action="hex">
        <input type="text" name="binhex" class="form-control mb-2" placeholder="Binary or Hexadecimal">
        <label class="mb-2">
          <input type="checkbox" name="split" value="1" class="toggledelimiter"> Split output
        </label>
        <br>
        <span class="delimiterinput" style="display:none;">
          Delimiter: <input class="form-control" type="text" name="delimiter" value=":" placeholder="Set the delimiter string">
        </span>
        <hr>
        <div class="btn-group">
          <?= submitBtn("bin2hex", "tool", "Bin2Hex", "file-text-fill") ?>
          <?= submitBtn("hex2bin", "tool", "Hex2Bin", "file-binary-fill") ?>
        </div>
        <div class="responseDiv" data-formid="binhex"></div>
      </form>
    </div>
  </div>

  <div class="card card-primary">
    <h1 class="card-header">IPHex</h1>
    <div class="card card-body">
      <div class="alert alert-info mx-2">
        <h4 class="text-info"><?= icon("info-circle", color: "cyan") ?> Protip</h4>
        <p>
          To supply more than one IP, use <kdb>,</kdb> (comma) to separate them, example:<br>
          <code>192.168.1.10, 192.168.1.20, 10.0.0.50</code>
        </p>
      </div>
      <form class="form" action="gen.php" method="POST" id="iphex" data-action="hex">
        <input type="text" name="iphex" class="form-control mb-2" placeholder="IP or Hexadecimal">
        <br>
        <label class="mb-1">
          <input type="checkbox" name="split" value="1" class="toggledelimiter form-check-input"> Hex: Split output
        </label>
        <br>
        <span class="delimiterinput" style="display:none;">
          Delimiter: <input class="form-control" type="text" name="delimiter" value=":" placeholder="Set the delimiter string">
        </span>
        <label class="togglelinebreak" style="display:none;">
          <input type="checkbox" name="linebreak" value="1" class="form-check-input"> Hex: Line break between each entry
        </label>
        <hr>
        <div class="btn-group">
          <?= submitBtn("ip2hex", "tool", "IP2Hex", "file-text-fill") ?>
          <?= submitBtn("hex2ip", "tool", "Hex2IP", "file-binary-fill") ?>
        </div>
        <div class="responseDiv" data-formid="binhex"></div>
      </form>
    </div>
  </div>
</div>

<script>
    // Seed toggle numgen
    $(".toggledelimiter").change(function() {
        if ($(this).is(":checked")) {
        $(this).closest("form").find(".delimiterinput").fadeIn();
        } else {
          $(this).closest("form").find(".delimiterinput").fadeOut();
        }
    });

    // Seed toggle linebreak
    $("input[name='iphex']").on("input", function() {
        if ($(this).val().includes(",") || $(this).val().includes(" ")) {
            $(".togglelinebreak").fadeIn();
        } else {
            $(".togglelinebreak").fadeOut();
        }
    });
</script>