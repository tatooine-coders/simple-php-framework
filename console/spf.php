<?php
use TC\Lib\DB;
use TC\Lib\Config;

require_once 'vendor/autoload.php';

require_once 'Generator/Generator.php';
require_once 'Generator/ModelsGenerator.php';
Config::load('config.php');	
DB::connect();
$tables = DB::getTablesNames();
$tables = DB::getTablesColumns($tables);
$modelsGenerator = new ModelsGenerator();
$modelsGenerator->force = (isset($argv[1]) && $argv[1] == 'force');
$modelsGenerator->setTables($tables);
$modelsGenerator->generateEntities();
$modelsGenerator->generateCollections();

?>