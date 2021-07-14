<?php

class db{

    private $dbHost = "167.250.5.45";
    private $dbName = "brown_cueto";

    private $dbUser = "brown_fer";
    private $dbPass = "Pirulo/!";
    
    //conección 
    public function conectDB(){
      $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName";
      $dbConnecion = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
      $dbConnecion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $dbConnecion;
    }
    
  }