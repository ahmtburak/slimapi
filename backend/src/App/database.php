<?php

namespace App;

use PDO;

class Database
{

  public function getConnection(): PDO
  {
    $conn = "mysql:host=localhost;dbname=slimdb;charset=utf8";

    $pdo = new PDO($conn, 'root', '', [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);


    return $pdo;
  }
}
