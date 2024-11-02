<?php
require_once __DIR__ . '/../../api/user/like.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_POST['user_id'];
  $post_id = $_POST['post_id'];

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
