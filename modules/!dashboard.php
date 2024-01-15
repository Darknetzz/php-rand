<div id="dashboard" class="content">
<div class="card">
  <h1 class="card-header">Welcome to RAND!</h1>
  <div class="card-body">
    <p>This page includes a bunch of tools. Choose a tool above to get started.</p>
    <h4>Tools</h4>
    <?php
      $modules = listModules();
    ?>
    <ul>
      <?= $modules ?>
    </ul>
  </div>
</div>
</div>