<?php
require __DIR__.'/vendor/autoload.php';
require_once __DIR__.('/../../config_var.php');

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

$rates = new \Matchish\ExchangeRates($db);

$sources = [];

array_shift($argv);

if (empty($argv)) {
    throw new Exception('No filename.');
}

$rates->parse($argv);
$rates->save();

die('ok');
