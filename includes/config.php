<?php

# Dirs
define("DIRSEP", DIRECTORY_SEPARATOR);
define("INCLUDES_DIR", basename(dirname(__FILE__)));
define("CURRENT_DIR", basename(dirname(__FILE__, 1)));
define("APP_ROOT", dirname(__FILE__, 2));

# Site
define("SITE_TITLE", "RAND");
define("SITE_BASE_URL", $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]);
define("DEMO_URL", "https://rand.demo.roste.org/");

# Encoding
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_regex_encoding('UTF-8');