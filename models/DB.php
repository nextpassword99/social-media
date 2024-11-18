<?php

class DB
{
  private $host;
  private $port;
  private $dbname;
  private $user;
  private $pass;

  public function __construct()
  {
    $this->cargarEnv();
    $this->host = getenv('HOST_DB');
    $this->port = getenv('PORT_DB') ?: null;
    $this->dbname = getenv('NAME_DB');
    $this->user = getenv('USUARIO_DB');
    $this->pass = getenv('PASSWORD_DB');
  }

  private function cargarEnv()
  {
    $archivo_env = __DIR__ . '/../.env';
    if (file_exists($archivo_env)) {
      $env = parse_ini_file($archivo_env);
      foreach ($env as $clave => $valor) {
        putenv("{$clave}={$valor}");
      }
    }
  }

  public function getConnection()
  {
    try {
      $dsn = "pgsql:host={$this->host};dbname={$this->dbname}";

      if ($this->port) {
        $dsn .= ";port={$this->port}";
      }

      $conn = new PDO($dsn, $this->user, $this->pass);

      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $conn;
    } catch (PDOException $e) {
      die("Error de conexión: " . $e->getMessage());
    }
  }
}
