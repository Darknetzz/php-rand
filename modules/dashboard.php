<div id="dashboard" class="content">
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

      <hr>

      <h5 class="text-warning"><?= icon("exclamation-triangle-fill") ?> Disclaimer</h5>
      <p>
        RAND is by no means a professional tool. It is a tool that I made for myself, and I am sharing it with you.
        The code is not perfect, and there are probably bugs. If you find a bug, please report it on the GitHub page.
    </p>

    <?php
      foreach ($navbarItems as $moduleName => $module) {

          $name       = $module["name"] ?? $moduleName;
          $formalName = $module["formalName"] ?? ucfirst($name);
          $icon       = $module["icon"] ?? icon('gear');

          echo "
          <ul class='list-unstyled'>
            <li><h2 class='text-primary'>$icon $formalName</h2></li>";

          # Module has subitems
          if (!empty($module["subitems"])) {
            echo "<ul>";
              foreach ($module["subitems"] as $subitemName => $subitem) {
                  $formalName = $subitem["formalName"] ?? ucfirst($subitemName);
                  $icon       = $subitem["icon"] ?? icon('gear');
                  echo "<li><a href='#" . $subitemName . "' id='nav" . $subName . "' data-show='" . $subName . "'> $icon $subitemName</a></li>";
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