<?php

  require "env.php";

  class db{
  
    private $dbHost = "DBHOST";
    private $dbName = "BDname";

    private $dbUser = "DBUSER";
    private $dbPass = "DBPASSWORD";
    
    //conección 
    public function conectDB(){
      $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName";
      $dbConnecion = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
      $dbConnecion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $dbConnecion;
    }

  }