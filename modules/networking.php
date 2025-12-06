<div id="networking" class="content">

    <!-- DNS Lookup -->
    <div class="card card-primary mb-4">
        <h1 class="card-header">DNS Lookup</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <h4><?= icon("info-circle", color: "cyan") ?> Information</h4>
                <p style="margin-bottom: 0;">Resolve hostname to IP address or IP address to hostname.</p>
            </div>
            <form class="form" action="gen.php" method="POST" id="dnslookup" data-action="ip">
                <input type="hidden" name="action" value="ip">
                <input type="hidden" name="tool" value="dnslookup">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="hostnameInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input</strong></label>
                        <input class="form-control form-control-lg" id="hostnameInput" type="text" name="hostname" placeholder="e.g., google.com or 8.8.8.8" style="border: 2px solid #495057; font-family: monospace; min-height: 50px;">
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Result</strong></label>
                        <div class="responseDiv flex-grow-1" data-formid="dnslookup" style="border: 2px solid #495057; padding: 20px; min-height: 50px; background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(0, 184, 255, 0.08) 100%); border-radius: 0.5rem; font-family: monospace;">
                            <div style="opacity: 0.5; text-align: center;">
                                <div style="font-size: 3rem;">🔍</div>
                                <div>Result will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100"><?= icon("search") ?> Lookup</button>
            </form>
        </div>
    </div>

    <!-- CIDR to Range -->
    <div class="card card-primary mb-4">
        <h1 class="card-header">CIDR to Range</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <h4><?= icon("info-circle", color: "cyan") ?> Information</h4>
                <p style="margin-bottom: 0;">Convert CIDR notation to IP range (start, end, total IPs).</p>
            </div>
            <form class="form" action="gen.php" method="POST" id="cidr2range" data-action="ip">
                <input type="hidden" name="action" value="ip">
                <input type="hidden" name="tool" value="cidr2range">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="cidrInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">CIDR Input</strong></label>
                        <input type="text" id="cidrInput" name="cidr" class="form-control form-control-lg" placeholder="e.g., 192.168.1.0/24" style="border: 2px solid #495057; font-family: monospace; min-height: 50px;">
                        <small class="text-muted d-block mt-2"><strong>Examples:</strong> 192.168.1.0/24, 10.0.0.0/22</small>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Result</strong></label>
                        <div class="responseDiv flex-grow-1" data-formid="cidr2range" style="border: 2px solid #495057; padding: 20px; min-height: 150px; overflow-y: auto; background: linear-gradient(135deg, rgba(32, 201, 151, 0.1) 0%, rgba(13, 110, 253, 0.08) 100%); border-radius: 0.5rem;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 50px;">
                                <div style="font-size: 3rem;">🗺️</div>
                                <div>Result will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100"><?= icon("calculator") ?> Calculate Range</button>
            </form>
        </div>
    </div>

    <!-- Range to CIDR -->
    <div class="card card-primary mb-4">
        <h1 class="card-header">Range to CIDR</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <h4><?= icon("info-circle", color: "cyan") ?> Information</h4>
                <p style="margin-bottom: 0;">Convert IP range (start and end) to CIDR notation. Calculates the smallest possible subnet.</p>
            </div>
            <form class="form" action="gen.php" method="POST" id="range2cidr" data-action="ip">
                <input type="hidden" name="action" value="ip">
                <input type="hidden" name="tool" value="range2cidr">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="startipInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">IP Range</strong></label>
                        <input type="text" id="startipInput" name="startip" class="form-control form-control-lg" placeholder="Start: 192.168.1.0" style="border: 2px solid #495057; font-family: monospace; margin-bottom: 10px;">
                        <input type="text" id="endipInput" name="endip" class="form-control form-control-lg" placeholder="End: 192.168.1.255" style="border: 2px solid #495057; font-family: monospace;">
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Result</strong></label>
                        <div class="responseDiv flex-grow-1" data-formid="range2cidr" style="border: 2px solid #495057; padding: 20px; min-height: 150px; overflow-y: auto; background: linear-gradient(135deg, rgba(102, 16, 242, 0.1) 0%, rgba(108, 92, 231, 0.08) 100%); border-radius: 0.5rem;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 50px;">
                                <div style="font-size: 3rem;">📊</div>
                                <div>Result will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100"><?= icon("calculator") ?> Convert to CIDR</button>
            </form>
        </div>
    </div>

    <!-- Subnet Mask Calculator -->
    <div class="card card-primary mb-4">
        <h1 class="card-header">Subnet Mask Calculator</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <h4><?= icon("info-circle", color: "cyan") ?> Information</h4>
                <p style="margin-bottom: 0;">Calculate subnet information: network, first IP, last IP, and broadcast address.</p>
            </div>
            <form class="form" action="gen.php" method="POST" id="subnetmask" data-action="ip">
                <input type="hidden" name="action" value="ip">
                <input type="hidden" name="tool" value="subnetmask">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="ipInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Network Details</strong></label>
                        <input type="text" id="ipInput" name="ip" class="form-control form-control-lg" placeholder="IP: 192.168.1.100" style="border: 2px solid #495057; font-family: monospace; margin-bottom: 10px;">
                        <input type="text" id="subnetInput" name="subnet" class="form-control form-control-lg" placeholder="Subnet: 255.255.255.0 or /24" style="border: 2px solid #495057; font-family: monospace;">
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Result</strong></label>
                        <div class="responseDiv flex-grow-1" data-formid="subnetmask" style="border: 2px solid #495057; padding: 20px; min-height: 150px; overflow-y: auto; background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 152, 0, 0.08) 100%); border-radius: 0.5rem;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 50px;">
                                <div style="font-size: 3rem;">🔧</div>
                                <div>Result will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100"><?= icon("calculator") ?> Calculate</button>
            </form>
        </div>
    </div>

    <!-- IP/Hex Converter -->
    <div class="card card-primary mb-4">
        <h1 class="card-header">IP/Hex Converter</h1>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <h4><?= icon("info-circle", color: "cyan") ?> Information</h4>
                <p style="margin-bottom: 0;">Convert between IP addresses and hexadecimal format. Supports multiple IPs separated by commas: <code>192.168.1.10, 192.168.1.20, 10.0.0.50</code></p>
            </div>
            <form class="form" action="gen.php" method="POST" id="iphex" data-action="hex">
                <input type="hidden" name="action" value="hex">
                <input type="hidden" name="tool" value="ip2hex" id="iphexTool">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="iphexInput" class="form-label mb-3"><strong style="font-size: 1.1rem;">Input</strong></label>
                        <input type="text" id="iphexInput" name="iphex" class="form-control form-control-lg" placeholder="IP: 192.168.1.10 or Hex: C0A8010A" style="border: 2px solid #495057; font-family: monospace; min-height: 50px; margin-bottom: 15px;">
                        
                        <div class="form-check mb-2">
                            <input type="checkbox" name="split" value="1" class="toggledelimiter form-check-input" id="splitCheck">
                            <label class="form-check-label" for="splitCheck"><strong>Hex: Split output</strong></label>
                        </div>
                        
                        <div class="delimiterinput mb-3" style="display:none;">
                            <label for="delimiterInput" class="form-label">Delimiter</label>
                            <input class="form-control form-control-lg" id="delimiterInput" type="text" name="delimiter" value=":" placeholder=":" style="font-family: monospace; border: 2px solid #495057;">
                        </div>
                        
                        <div class="form-check togglelinebreak mb-3" style="display:none;">
                            <input type="checkbox" name="linebreak" value="1" class="form-check-input" id="linebreakCheck">
                            <label class="form-check-label" for="linebreakCheck"><strong>Add line breaks</strong></label>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <label class="form-label mb-3"><strong style="font-size: 1.1rem;">Result</strong></label>
                        <div class="responseDiv flex-grow-1" data-formid="binhex" style="border: 2px solid #495057; padding: 20px; min-height: 150px; overflow-y: auto; background: linear-gradient(135deg, rgba(13, 202, 240, 0.1) 0%, rgba(32, 201, 151, 0.08) 100%); border-radius: 0.5rem; font-family: monospace; white-space: pre-wrap;">
                            <div style="opacity: 0.5; text-align: center; padding-top: 50px;">
                                <div style="font-size: 3rem;">🔄</div>
                                <div>Result will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2 gap-sm-0 d-sm-flex">
                    <button type="submit" class="btn btn-outline-primary flex-sm-grow-1" onclick="document.getElementById('iphexTool').value='ip2hex'" style="border: 2px solid #0d6efd;"><?= icon("arrow-right") ?> IP → Hex</button>
                    <button type="submit" class="btn btn-outline-primary flex-sm-grow-1" onclick="document.getElementById('iphexTool').value='hex2ip'" style="border: 2px solid #0d6efd;"><?= icon("arrow-left") ?> Hex → IP</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Delimiter toggle
    $(".toggledelimiter").change(function() {
        if ($(this).is(":checked")) {
            $(this).closest("form").find(".delimiterinput").fadeIn();
        } else {
            $(this).closest("form").find(".delimiterinput").fadeOut();
        }
    });

    // Line break toggle
    $("input[name='iphex']").on("input", function() {
        if ($(this).val().includes(",") || $(this).val().includes(" ")) {
            $(".togglelinebreak").fadeIn();
        } else {
            $(".togglelinebreak").fadeOut();
        }
    });
    </script>

</div>