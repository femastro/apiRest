<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->get('/productos/neumaticos', function(Request $request, Response $response){
    $sql = "SELECT * FROM stockneumaticos";
    try{
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->query($sql);

        if ($resultado->rowCount() > 0){
        $articulos = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($articulos);
        }else {
        echo json_encode("No existen productos en la BBDD.");
        }
        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
});

// GET Recueperar Articulo por ID 
$app->get('/productos/neumaticos/{id}', function(Request $request, Response $response){
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
$app->post('/productos/neumaticos/nuevo', function(Request $request, Response $response){

   $cod_Articulo = $request->getParam('cod_Articulo');
   
   $modelo = $request->getParam('modelo');
   $marca = $request->getParam('marca');
   $medida = $request->getParam('medida');
   $cod_Proveedor = $request->getParam('cod_Proveedor');
   $cantidad = $request->getParam('cantidad');
   $precio = $request->getParam('precio');
   $precioventa = $request->getParam('precioventa');
   $ultimocosto = $request->getParam('ultimocosto');
   $ubicacion = $request->getParam('ubucacion');
  
    $sql = "INSERT INTO stockneumaticos 
        (   
        cod_articulo, 
        modelo, 
        marca, 
        medida, 
        cod_Proveedor, 
        cantidad, 
        precio, 
        precioventa, 
        ultimocosto, 
        ubicacion
        )
        VALUES 
        (   
        :cod_Articulo, 
        :modelo, 
        :marca, 
        :medida, 
        :cod_Proveedor, 
        :cantidad, 
        :precio, 
        :precioventa, 
        :ultimocosto, 
        :ubicacion
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
        $resultado->bindParam(':precio', $precio);
        $resultado->bindParam(':precioventa', $precioventa);
        $resultado->bindParam(':ultimocosto', $ultimocosto);
        $resultado->bindParam(':ubicacion', $ubicacion);

        $resultado->execute();
        echo json_encode("Nuevo articulo guardado.");  

        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
});

// DELETE borrar Articulo
$app->delete('/productos/neumaticos/delete/{id}', function(Request $request, Response $response){
    
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM stockneumaticos WHERE id =".$id;
        
    try{
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->prepare($sql);
        $resultado->execute();

        if ($resultado->rowCount() > 0) {
        echo json_encode("Articulo eliminado.");  
        }else {
        echo json_encode("No existe Articulo con este ID.");
        }

        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
}); 

// PUT Modificar Articulo 
$app->put('/productos/neumaticos/modificar/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    $cod_Articulo = $request->getParam('cod_Articulo');
    $modelo = $request->getParam('modelo');
    $marca = $request->getParam('marca');
    $medida = $request->getParam('medida');
    $cod_Proveedor = $request->getParam('cod_Proveedor');
    $cantidad = $request->getParam('cantidad');
    $precio = $request->getParam('precio');
    $precioventa = $request->getParam('precioventa');
    $ultimocosto = $request->getParam('ultimocosto');
    $ubicacion = $request->getParam('ubucacion');
 
  
    $sql = "UPDATE stockneumaticos SET
            cod_Articulo = :cod_Articulo,
            modelo = :modelo,
            marca = :marca,
            medida = :medida,
            cod_Proveedor = :cod_Proveedor,
            cantidad = :cantidad,
            precio = :precio,
            precioventa = :precioventa,
            ultimocosto = :ultimocosto,
            ubicacion = :ubicacion
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
        $resultado->bindParam(':precio', $precio);
        $resultado->bindParam(':precioventa', $precioventa);
        $resultado->bindParam(':ultimocosto', $ultimocosto);
        $resultado->bindParam(':ubicacion', $ubicacion);

        $resultado->execute();
        echo json_encode("Articulo modificado.");  

        $resultado = null;
        $db = null;
    }catch(PDOException $e){
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
}); 

