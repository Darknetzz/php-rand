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
            <form class="form" action="gen.php" method="POST" id="openssl" data-action="openssl">
                <?php
                  $openssl   = Null;
                  $key       = Null;
                  $iv        = Null;
                  $stringVal = Null;
                  if (isset($_POST['openssl'])) {
                    $openssl   = $_POST['openssl'];
                    $key       = $_POST['key'];
                    $iv        = $_POST['iv'];
                    $stringVal = "value='$openssl'";
                  }
                  echo '<div class="form-floating mb-3">';
                  echo '<input type="text" name="openssl" class="form-control" id="opensslInput" '.$stringVal.'>';
                  echo '<label for="opensslInput">String</label>';
                  echo '</div>';

                  echo '<div class="form-floating mb-3">';
                  echo '<select class="form-control" name="cipher" id="cipherSelect">'.$ciphers.'</select>';
                  echo '<label for="cipherSelect">Cipher</label>';
                  echo '</div>';

                  echo '<div class="form-floating mb-3">';
                  echo '<input type="text" name="key" class="form-control" id="keyInput" value="'.$key.'" placeholder="Optional - key to encrypt string with, will generate random psuedo bytes if not set">';
                  echo '<label for="keyInput">Key</label>';
                  echo '</div>';

                  echo '<div class="form-floating mb-3">';
                  echo '<input type="text" name="iv" class="form-control" id="ivInput" value="'.$iv.'" placeholder="Optional, will randomize if not set">';
                  echo '<label for="ivInput">Initialization Vector</label>';
                  echo '</div>';
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