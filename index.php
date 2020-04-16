<?php header('Content-Type: text/html; charset=utf-8'); ?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<style>
body {
  background-color:#262626;
  background-color:#111;
  color:#FFF;
}
.panel {
  background-color:#202020;
  color:#FFF;
}
.form-control {
  background-color:#303030;
  color:#FFF;
}
.jumbotron {
  background-color:#303030;
  color:#FFF;
}
</style>

<title>Rand</title>

<?php include_once("navbar.php"); ?>
<br>
<div class="container">
<div id="dashboard" class="content" hidden>
<div class="jumbotron">
  <h1>Welcome to RAND!</h1>
  <p>This page includes a bunch of tools. Choose a tool above to get started.</p>
</div>
</div>
<div id="rsgen" class="content" hidden>
    <h1>Random string generator</h1>
  <div class="panel panel-primary">
  <div class="panel panel-body">
<form class="form" action="gen.php" method="POST" id="stringgen">
<input type="hidden" name="containnumbers" value="0">
<input type="hidden" name="containletters" value="0">
<input type="hidden" name="containuletters" value="0">
<input type="hidden" name="containsymbols" value="0">
<input type="hidden" name="customizecharset" value="0">
<select name="digits" class="form-control">
<?php
$start = 1;
$maxdigits = 100;
while ($start <= $maxdigits) {
$selected = ($start == 10) ? 'selected' : '';
  if (isset($_POST['digits']) && $_POST['digits'] == $start) {
  echo "<option value='$start' selected>$start</option>";
  }
  else {
  echo "<option value='$start' $selected>$start</option>";
  }
  $start++;
}
?>
</select>
<?php
if (isset($_POST['containnumbers']) && $_POST['containnumbers'] == 1) {
$numberscheckbox = '<label><input type="checkbox" name="containnumbers" value="1" checked> Contain numbers</label> <font color="grey">0-9</font><br>';
} else {
$numberscheckbox = '<label><input type="checkbox" name="containnumbers" value="1" checked> Contain numbers</label> <font color="grey">0-9</font><br>';
}
if (isset($_POST['containletters']) && $_POST['containletters'] == 1) {
$letterscheckbox = '<label><input type="checkbox" name="containletters" value="1" checked> Contain lowercase letters</label> <font color="grey">a-z</font><br>';
}
else {
$letterscheckbox = '<label><input type="checkbox" name="containletters" value="1" checked> Contain lowercase letters</label> <font color="grey">a-z</font><br>';
}
if (isset($_POST['containuletters']) && $_POST['containuletters'] == 1) {
$uletterscheckbox = '<label><input type="checkbox" name="containuletters" value="1" checked> Contain uppercase letters</label> <font color="grey">A-Z</font><br>';
}
else {
$uletterscheckbox = '<label><input type="checkbox" name="containuletters" value="1" checked> Contain uppercase letters</label> <font color="grey">A-Z</font><br>';
}
if (isset($_POST['containsymbols']) && $_POST['containsymbols'] == 1) {
$symbolscheckbox = '<label><input type="checkbox" name="containsymbols" value="1" checked> Contain symbols</label> <font color="grey">!#¤%&\/()=?;:-_.,\'"*^<>{}[]@~+´`</font><br>';
}
else {
$symbolscheckbox = '<label><input type="checkbox" name="containsymbols" value="1"> Contain symbols</label> <font color="grey">!#¤%&\/()=?;:-_.,\'"*^<>{}[]@~+´`</font><br>';
}
if (isset($_POST['containesymbols']) && $_POST['containesymbols'] == 1) {
$symbolsecheckbox = '<label><input type="checkbox" name="containesymbols" value="1" checked> Contain extended symbols</label> <font color="grey">ƒ†‡™•)</font><br>';
}
else {
$symbolsecheckbox = '<label><input type="checkbox" name="containesymbols" value="1"> Contain extended symbols</label> <font color="grey">ƒ†‡™•</font><br>';
}
if (isset($_POST['customizecharset']) && $_POST['customizecharset'] == 1) {
$customizecharset = '<label><input type="checkbox" name="customizecharset" id="customizecharset" value="1" checked> Custom characters</label><br>
<textarea class="form-control" name="charset" id="charset" style="display:none;"></textarea><br>';
}
else {
$customizecharset = '<label><input type="checkbox" name="customizecharset" id="customizecharset" value="1"> Custom characters</label><br>
<textarea class="form-control" name="charset" id="charset" style="display:none;"></textarea><br>';
}
echo $numberscheckbox;
echo $letterscheckbox;
echo $uletterscheckbox;
echo $symbolscheckbox;
echo $symbolsecheckbox;
echo $customizecharset;

