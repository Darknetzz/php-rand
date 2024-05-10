<?php

$modules  = listModules();
$navItems = '';
foreach ($modules as $module) {
  $name       = $module["name"];
  $formalname = $module["formalName"];
  $icon       = pageIcon($name);
  $navItems  .= '
      <li class="nav-item">
        <a class="link nav-link" href="#'.$name.'" id="nav'.$name.'" data-show="'.$name.'">'.$icon.$formalname.'</a>
      </li>
    ';
}

$navbar = '
<nav class="navbar ps-3 navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">RAND</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <div class="d-flex justify-content-between w-100">
      <ul class="navbar-nav">
        '.$navItems.'
      </ul>

      <ul class="navbar-nav mx-2">
        <li class="nav-item">
          <a class="nav-link link-info" target="_blank" href="https://github.com/Darknetzz/phprand"><i class="bi bi-github"></i> GitHub</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
';

echo $navbar;
?>
