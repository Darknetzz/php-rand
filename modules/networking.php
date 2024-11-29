<div id="networking" class="content">

    <div class="card card-primary">
        <h1 class="card-header">CIDR to range</h1>
        <div class="card card-body">
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
        <div class="card card-body">
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
        <div class="card card-body">
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

</div>
