# apiRest

Se debe crear dentro de /src un carpeta /config con la configuracion de conexion a la base de datos

==============================================================================

# Ej: 

/src/config/db.php


Archivo db.php

##<?php

class db{

    private $dbHost = "localhost";
    private $dbName = "BDname";

    private $dbUser = "user.bd";
    private $dbPass = "pass.bd";
    
    //conección 
    public function conectDB(){
      $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName";
      $dbConnecion = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
      $dbConnecion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $dbConnecion;
    }
    
  }

