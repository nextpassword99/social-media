<?php

class DB
{
  private $host = 'localhost';
  private $port = '5432';
  private $dbname = 'postgres';
  private $user = 'postgres';
  private $pass = '123456';


  public function getConnection()
  {
    try {
      $conn = new PDO(
        "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}",
        $this->user,
        $this->pass
      );

      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $conn;
    } catch (PDOException $e) {
      die("Error de conexión: " . $e->getMessage());
    }
  }
}
