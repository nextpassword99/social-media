<?php
session_start();
require_once __DIR__ . '/../../../models/Auth.php';

header('Content-Type: application/json');

if (!Auth::validarSession()) {
  echo json_encode([
    'procesado' => false,
    'mensaje' => 'Usuario no autenticado'
  ]);
  exit;
}

$data = json_decode(file_get_contents('php://input'), true);

require_once __DIR__ . '/../../../models/Post.php';

$post_id = $data['post_id'] ?? null;
$usuario_id_session = $_SESSION['usuario_id'] ?? null;
$comentario = $data['comentario'] ?? null;

$esEnviado = Post::setComentario($post_id, $usuario_id_session, $comentario);
echo json_encode($esEnviado);