?>
</label><br>
<input type="submit" name="submit" class="btn btn-success" value="Generate">
</form>

<div id="stringgenresponse"></div>
</div>
</div>
</div>

<!-------------------------------------------------------------------------------->
<div id="cnumgen" class="content" hidden>
<h1>Number Generator</h1>
<div class="panel panel-primary">
    <div class="panel panel-body">
        <form class="form" action="gen.php" method="POST" id="numgen">
            Generate a number between
            <?php
            if (isset($_POST['numgenfrom']) && isset($_POST['numgento'])) {
                echo "<input type='number' name='numgenfrom' class='form-control' value='".$_POST['numgenfrom']."'> and <input type='number' name='numgento' class='form-control' value='".$_POST['numgento']."'>";
            } else {
                echo "<input type='number' name='numgenfrom' class='form-control' value='1'> and <input type='number' name='numgento' class='form-control' value='100'>";
            }
            ?>
            <input type="hidden" name="seed" value="0">
            <label><input type="checkbox" id="cnumgenseedtoggle" name="seed" value="1"> Seed</label><br>
            <div class="cnumgenseed" hidden>
            with seed: 
            <input type="text" name="numgenseed" class="form-control" value="" placeholder="Optional">
            </div>
            <br>
            <input type="submit" value="Generate" class="btn btn-success">
        </form>
    <div id="numgenresponse"></div>
    </div>
</div>
</div>

<div id="md5" class="content" hidden>
<h1>MD5 Hasher</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form class="form" action="gen.php" method="POST" id="md5hasher">
  <?php
  if (isset($_POST['md5'])) {
  echo '<input type="text" name="md5" class="form-control" value="' . $_POST['md5'] . '">';
  }
  else {
  echo '<input type="text" name="md5" class="form-control">';
  }
  ?>
  <input type="submit" name="md5hash" value="Hash" class="btn btn-success">
</form>
 <div id="md5hasherresponse"></div>
</div>
</div>
</div>
<div id="sha" class="content" hidden>
<h1>SHA1 Hasher</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form class="form" action="gen.php" method="POST" id="sha1hasher">
  <?php
  if (isset($_POST['sha1'])) {
  echo '<input type="text" name="sha1" class="form-control" value="' . $_POST['sha1'] . '">';
  }
  else {
  echo '<input type="text" name="sha1" class="form-control">';
  }
  ?>
  <input type="submit" name="sha1hash" value="Hash" class="btn btn-success">
</form>
 <div id="sha1hasherresponse"></div>
</div>
</div>
<h1>SHA256 Hasher</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form class="form" action="gen.php" method="POST" id="sha256hasher">
  <?php
  if (isset($_POST['sha256'])) {
  echo '<input type="text" name="sha256" class="form-control" value="' . $_POST['sha256'] . '">';
  }
  else {
  echo '<input type="text" name="sha256" class="form-control">';
  }
  ?>
  <input type="submit" name="sha256hash" value="Hash" class="btn btn-success">
</form>
 <div id="sha256hasherresponse"></div>
