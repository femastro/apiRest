<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Headers: content-type");
header("Access-Control-Allow-Methods: OPTIONS,GET,PUT,POST,DELETE");

require '../vendor/autoload.php';
require '../src/config/db.php';

// Rutas
require_once('../src/rutas/api.php');


$app->run();