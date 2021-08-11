<?php
define('SCRIPT_STARTED_AT', microtime(true));
define('CLI', TRUE); # Скрипт запущен из PHP_CLI, не из PHP_FPM
define("DOC_ROOT", __DIR__ . '/../../../../../'); # Путь к корневой папке проекта

date_default_timezone_set("Europe/Moscow");
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
mb_internal_encoding("UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$loader = require_once DOC_ROOT . 'vendor/autoload.php';