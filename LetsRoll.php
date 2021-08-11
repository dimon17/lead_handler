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

function floor2($float, $decimals = 2)
{
    return floor($float * pow(10, $decimals)) / pow(10, $decimals);
}

//App::initialize(new Config\App\Dev);
App::initialize(new Config\App\Prod);
App::run();

echo 'Time spent for all script: ' . floor2(microtime(true) - SCRIPT_STARTED_AT) . PHP_EOL;
exit();