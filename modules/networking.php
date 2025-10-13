<div id="networking" class="content">

    <div class="card card-primary">
        <h1 class="card-header">DNS Lookup</h1>
        <div class="card-body">

            <form class="form" action="gen.php" method="POST" id="dnslookup" data-action="ip">
                Hostname/IP:
                <input class="form-control mb-2" type="text" name="hostname" placeholder="Hostname/IP">
                <?= submitBtn("dnslookup", "tool", "Lookup", "search") ?>
                <div class="responseDiv" data-formid="dnslookup"></div>
        </div>
    </div>

    <div class="card card-primary">
        <h1 class="card-header">CIDR to range</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="cidr2range" data-action="ip">
                <input type="text" name="cidr" class="form-control mb-2" placeholder="CIDR Range">
                <span class="text-muted">Examples:
                    <ul>
                        <li><span class="text-info m-1">192.168.1.0/24</span></li>
                        <li><span class="text-info m-1">10.0.0.0/22</span></li>
                    </ul>
                </span>
                <hr>
                <div class="btn-group">
                    <?= submitBtn("cidr2range", "tool", "Calculate", "file-text-fill") ?>
                </div>
                <div class="responseDiv" data-formid="cidr2range"></div>
            </form>
        </div>
    </div>

    <div class="card card-primary">
        <h1 class="card-header">Range to CIDR</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="range2cidr" data-action="ip">
                <input type="text" name="startip" class="form-control mb-2" placeholder="Start IP">
                <input type="text" name="endip" class="form-control mb-2" placeholder="End IP">
                <span class="text-muted">The CIDR will be calculated based on the smallest possible subnet.</span>
                <hr>
                <div class="btn-group">
                    <?= submitBtn("range2cidr", "tool", "Calculate", "file-text-fill") ?>
                </div>
                <div class="responseDiv" data-formid="range2cidr"></div>
            </form>
        </div>
    </div>

    <div class="card card-primary">
        <h1 class="card-header">Subnet mask</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="subnetmask" data-action="ip">
                <input type="text" name="ip" class="form-control mb-2" placeholder="IP Address">
                <input type="text" name="subnet" class="form-control mb-2" placeholder="Subnet Mask">
                <hr>
                <div class="btn-group">
                    <?= submitBtn("subnetmask", "tool", "Calculate", "file-text-fill") ?>
                </div>
                <div class="responseDiv" data-formid="subnetmask"></div>
            </form>
        </div>
    </div>

    <div class="card card-primary">
        <h1 class="card-header">IPHex</h1>
        <div class="card-body">
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
                    <input type="checkbox" name="split" value="1" class="toggledelimiter form-check-input"> Hex: Split
                    output
                </label>
                <br>
                <span class="delimiterinput" style="display:none;">
                    Delimiter: <input class="form-control" type="text" name="delimiter" value=":"
                        placeholder="Set the delimiter string">
                </span>
                <label class="togglelinebreak" style="display:none;">
                    <input type="checkbox" name="linebreak" value="1" class="form-check-input"> Hex: Line break between
                    each entry
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

</div>