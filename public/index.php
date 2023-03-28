<?php

define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);
define('LOGS_PATH', dirname(__DIR__)."/logs");
define('DB_PATH', dirname(__DIR__)."/db");
define('DEBUG', getenv('DEBUG'));

if (defined('DEBUG') && DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL & ~E_DEPRECATED);
}

require_once("../vendor/autoload.php");
require_once("../src/index.php");