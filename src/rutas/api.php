<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

/// Tabla Neumaticos

$app->get('/all', function(Request $request, Response $response){
    $sql = "SELECT marca, modelo, medida FROM neumaticos WHERE 1 ";
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

$app->get('/all/marcas', function(Request $request, Response $response){
    $sql = "SELECT DISTINCT(marca) 
            FROM neumaticos 
            WHERE marca != '' ORDER BY marca ASC";
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

    }catch(PDOException $e){
        echo 'Error - > {"error" : {"text":'.$e->getMessage().'}';
    }
});

$app->post('/all/modelos', function(Request $request, Response $response){

    $marca = $request->getParam('marca');

    $sql = "SELECT DISTINCT(modelo) 
            FROM neumaticos 
            WHERE marca = '".$marca."' ORDER BY modelo ASC";
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

    }catch(PDOException $e){
        echo 'Error - > {"error" : {"text":'.$e->getMessage().'}';
    }
});

$app->post('/all/medidas', function(Request $request, Response $response){

    $marca = $request->getParam('marca');
    $modelo = $request->getParam('modelo');

    $sql = "SELECT DISTINCT(medida) 
            FROM neumaticos 
            WHERE marca = '".$marca."' AND modelo = '".$modelo."' ORDER BY medida ASC";
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

    }catch(PDOException $e){
        echo 'Error - > {"error" : {"text":'.$e->getMessage().'}';
    }
});


/// Carga de Imagen

$app->post('/imagen/{codigo}', function(Request $request, Response $response){

    $name = $request->getAttribute('codigo').'.jpg';

    if (!empty($_FILES['file']['name']))
    {
        $ruta = "imgProducto/";
        

        $ruta_provisional = $_FILES['file']['tmp_name'];

        if (!file_exists($ruta)){
            mkdir($ruta, 0777, true);
        }
        $carpeta = $ruta.$name;
        move_uploaded_file($ruta_provisional, $carpeta);

        echo json_encode($carpeta);
    }
    

    
});

/// PRODUCTOS 

$app->get('/neumaticos', function(Request $request, Response $response){
    $sql = "SELECT id, cod_Articulo, marca, modelo, medida, cod_Proveedor, cantidad 
            FROM stockneumaticos 
            ORDER BY cod_Articulo ASC";
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
    $sql = "SELECT id, cod_Articulo, marca, modelo, medida, cod_Proveedor, cantidad, image 
            FROM stockneumaticos WHERE id =".$id;
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

    $marca = $request->getParam('marca');
    $modelo = $request->getParam('modelo');
    $medida = $request->getParam('medida');
    $image = $request->getParam('image');

    $sql = "SELECT cod_Articulo, cod_Proveedor 
            FROM neumaticos 
            WHERE marca='".$marca."' AND modelo='".$modelo."' AND medida='".$medida."'";
    try {
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->query($sql);

        if($resultado->rowCount() > 0 ){
            $articulo = $resultado->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        echo '{"error" : {"text":'.$e->getMessage().'}';
    }
    
    $cod_Articulo = $articulo[0]['cod_Articulo'];
    $cod_Proveedor = $request->getParam('cod_Proveedor');

    if ($cod_Proveedor == ""){
        $cod_Proveedor = $articulo[0]['cod_Proveedor'];
    }

    $cantidad = $request->getParam('cantidad');

    /// Consultar si el Articulo ya existe en la BD....
    $sql = "SELECT cod_Articulo, cantidad 
            FROM stockneumaticos 
            WHERE cod_articulo ='".$cod_Articulo."'";
    try {
        $db = new db();
        $db = $db->conectDB();
        $resultado = $db->query($sql);

        if($resultado->rowCount() > 0 ){
            echo json_encode('El Articulo y Existe ! - Codigo : '.$cod_Articulo);
        }else{
            $sql = "INSERT INTO stockneumaticos 
                (
                id,
                cod_articulo, 
                marca, 
                modelo, 
                medida, 
                cod_Proveedor, 
                cantidad,
                precio,
                precioventa,
                fecha,
                ultimocosto,
                ubicacion,
                image
                )
                VALUES 
                (
                null,
                :cod_Articulo, 
                :marca, 
                :modelo, 
                :medida, 
                :cod_Proveedor, 
                :cantidad,
                null,
                null,
                null,
                null,
                0,
                :image
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
                $resultado->bindParam(':image', $image);

                $resultado->execute();

                echo json_encode('Nuevo Articulo Agregado !');  

            }catch(PDOException $e){
                echo '{"error" : {"text":'.$e->getMessage().'}';
            }
            $resultado = null;
            $db = null;
        }
    } catch (PDOException $e) {
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
    $image = $request->getParam('image');
  
    $sql = "UPDATE stockneumaticos SET
            cod_Articulo = :cod_Articulo,
            modelo = :modelo,
            marca = :marca,
            medida = :medida,
            cod_Proveedor = :cod_Proveedor,
            cantidad = :cantidad,
            image = :image 
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
        $resultado->bindParam(':image', $image);

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

    $usuario = $request->getParam('username');
    $password = md5($request->getParam('password'));

    $sql = "SELECT usuario, privilegios 
            FROM usuarios 
            WHERE usuario='".$usuario."' AND password='".$password."'";
    
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