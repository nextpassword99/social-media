<?php
include_once __DIR__ . '/../db_config.php';

function obtenerUsuariosAleatorios($limit = 10)
{
  $conn = getConnection();
  $sql = "SELECT * FROM t_usuarios ORDER BY RANDOM() LIMIT :limit";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
