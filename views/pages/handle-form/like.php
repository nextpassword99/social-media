<?php
session_start();
require_once __DIR__ . '/../../../models/Post.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);

  $user_id = $data['user_id'] ?? null;
  $post_id = $data['post_id'] ?? null;

  if (!$user_id || !$post_id) {
    http_response_code(400);
    die();
  }

  $response = false;
  $post = new Post($post_id);
  if (!$post->checkIfLikeExists($post_id, $user_id)) {
    $response = $post->addLike($post_id, $user_id);
  } else {
    $response = !$post->deleteLike($post_id, $user_id);
  }

  header('Content-Type: application/json');
  echo json_encode($response);
}
