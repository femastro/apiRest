<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->post('/login', function(Request $request, Response $response){

    $usuario = $request->getParam('usuario');
    $password = $request->getParam('password');

    $sql = "SELECT usuario, privilegios FROM usuarios WHERE usuario='".$usuario."' AND password='".$password."'";
    
    try {
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->query($sql);

        if($resultado->rowCount() > 0 ){
            $user = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($user);
        }
    } catch (PDOException $e) {
        echo "Error -> ",$e;
    }
});