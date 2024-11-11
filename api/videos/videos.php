<?php
include_once __DIR__ . '/../db_config.php';

function obtenerVideosAleatorios() {
  $conn = getConnection();
  $sql = "SELECT * FROM t_videos ORDER BY RANDOM() LIMIT 10";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerVideoPorIdPost($id_post) {
  $conn = getConnection();
  $sql = "SELECT * FROM t_videos WHERE publicacion_id = :id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':id', $id_post);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function obtenerPostPorId($id) {
  $conn = getConnection();
  $sql = "SELECT * FROM t_publicaciones WHERE publicacion_id = :id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}
