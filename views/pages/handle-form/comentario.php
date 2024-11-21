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

if (!$data || !isset($data['post_id'], $data['comentario'])) {
  echo json_encode([
    'procesado' => false,
    'mensaje' => 'Datos incompletos'
  ]);
  exit;
}

require_once __DIR__ . '/../../../repositories/ComentarioRepository.php';
require_once __DIR__ . '/../../../services/ComentarioService.php';

$post_id = $data['post_id'];
$comentario = $data['comentario'];
$usuario_id_session = $_SESSION['usuario_id'];

$esEnviado = Post::setComentario($post_id, $usuario_id_session, $comentario);
echo json_encode($esEnviado);
