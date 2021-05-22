<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");

require '../vendor/autoload.php';
require '../src/config/db.php';

// Ruta productos
require '../src/rutas/productos.php';

$app->run();