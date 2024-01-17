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
        <h1 class="card-header">OpenSSL</h1>
        <div class="card card-body">
            <form class="form" action="gen.php" method="POST" id="openssl">
              <input type="hidden" name="action" value="openssl">
                <?php
                  $openssl = NULL;
                  $key = NULL;
                  $iv = NULL;
                  if (isset($_POST['openssl'])) {
                    $openssl = $_POST['openssl'];
                    $key = $_POST['key'];
                    $iv = $_POST['iv'];
                  }
                  echo 'String: <input type="text" name="openssl" class="form-control" value="'.$openssl.'">';
                  echo 'Cipher: <select class="form-control" name="cipher">'.$ciphers.'</select>';
                  echo 'Key: <input type="text" name="key" class="form-control" value="'.$key.'" placeholder="Optional - key to encrypt string with, will generate random psuedo bytes if not set">';
                  echo 'Initialization Vector: <input type="text" name="iv" class="form-control" value="'.$iv.'" placeholder="Optional, will randomize if not set">';
                ?>
                <div class="btn-group mt-3">
                    <?= submitBtn("encrypt", "tool", "Encrypt", "lock", "lg") ?>
                    <?= submitBtn("decrypt", "tool", "Decrypt", "unlock", "lg") ?>
                </div>
                <div class="responseDiv" id="opensslresponse"></div>
            </form>
        </div>
    </div>
</div>
<!-- // ─────────────────────────────────────────────────────────────────────────────────────────────── # -->