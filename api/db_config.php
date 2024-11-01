<?php
$host = '23.108.108.119';
$db = 'socialmedia';
$user = 'socialmedia';
$pass = '23VUOvCCZIjkUOH';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}
