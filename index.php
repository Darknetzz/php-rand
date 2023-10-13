<?php header('Content-Type: text/html; charset=utf-8'); ?>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<!-- Latest compiled and minified CSS -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->

<!-- Optional theme -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

<style>
body {
  background-color:#262626;
  background-color:#111;
  color:#FFF;
}
/* .panel {
  background-color:#202020;
  color:#FFF;
} */

select, select:active,
input, input:active, input:focus,
.card, .jumbotron, 
.form-control, .form-control:active, .form-control:focus,
.form-select, .form-select:active, .form-select:focus {
  background-color:#303030;
  color:#FFF;
}

.description {
  color: #888;
}

li.active {
  background-color:#303030;
}

footer {
  color:grey;
  text-align:center;
  position:absolute;
  left:50%;
  bottom:5px;
}
</style>

<title>Rand</title>

<?php include_once("navbar.php"); ?>
<br>
<div class="container">
<div id="dashboard" class="content" style="display:none;">
<div class="card">
  <h1 class="card-header">Welcome to RAND!</h1>
  <p class="card-body">This page includes a bunch of tools. Choose a tool above to get started.</p>
</div>
</div>
<div id="rsgen" class="content" style="display:none;">
<div class="card">
<h1 class="card-header">Random String Generator</h1>
<div class="card card-body">
<div class="alert alert-secondary">This will generate a string with the charset defined.</div>
<form class="form" action="gen.php" method="POST" id="stringgen">
<input type="hidden" name="containnumbers" value="0">
<input type="hidden" name="containletters" value="0">
<input type="hidden" name="containuletters" value="0">
<input type="hidden" name="containsymbols" value="0">
<input type="hidden" name="customizecharset" value="0">
<select name="digits" class="form-select">
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

<hr>

<div class="card">
  <h1 class="card-header">String Repeater</h1>
  <div class="card-body">
  <div class="alert alert-secondary">This will repeat the given string n number of times</div>
  <form class="form" action="gen.php" method="POST" id="strrepeat">
    String to repeat:
    <input type="text" name="repeatstr" class="form-control">
    How many times to repeat:
    <input type="number" name="repeatamt" class="form-control">
    <input type="submit" class="btn btn-success" value="Generate">
  </form>
  <div id="strrepeatresponse"></div>
  </div>
</div>
</div>

<!-------------------------------------------------------------------------------->
<div id="cnumgen" class="content" style="display:none;">
<div class="card card-primary">
<h1 class="card-header">Number Generator</h1>
    <div class="card card-body">
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
            <div class="cnumgenseed" style="display:none;">
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


<div id="hash" class="content" style="display:none;">
<div class="card card-primary">
<h1 class="card-header">Hasher</h1>
<div class="card card-body">
<form class="form" action="gen.php" method="POST" id="hasher">
  <input type="text" name="hash" class="form-control">
  <input type="submit" name="hasher" value="Hash" class="btn btn-success">
</form>
  <div id="hasherresponse"></div>
</div>
</div>
</div>


<div id="base" class="content" style="display:none;">
<div class="card card-primary">
<h1 class="card-header">Base</h1>
<div class="card card-body">
<span class="description">Input any text or base encoded string below, and this tool will convert it to all other base formats.</span>
<form class="form" action="gen.php" method="POST" id="base">
  <input type="text" name="base" class="form-control">
  <select name="from" class="form-select">
    <option value="text" disabled selected>Please choose input type [default: text/base36]...</option>
    <?php
    for ($i = 2; $i <= 36; $i++) {
      if ($i == 2) {
        $name = "Base $i (binary)";
      } else {
        $name = "Base $i";
      }
      echo "<option value='$i'>$name</option>";
    }
    ?>
  </select>
  <input type="submit" value="Convert" class="btn btn-success">
</form>
 <div id="baseresponse"></div>
</div>
</div>
</div>



<div id="binhex" class="content" style="display:none;">
<div class="card card-primary">
<h1 class="card-header">Bin2Hex</h1>
<div class="card card-body">
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

<br>

<div class="card card-primary">
<h1 class="card-header">Hex2Bin</h1>
<div class="card card-body">
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

<div id="rot" class="content" style="display:none;">
<div class="card card-primary">
<h1 class="card-header">ROT</h1>
<div class="card card-body">
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

<div id="shuffler" class="content" style="display:none;">
<div class="card card-primary">
<h1 class="card-header">Shuffler</h1>
<div class="card card-body">
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

<div id="openssl" class="content" style="display:none;">
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
<!----------------------------------------->
</div> <!-- CONTAINER END -->
<div id="error"></div>


<footer>Made with ❤️ by <a href="https://github.com/darknetzz" target="_blank" style="color:grey;">darknetzz</a></footer>

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
               error: function(data)
               {
                  console.log(data);
                  $("#"+responseid).html("<div class='alert alert-danger'>Error: "+data.statusText+"</div>");
               },
               success: function(data){
                  $("#"+responseid).html(data); // show response from the php script.
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
    $(".nav-link").parent().prop("class", "");
    $("#nav"+afterhash).parent().prop("class", "nav-item active");
    console.log("Hash detected: "+hash+", setting nav"+afterhash+" parent as the active tab.");
    } else {
    // Hide everything, put first to avoid a split second of seeing everything | EDIT: Nevermind, putting hidden in html element works better, so the browser doesn't render it at all.
    $(".content").hide();
    // Start by only showing random string generator
    $("#dashboard").fadeIn(1000);
    console.log("Init with #rsgen");
    }

    // Handle navbar
    $(".nav-link").click(function() {
        var navLink = $(this);
        var navItems = $(".nav-item");
        var navItem = navLink.parent();

        navItems.prop("class", "nav-item");
        navItem.prop("class", "nav-item active");

        // $(".nav-link").prop("class", "nav-link");
        // $(this).prop("class", "nav-link active");

        var elementToShow = $(this).attr("href");
        $(".content").hide();
        $(elementToShow).fadeIn();

        if (elementToShow == undefined) {
            console.log("unable to show "+elementToShow);
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