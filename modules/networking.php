<div id="networking" class="content">

    <div class="card card-primary">
        <h1 class="card-header">DNS Lookup</h1>
        <div class="card-body">

            <form class="form" action="gen.php" method="POST" id="dnslookup" data-action="ip">
                <label for="hostnameInput" class="form-label"><strong>Hostname/IP</strong></label>
                <input class="form-control mb-3" id="hostnameInput" type="text" name="hostname" placeholder="Enter hostname or IP address" style="font-family: monospace;">
                <?= submitBtn("dnslookup", "tool", "Lookup", "search") ?>
                <hr>
                <div class="responseDiv" data-formid="dnslookup" style="border: 1px solid #dee2e6; padding: 15px; min-height: 60px; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace;">Result will appear here...</div>
        </div>
    </div>

    <div class="card card-primary">
        <h1 class="card-header">CIDR to range</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="cidr2range" data-action="ip">
                <label for="cidrInput" class="form-label"><strong>CIDR Range</strong></label>
                <input type="text" id="cidrInput" name="cidr" class="form-control mb-3" placeholder="e.g., 192.168.1.0/24" style="font-family: monospace;">
                <span class="text-muted"><strong>Examples:</strong>
                    <ul>
                        <li><code>192.168.1.0/24</code></li>
                        <li><code>10.0.0.0/22</code></li>
                    </ul>
                </span>
                <?= submitBtn("cidr2range", "tool", "Calculate", "file-text-fill") ?>
                <hr>
                <div class="responseDiv" data-formid="cidr2range" style="border: 1px solid #dee2e6; padding: 15px; min-height: 100px; max-height: 400px; overflow-y: auto; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem;">Result will appear here...</div>
            </form>
        </div>
    </div>

    <div class="card card-primary">
        <h1 class="card-header">Range to CIDR</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="range2cidr" data-action="ip">
                <label for="startipInput" class="form-label"><strong>Start IP</strong></label>
                <input type="text" id="startipInput" name="startip" class="form-control mb-3" placeholder="e.g., 192.168.1.0" style="font-family: monospace;">
                <label for="endipInput" class="form-label"><strong>End IP</strong></label>
                <input type="text" id="endipInput" name="endip" class="form-control mb-3" placeholder="e.g., 192.168.1.255" style="font-family: monospace;">
                <span class="text-muted"><small>The CIDR will be calculated based on the smallest possible subnet.</small></span>
                <hr>
                <?= submitBtn("range2cidr", "tool", "Calculate", "file-text-fill") ?>
                <hr>
                <div class="responseDiv" data-formid="range2cidr" style="border: 1px solid #dee2e6; padding: 15px; min-height: 100px; max-height: 400px; overflow-y: auto; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem;">Result will appear here...</div>
            </form>
        </div>
    </div>

    <div class="card card-primary">
        <h1 class="card-header">Subnet mask</h1>
        <div class="card-body">
            <form class="form" action="gen.php" method="POST" id="subnetmask" data-action="ip">
                <label for="ipInput" class="form-label"><strong>IP Address</strong></label>
                <input type="text" id="ipInput" name="ip" class="form-control mb-3" placeholder="e.g., 192.168.1.100" style="font-family: monospace;">
                <label for="subnetInput" class="form-label"><strong>Subnet Mask</strong></label>
                <input type="text" id="subnetInput" name="subnet" class="form-control mb-3" placeholder="e.g., 255.255.255.0 or /24" style="font-family: monospace;">
                <?= submitBtn("subnetmask", "tool", "Calculate", "file-text-fill") ?>
                <hr>
                <div class="responseDiv" data-formid="subnetmask" style="border: 1px solid #dee2e6; padding: 15px; min-height: 100px; max-height: 400px; overflow-y: auto; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem;">Result will appear here...</div>
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
                <label for="iphexInput" class="form-label"><strong>IP or Hexadecimal</strong></label>
                <input type="text" id="iphexInput" name="iphex" class="form-control mb-3" placeholder="e.g., 192.168.1.10 or C0A8010A" style="font-family: monospace;">
                
                <div class="form-check mb-2">
                    <input type="checkbox" name="split" value="1" class="toggledelimiter form-check-input" id="splitCheck">
                    <label class="form-check-label" for="splitCheck"><strong>Hex: Split output</strong></label>
                </div>
                
                <div class="delimiterinput mb-3" style="display:none;">
                    <label for="delimiterInput" class="form-label">Delimiter</label>
                    <input class="form-control" id="delimiterInput" type="text" name="delimiter" value=":" placeholder="Set the delimiter string" style="max-width: 200px; font-family: monospace;">
                </div>
                
                <div class="form-check togglelinebreak mb-3" style="display:none;">
                    <input type="checkbox" name="linebreak" value="1" class="form-check-input" id="linebreakCheck">
                    <label class="form-check-label" for="linebreakCheck"><strong>Hex: Line break between each entry</strong></label>
                </div>
                
                <div class="btn-group mb-3">
                    <?= submitBtn("ip2hex", "tool", "IP2Hex", "file-text-fill") ?>
                    <?= submitBtn("hex2ip", "tool", "Hex2IP", "file-binary-fill") ?>
                </div>
                <hr>
                <div class="responseDiv" data-formid="binhex" style="border: 1px solid #dee2e6; padding: 15px; min-height: 100px; max-height: 400px; overflow-y: auto; background-color: rgba(0,0,0,0.1); border-radius: 0.25rem; font-family: monospace; white-space: pre-wrap;">Result will appear here...</div>
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