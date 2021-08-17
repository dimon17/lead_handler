<?php
define('SCRIPT_STARTED_AT', microtime(true));
define('CLI', TRUE); # Скрипт запущен из PHP_CLI, не из PHP_FPM
define("DOC_ROOT", dirname(__FILE__) . '/'); # Путь к корневой папке проекта

date_default_timezone_set("Europe/Moscow");
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
mb_internal_encoding("UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$loader = require_once __DIR__ . '/vendor/autoload.php';

use Application\App;
use Tools\Math\NumbersConverter;

if (getenv('DEV')) App::initialize(new Config\App\Dev);
else App::initialize(new Config\App\Prod);

App::run();

echo 'Time spent for all script: ' . NumbersConverter::floor(microtime(true) - SCRIPT_STARTED_AT) . PHP_EOL;
exit();