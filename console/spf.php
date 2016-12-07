<?php
use TC\Lib\DB;
use TC\Lib\Config;
use TC\Lib\File;

require_once 'vendor/autoload.php';

require_once 'Generator/Generator.php';
require_once 'Generator/ModelsGenerator.php';
Config::load('config.php');	
DB::connect();
$tables = DB::getTablesNames();
$tables = DB::getTablesColumns($tables);
ModelsGenerator::$force = (isset($argv[1]) && $argv[1] == 'force');
ModelsGenerator::generateEntities($tables);
ModelsGenerator::generateCollections($tables);