</div>
</div>
<h1>SHA512 Hasher</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form class="form" action="gen.php" method="POST" id="sha512hasher">
  <?php
  if (isset($_POST['sha512'])) {
  echo '<input type="text" name="sha512" class="form-control" value="' . $_POST['sha512'] . '">';
  }
  else {
  echo '<input type="text" name="sha512" class="form-control">';
  }
  ?>
  <input type="submit" name="sha512hash" value="Hash" class="btn btn-success">
</form>
 <div id="sha512hasherresponse"></div>
</div>
</div>
</div>
<div id="base64" class="content" hidden>
<h1>Base64 Encoder</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form class="form" action="gen.php" method="POST" id="base64encode">
  <?php
  if (isset($_POST['base64'])) {
  echo '<input type="text" name="base64" class="form-control" value="' . $_POST['base64'] . '">';
  }
  else {
  echo '<input type="text" name="base64" class="form-control">';
  }
  ?>
  <input type="submit" name="base64encode" value="Encode" class="btn btn-success">
</form>
 <div id="base64encoderesponse"></div>
</div>
</div>
<h1>Base64 Decoder</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form class="form" action="gen.php" method="POST" id="base64decode">
  <?php
  if (isset($_POST['base64d'])) {
  echo '<input type="text" name="base64d" class="form-control" value="' . $_POST['base64d'] . '">';
  }
  else {
  echo '<input type="text" name="base64d" class="form-control">';
  }
  ?>
  <input type="submit" name="base64encode" value="Decode" class="btn btn-success">
</form>
 <div id="base64decoderesponse"></div>
</div>
</div>
</div>
<div id="hex" class="content" hidden>
<h1>Bin2Hex</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form class="form" action="gen.php" method="POST" id="bin2hex">
  <?php
  if (isset($_POST['bin2hex'])) {
  echo '<input type="text" name="bin2hex" class="form-control" value="' . $_POST['bin2hex'] . '">';
  }
  else {
  echo '<input type="text" name="bin2hex" class="form-control">';
  }
  ?>
  <input type="submit" name="bin2hex" value="Bin2Hex" class="btn btn-success">
</form>
 <div id="bin2hexresponse"></div>
</div>
</div>
<h1>Hex2Bin</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form class="form" action="gen.php" method="POST" id="hex2bin">
  <?php
  if (isset($_POST['hex2bin'])) {
  echo '<input type="text" name="hex2bin" class="form-control" value="' . $_POST['hex2bin'] . '">';
  }
  else {
  echo '<input type="text" name="hex2bin" class="form-control">';
  }
  ?>
  <input type="submit" name="hex2bin" value="Hex2Bin" class="btn btn-success">
</form>
 <div id="hex2binresponse"></div>
</div>
</div>
</div>

<div id="rot" class="content" hidden>
<h1>ROT</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form class="form" action="gen.php" method="POST" id="rot">
  <?php
  if (isset($_POST['rot'])) {
  $rot = $_POST['rot'];
  $rotations = $_POST['rotations'];
  }
  else {
  $rot = NULL;
  $rotations = NULL;
  }
  echo '<input type="text" name="rot" class="form-control" value="'.$rot.'">';
  echo '<input type="number" name="rotations" id="rotations" class="form-control" value="'.$rotations.'" placeholder="Optional: Amount of rotations (13 default)">';
  echo '<input type="hidden" name="bruteforce" value="0">';
  echo '<label><input type="checkbox" name="bruteforce" id="rotbruteforce" value="1"> Bruteforce</label><br>';
  ?>
  <input type="submit" name="rot" value="Generate" class="btn btn-success">
</form>
 <div id="rotresponse"></div>
</div>
</div>
</div>

<div id="shuffler" class="content" hidden>
<h1>Shuffler</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
<form class="form" action="gen.php" method="POST" id="shuffler">
  <?php
  if (isset($_POST['shuffler'])) {
  $shuffler = $_POST['shuffler'];
  }
  else {
  $shuffler = NULL;
  }
  echo '<input type="text" name="shuffler" class="form-control" value="'.$shuffler.'">';
  ?>
  <input type="submit" name="shuffle" value="Generate" class="btn btn-success">
