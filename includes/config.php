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

# UI
// Action button style: false = filled (btn-primary), true = outline (btn-outline-primary).
// Applied via btnClass() in PHP and JS. Radio/checkbox toggle groups (btn-check) always stay outline.
define("BTN_OUTLINE", false);

# Dice roller limits (form and handle_dice_roll)
define("DICE_MAX_COUNT", 100);
define("DICE_MIN_SIDES", 2);
define("DICE_MAX_SIDES", 1000);
# Coin flip limit (form and handle_coin_flip)
define("COIN_MAX_COUNT", 100);
# List shuffler limits (form and handle_list_shuffle)
define("LIST_SHUFFLE_MAX_ITEMS", 10000);
define("LIST_SHUFFLE_MAX_BYTES", 1000000);
# Magic 8-ball question length (form and handle_eightball)
define("EIGHTBALL_MAX_QUESTION", 200);
# Passphrase generator (form and handle_passphrase); word list in includes/data/
define("PASSPHRASE_MIN_WORDS", 3);
define("PASSPHRASE_MAX_WORDS", 20);
define("PASSPHRASE_MAX_COUNT", 100);
define("PASSPHRASE_SYMBOLS", "!@#$%^&*?=+");

# Levenshtein: max characters per string (O(m×n) pure-PHP UTF-8 implementation)
define("LEVENSHTEIN_MAX_CHARS", 1000);

# Encoding
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_regex_encoding('UTF-8');