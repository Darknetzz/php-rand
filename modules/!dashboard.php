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

    <hr>

    <h4>Tools</h4>
    <ul class="">
    <?php
      $modules = listModules();
      foreach ($modules as $module) {
        $name       = $module["name"];
        $formalname = $module["formalName"];
        if ($name == "dashboard") continue;
        echo '<li><a class="link" href="#'.$name.'" data-show="'.$name.'">'.$formalname.'</a></li>';
      }
    ?>
    </ul>
  </div>
</div>
</div>