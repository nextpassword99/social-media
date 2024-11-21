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

require_once __DIR__ . '/../../../repositories/LikeRespository.php';
require_once __DIR__ . '/../../../services/LikeService.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['post_id'])) {
  echo json_encode([
    'procesado' => false,
    'mensaje' => 'Datos incompletos o incorrectos',
  ]);
}

$usuario_id_session = $_SESSION['usuario_id'];
$post_id = $data['post_id'];

$DB = new DB();
$LikeRepository = new LikeRepository($DB);
$LikeService = new LikeService($LikeRepository);

$isLike = !$LikeService->checkIfLikeExists($post_id, $usuario_id_session)
  ? $isLike = $LikeService->addLike($post_id, $usuario_id_session)
  : $isLike = !$LikeService->deleteLike($post_id, $usuario_id_session);


echo json_encode($response);
