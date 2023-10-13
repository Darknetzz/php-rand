<!-- <ul class="nav nav-tabs">
  <li role="presentation"><a href="#rsgen" id="navrsgen" class="navlink" cshow="rsgen">String Generator</a></li>
  <li role="presentation"><a href="#cnumgen" id="navcnumgen" class="navlink" cshow="cnumgen">Number Generator</a></li>
  <li role="presentation"><a href="#md5" id="navmd5" class="navlink" cshow="md5">MD5 Hasher</a></li>
  <li role="presentation"><a href="#sha" id="navsha" class="navlink" cshow="sha">SHA Hasher</a></li>
  <li role="presentation"><a href="#base64" id="navbase64" class="navlink" cshow="base64">Base64</a></li>
  <li role="presentation"><a href="#hex" id="navhex" class="navlink" cshow="hex">Hex</a></li>
  <li role="presentation"><a href="#rot" id="navrot" class="navlink" cshow="rot">ROT</a></li>
  <li role="presentation"><a href="#shuffler" id="navshuffler" class="navlink" cshow="shuffler">Shuffler</a></li>
  <li role="presentation"><a href="#openssl" id="navopenssl" class="navlink" cshow="openssl">OpenSSL</a></li>
</ul> -->

<!-- <ul class="nav nav-tabs">
  <li role="presentation"><a href="#rsgen" id="navrsgen" class="navlink" cshow="rsgen">String Generator</a></li>
  <li role="presentation"><a href="#cnumgen" id="navcnumgen" class="navlink" cshow="cnumgen">Number Generator</a></li>
  <li role="presentation"><a href="#hash" id="navhash" class="navlink" cshow="hash">Hash</a></li>
  <li role="presentation"><a href="#base" id="navbase" class="navlink" cshow="base">Base</a></li>
  <li role="presentation"><a href="#binhex" id="navbinhex" class="navlink" cshow="binhex">Bin/Hex</a></li>
  <li role="presentation"><a href="#rot" id="navrot" class="navlink" cshow="rot">ROT</a></li>
  <li role="presentation"><a href="#shuffler" id="navshuffler" class="navlink" cshow="shuffler">Shuffler</a></li>
  <li role="presentation"><a href="#openssl" id="navopenssl" class="navlink" cshow="openssl">OpenSSL</a></li>
</ul> -->

<?php
$modules = [
  ["String Tools", "rsgen"]   , 
  ["Number Tools", "cnumgen"] , 
  ["Hash"            , "hash"]    , 
  ["Base"            , "base"]    , 
  ["Bin/Hex"         , "binhex"]  , 
  ["ROT"             , "rot"]     , 
  ["Shuffler"        , "shuffler"], 
  ["OpenSSL"         , "openssl"] , 
];

$navItems = "";
foreach ($modules as $module) {
  $formalName = $module[0];
  $shortName  = $module[1];
  $navItems .= '
  <li class="nav-item">
    <a class="nav-link" href="#'.$shortName.'" id="nav'.$shortName.'" cshow="'.$shortName.'">'.$formalName.'</a>
  </li>
  ';
}
?>

<ul class="nav nav-tabs">
  <?php echo $navItems; ?>
</ul>