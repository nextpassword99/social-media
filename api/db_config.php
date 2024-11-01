<?php
function getConnection()
{
  $host = '23.108.108.219';
  $port = '5432';
  $dbname = 'socialmedia';
  $user = 'socialmedia';
  $pass = '23VUOvCCZIjkUOH';

  try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
  }
}
