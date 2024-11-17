<?php
require_once __DIR__ . '/../../api/user/like.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);

  $user_id = $data['user_id'] ?? null;
  $post_id = $data['post_id'] ?? null;

  if (!$user_id || !$post_id) {
    http_response_code(400);
    die();
  }

  $response = false;
  if (!checkIfLikeExists($post_id, $user_id)) {
    $response = addLike($post_id, $user_id);
  } else {
    $response = !deleteLike($post_id, $user_id);
  }

  header('Content-Type: application/json');
  echo json_encode($response);
}
