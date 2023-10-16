<!-- 
// ─────────────────────────────────────────────────────────────────────────────────────────────── #
//                                             OPENSSL                                             #
// ─────────────────────────────────────────────────────────────────────────────────────────────── # 
-->
<div id="openssl" class="content">
<?php
  $ciphers = "";
  foreach (openssl_get_cipher_methods() as $thiscipher) {
    $ciphers .= "<option value='$thiscipher'>$thiscipher</option>";
  }
?>

<div class="card card-primary">
<h1 class="card-header">OpenSSL Encrypt</h1>
<div class="card card-body">
<form class="form" action="gen.php" method="POST" id="openssl">
  <?php
  if (isset($_POST['openssl'])) {
  $openssl = $_POST['openssl'];
  $key = $_POST['key'];
  $iv = $_POST['iv'];
  }
  else {
  $openssl = NULL;
  $key = NULL;
  $iv = NULL;
  }
  echo 'Plaintext: <input type="text" name="openssl" class="form-control" value="'.$openssl.'">';
  echo 'Cipher: <select class="form-control" name="cipher">'.$ciphers.'</select>';
  echo 'Key: <input type="text" name="key" class="form-control" value="'.$key.'" placeholder="Optional - key to encrypt string with, will generate random psuedo bytes if not set">';
  echo 'Initialization Vector: <input type="text" name="iv" class="form-control" value="'.$iv.'" placeholder="Optional, will randomize if not set">';
  ?>
  <input type="submit" name="opensslsubmit" value="Encrypt" class="btn btn-success">
</form>
 <div id="opensslresponse"></div>
</div>
</div>

<br>

<div class="card card-primary">
<h1 class="card-header">OpenSSL Decrypt</h1>
<div class="card card-body">
<form class="form" action="gen.php" method="POST" id="openssld">
  <?php
  if (isset($_POST['openssld'])) {
  $openssld = $_POST['openssld'];
  $key = $_POST['key'];
  $iv = $_POST['iv'];
  }
  else {
  $openssld = NULL;
  $key = NULL;
  $iv = NULL;
  }
  echo 'Encrypted string: <input type="text" name="openssld" class="form-control" value="'.$openssld.'">';
  echo 'Cipher: <select class="form-control" name="cipher">'.$ciphers.'</select>';
  echo 'Key: <input type="text" name="key" class="form-control" value="'.$key.'" placeholder="Optional - key to decrypt string with">';
  echo 'Initialization Vector (Hex): <input type="text" name="iv" class="form-control" value="'.$iv.'" placeholder="Required">';
  ?>
  <input type="submit" name="openssldsubmit" value="Decrypt" class="btn btn-success">
</form>
 <div id="openssldresponse"></div>
</div>
</div>
</div>
<!-- // ─────────────────────────────────────────────────────────────────────────────────────────────── # -->