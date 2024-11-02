<?php
function getConnection()
{
  $host = 'localhost';
  $port = '5432';
  $dbname = 'postgres';
  $user = 'postgres';
  $pass = '123456';

  try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
  }
}
