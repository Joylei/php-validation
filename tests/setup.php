<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Joylei\\Validation\\', __DIR__.'/../src');
