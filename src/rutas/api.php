<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

/// PRODUCTOS 

$app->get('/neumaticos', function(Request $request, Response $response){
    $sql = "SELECT * FROM stockneumaticos WHERE 1 LIMIT 50";
    try{
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->query($sql);

        if ($resultado->rowCount() > 0){
            $articulos = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($articulos);
        }else {
            echo json_encode("No existen articulos en la BBDD.");
        }
        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo 'Error - > {"error" : {"text":'.$e->getMessage().'}';
    }
});

// GET Recueperar Articulo por ID 
$app->get('/neumaticos/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM stockneumaticos WHERE id =".$id;
    try{
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->query($sql);

        if ($resultado->rowCount() > 0){
            $articulo = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($articulo);
        }else {
            echo json_encode("No existen articulos en la BBDD con este ID.");
        }
        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
}); 

// POST Crear nuevo Articulo 
$app->post('/neumaticos/new', function(Request $request, Response $response){

    $sql = "SELECT MAX(cod_Articulo) as codigo FROM neumaticos";
    try {
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->query($sql);

        if($resultado->rowCount() > 0 ){
            $articulo = $resultado->fetchAll(PDO::FETCH_ASSOC);
            //$codigo = $articulo[0]['codigo'];
        }
    } catch (PDOException $e) {
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
    
    $cod= intval(substr($articulo[0]['codigo'], 1))+1;
    //$cod_Articulo = $request->getParam('cod_Articulo');
    $cod_Articulo = "N".$cod;

    $modelo = $request->getParam('modelo');
    $marca = $request->getParam('marca');
    $medida = $request->getParam('medida');
    $cod_Proveedor = $request->getParam('cod_Proveedor');
    $cantidad = $request->getParam('cantidad');
  
    $sql = "INSERT INTO stockneumaticos 
        (   
        cod_articulo, 
        marca, 
        modelo, 
        medida, 
        cod_Proveedor, 
        cantidad, 
        )
        VALUES 
        (   
        :cod_Articulo, 
        :marca, 
        :modelo, 
        :medida, 
        :cod_Proveedor, 
        :cantidad, 
        )";
    
    try{
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->prepare($sql);

        $resultado->bindParam(':cod_Articulo', $cod_Articulo);
        $resultado->bindParam(':modelo', $modelo);
        $resultado->bindParam(':marca', $marca);
        $resultado->bindParam(':medida', $medida);
        $resultado->bindParam(':cod_Proveedor', $cod_Proveedor);
        $resultado->bindParam(':cantidad', $cantidad);

        $resultado->execute();
        echo json_encode("Nuevo articulo guardado.");  

        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }

});

// DELETE borrar Articulo
$app->delete('/neumaticos/delete/{id}', function(Request $request, Response $response){
    
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM stockneumaticos WHERE id =".$id;
        
    try{
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->prepare($sql);
        $resultado->execute();

        if ($resultado->rowCount() > 0) {
        echo '{"status":200,"message":"Articulo eliminado."}';  
        }else {
        echo '{"status":"404","message":"No existe Articulo con este ID."}';
        }

        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
}); 

// PUT Modificar Articulo 
$app->put('/neumaticos/update/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    $cod_Articulo = $request->getParam('cod_Articulo');
    $modelo = $request->getParam('modelo');
    $marca = $request->getParam('marca');
    $medida = $request->getParam('medida');
    $cod_Proveedor = $request->getParam('cod_Proveedor');
    $cantidad = $request->getParam('cantidad');
  
    $sql = "UPDATE stockneumaticos SET
            cod_Articulo = :cod_Articulo,
            modelo = :modelo,
            marca = :marca,
            medida = :medida,
            cod_Proveedor = :cod_Proveedor,
            cantidad = :cantidad 
            WHERE id = ".$id;
        
    try{
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->prepare($sql);

        $resultado->bindParam(':cod_Articulo', $cod_Articulo);
        $resultado->bindParam(':modelo', $modelo);
        $resultado->bindParam(':marca', $marca);
        $resultado->bindParam(':medida', $medida);
        $resultado->bindParam(':cod_Proveedor', $cod_Proveedor);
        $resultado->bindParam(':cantidad', $cantidad);

        $resultado->execute();

        echo json_encode("Articulo modificado.");  

        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
}); 


//// LOGIN

$app->post('/login', function(Request $request, Response $response){

    $usuario = $request->getParam('usuario');
    $password = md5($request->getParam('password'));

    $sql = "SELECT usuario, privilegios FROM usuarios WHERE usuario='".$usuario."' AND password='".$password."'";
    
    try {
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->query($sql);

        if($resultado->rowCount() > 0 ){
            $user = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($user);
        }else {
            echo "Ha Ocurrido un Error !.";
        }
    } catch (PDOException $e) {
        echo "Error -> ",$e;
    }
});


//// USERS 

$app->get('/users', function(Request $request, Response $response){
    $sql = "SELECT * FROM usuarios WHERE 1";
    try{
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->query($sql);

        if ($resultado->rowCount() > 0){
            $users = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($users);
        }else {
            echo "No existen usuarios en la BBDD.";
        }
        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
});

$app->get('/users/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM usuarios WHERE idusuarios =".$id;
    try{
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->query($sql);

        if ($resultado->rowCount() > 0){
            $user = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($user);
        }else {
            echo json_encode("No existen usuario en la BBDD con este ID.");
        }
        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
}); 