</form>
 <div id="shufflerresponse"></div>
</div>
</div>
</div>

<div id="openssl" class="content" hidden>
<?php
  $ciphers = "";
  foreach (openssl_get_cipher_methods() as $thiscipher) {
    $ciphers .= "<option value='$thiscipher'>$thiscipher</option>";
  }
?>
<h1>OpenSSL Encrypt</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
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
<h1>OpenSSL Decrypt</h1>
<div class="panel panel-primary">
<div class="panel panel-body">
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
<!----------------------------------------->
</div> <!-- CONTAINER END -->
<div id="error"></div>
<script>
$( document ).ready(function() {
    //function submitForm(formname, responseid) {
    $(".form").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
    
        var form = $(this);
        // var form = $("#"+formname);
        var url = form.attr('action');
        var responseid = form.prop('id')+'response';
        console.log("Generating through "+form.prop('id')+" to "+responseid);
        $.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               beforeSend: function()
               {
                $("#"+responseid).html('<h3>Generating...</h3>'); // show loading
               },
               success: function(data)
               {
                   $("#"+responseid).html(data); // show response from the php script.
                   // console.log("Response successfully received.);
               }
    });
    });
    //}


    // submitForm("base64decode", "base64decoderesponse");
    // submitForm("base64encode", "base64encoderesponse");
    // submitForm("sha512hasher", "sha512hasherresponse");
    // submitForm("sha256hasher", "sha256hasherresponse");
    // submitForm("sha1hasher", "sha1hasherresponse");
    // submitForm("md5hasher", "md5hasherresponse");
    // submitForm("numgen", "numgenresponse");
    // submitForm("stringgen", "stringgenresponse");
    // submitForm("hex2bin", "hex2binresponse");
    // submitForm("bin2hex", "bin2hexresponse");
    // submitForm("rot", "bin2hexresponse");

    if (window.location.hash != '' && window.location.hash != undefined) {
    var hash = window.location.hash;
    var afterhash = hash.replace('#','');
    $(".content").hide();
    $(hash).fadeIn(1000);
    $(".navlink").parent().prop("class", "");
    $("#nav"+afterhash).parent().prop("class", "active");
    console.log("Hash detected: "+hash+", setting nav"+afterhash+" parent as the active tab.");
    } else {
    // Hide everything, put first to avoid a split second of seeing everything | EDIT: Nevermind, putting hidden in html element works better, so the browser doesn't render it at all.
    $(".content").hide();
    // Start by only showing random string generator
    $("#dashboard").fadeIn(1000);
    console.log("Init with #rsgen");
    }

    // Handle navbar
    $(".navlink").click(function() {
        $(".navlink").parent().prop("class", "");
        $(this).parent().prop("class", "active");
        var elementToShow = $(this).attr("href");
        $(".content").hide();
        $(elementToShow).fadeIn();
        if (elementToShow == undefined) {
            $("#error").html("<br><div class='alert alert-danger'>Failed to show page ("+elementToShow+").</div>");
            $("#error").show();
        } else {
            $("#error").hide();
            console.log("Showing "+elementToShow);
        }
    });

    // turn off all autocomplete
    $(".form-control").prop("autocomplete", "off");

    // Seed toggle numgen
    $("#cnumgenseedtoggle").change(function() {
      if ($(this).is(":checked")) {
        $(".cnumgenseed").fadeIn();
      } else {
        $(".cnumgenseed").fadeOut();
      }
    });

    // Toggle charset
   $("#customizecharset").change(function() {
      if ($(this).is(":checked")) {
        $("#charset").fadeIn();
      } else {
        $("#charset").fadeOut();
      }
    });

    // Toggle ROT bruteforce
   $("#rotbruteforce").change(function() {
      if ($(this).is(":checked")) {
        $("#rotations").fadeOut();
      } else {
        $("#rotations").fadeIn();
      }
    });
});
</script>