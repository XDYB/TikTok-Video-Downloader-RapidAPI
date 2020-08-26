<?php
define("API_URL", 'https://tnw.ssovit.com/');
define("BASE_DIR", __DIR__ . '/');
define("CONFIG_DIR", BASE_DIR . '_config/');
define("INC_DIR", BASE_DIR . 'includes/');
define("VERSION", '1.0.0');
define("USER_AGENT", 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36');
//define("CONFIG_DIR", CACHE_DIR . 'cache/');
define("CONFIG_DIR", sys_get_temp_dir());

require_once BASE_DIR . "vendor/autoload.php";
