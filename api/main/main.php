<?php
include_once __DIR__ . '/../db_config.php';
include_once __DIR__ . '/../user/perfil_user.php';

/**
 * Devuelve las publicaciones de los amigos del usuario con el $user_id dado.
 *
 * @param int $user_id El ID del usuario para recuperar las publicaciones de amigos.
 *
 * @return array Las publicaciones de amigos
 */
function getPostsFromFriends($user_id)
{
  $friends = getFriendsByUserId($user_id);
  if (empty($friends)) {
    return [];
  }

  $friend_ids = array_column($friends, 'usuario_id');
  $conn = getConnection();
  $placeholders = implode(',', array_fill(0, count($friend_ids), '?'));

  $sql = "SELECT p.publicacion_id, p.contenido, p.fecha_publicacion, u.foto_perfil AS image_perfil, u.nombre AS author
            FROM t_publicaciones p
            JOIN t_usuarios u ON u.usuario_id = p.usuario_id
            WHERE p.usuario_id IN ($placeholders)";
  $stmt = $conn->prepare($sql);
  $stmt->execute($friend_ids);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Devuelve publicaciones aleatorias de cualquier usuario.
 *
 * @param int $limit El número de publicaciones a recuperar.
 *
 * @return array Las publicaciones aleatorias
 */
function getRandomPosts($limit = 5)
{
  $conn = getConnection();
  $sql = "SELECT p.publicacion_id, p.contenido, p.fecha_publicacion, u.foto_perfil AS image_perfil, u.nombre AS author
            FROM t_publicaciones p
            JOIN t_usuarios u ON u.usuario_id = p.usuario_id
            ORDER BY RANDOM() LIMIT :limit";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Devuelve el número de likes de una publicación.
 *
 * @param int $post_id El ID de la publicación.
 *
 * @return int El número de likes.
 */
function getCountLikesByPostId($post_id)
{
  $conn = getConnection();
  $sql = "SELECT COUNT(*) AS total_likes FROM t_likes WHERE publicacion_id = :post_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':post_id', $post_id);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['total_likes'];
}

function buildResponseMain($user_id)
{
  $user = getUserById($user_id);
  if (!$user) {
    return null;
  }

  $response = [
    'user' => [
      'id' => $user['usuario_id'],
      'name' => $user['nombre'] . ' ' . $user['apellido'],
      'profile_image' => $user['foto_perfil'],
    ],
    'posts' => []
  ];

  $posts = getPostsFromFriends($user_id) ?? [];
  if (count($posts) < 5) {
    $posts = array_merge($posts, getRandomPosts());
  }
  foreach ($posts as $post) {
    $post_id = $post['publicacion_id'];
    $comentarios = getCommentsByPostId($post_id);

    $response['posts'][] = [
      'publicacion_id' => $post['publicacion_id'],
      'image_perfil' => $post['image_perfil'],
      'author' => $post['author'],
      'date' => $post['fecha_publicacion'],
      'content_text' => $post['contenido'],
      'content_images' => array_map(function ($c) {
        return $c['url_imagen'];
      }, getImagesByPostId($post_id)),
      'comentarios' => [
        'numero_comentarios' => count($comentarios),
        'content_comentarios' => array_map(function ($c) {
          return [
            'imagen_perfil' => $c['imagen_perfil'],
            'autor_comentario' => $c['autor_comentario'],
            'fecha_comentario' => $c['fecha_comentario'],
            'contenido_comentario' => $c['contenido'],
          ];
        }, $comentarios)
      ],
      'reacciones' => [
        'numero_reacciones' => getCountLikesByPostId($post_id),
      ]
    ];
  }


  return $response;
}
