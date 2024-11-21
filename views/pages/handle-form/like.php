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

require_once __DIR__ . '/../../../models/Post.php';

$data = json_decode(file_get_contents('php://input'), true);

$usuario_id_session = $_SESSION['usuario_id'] ?? null;
$post_id = $data['post_id'] ?? null;

if (!$usuario_id_session || !$post_id) {
  http_response_code(400);
  die();
}

$response = false;
$post = new Post($post_id);
if (!$post->checkIfLikeExists($post_id, $usuario_id_session)) {
  $response = $post->addLike($post_id, $usuario_id_session);
} else {
  $response = !$post->deleteLike($post_id, $usuario_id_session);
}

echo json_encode($response);
