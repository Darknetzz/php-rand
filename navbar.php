<?php

$modules = listModules();
$navItems = '
<nav class="navbar ps-3 navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">RAND</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">';
  foreach ($modules as $module) {
    $name       = $module["name"];
    $formalname = $module["formalName"];
    $navItems  .= '
        <li class="nav-item">
          <a class="link nav-link" href="#'.$name.'" id="nav'.$name.'" data-show="'.$name.'">'.$formalname.'</a>
        </li>
      ';
  }
$navItems .= '
    </ul>
  </div>
</nav>
';

echo $navItems;
?>