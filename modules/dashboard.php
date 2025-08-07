<div id="dashboard" class="content">

    <div class="alert alert-warning d-flex justify-content-between" role="alert">
        <h3 class="alert-title text-warning">
            <span style="display: inline-flex; align-items: center;">
                <span class="alert-icon" style="margin-right: 0.5em;"><?= icon("exclamation-triangle-fill") ?></span>
                Disclaimer
            </span>
        </h3>
        <div class="alert-description">
            RAND is by no means a professional tool. It is a tool that I made for myself, and I am sharing it
            with you.
            The code is not perfect, and there are probably bugs. If you find a bug, please report it on the
            GitHub page.
        </div>
    </div>

    <div class="card">
        <h1 class="card-header d-flex justify-content-between">
            <span>Welcome to RAND!</span>
        </h1>
        <div class="card-body">
            <p class="text-muted">
            <h1 class="text-primary">RAND</h1>
            This page includes a bunch of tools. Choose a tool above to get started.
            <br>
            To see more, click one of the links below or in the navbar.

            <?php
      foreach ($navbarItems as $moduleName => $module) {

          $name       = $module["name"] ?? $moduleName;
          $formalName = $module["formalName"] ?? ucfirst($name);
          $icon       = $module["icon"] ?? icon('gear');

          if ($name === "dashboard") {
            continue; // Skip the dashboard module itself
          }

          echo "
          <ul class='list-unstyled'>
            <li><h2 class='text-primary'>$icon $formalName</h2></li>";

          # Module has subitems
          if (!empty($module["subitems"])) {
            echo "<ul class='list-unstyled ps-3'>";
              foreach ($module["subitems"] as $subitemName => $subitem) {
                  $formalName = $subitem["formalName"] ?? ucfirst($subitemName);
                  $icon       = $subitem["icon"] ?? icon('gear');
                  echo "<li><a class='link text-secondary' href='#" . $subitemName . "' id='nav" . $subName . "' data-show='" . $subName . "'> $icon $formalName</a></li>";
              }
            echo "</ul>";
          }
          echo "</ul><hr>";
        }
      ?>

            <hr>
        </div>
    </div>
</div>