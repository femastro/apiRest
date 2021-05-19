<?php
header('content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require '../vendor/autoload.php';
require '../src/config/db.php';

// Ruta productos
require '../src/rutas/productos.php';

$app->run();