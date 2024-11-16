<?php

$data = json_decode(file_get_contents('php://input'), true);

require_once __DIR__ . '/../../models/Post.php';

$post_id = $data['post_id'] ?? null;
$usuario_id = $data['usuario_id'] ?? null;
$comentario = $data['comentario'] ?? null;

$esEnviado = Post::setComentario($post_id, $usuario_id, $comentario);
echo json_encode($esEnviado);
