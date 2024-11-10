<?php
include_once __DIR__ . '/../db_config.php';
include_once __DIR__ . '/like.php';
include_once __DIR__ . '/../main/main.php';

/**
 * Devuelve una matriz asociativa que representa al usuario con el $user_id dado
 *
 * @param int $user_id El ID del usuario a recuperar.
 *
 * @return array Los datos del usuario
 */
function getUserById($user_id)
{
  $conn = getConnection();
  $sql = "SELECT * FROM t_usuarios WHERE usuario_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Devuelve una matriz asociativa que representa a los amigos del usuario con el $user_id dado.
 *
 * @param int $user_id El ID del usuario a recuperar sus amigos.
 *
 * @return array Los datos de los amigos
 */
function getFriendsByUserId($user_id)
{
  $conn = getConnection();
  $sql = "SELECT u.usuario_id, u.nombre, u.apellido, u.ubicacion, u.foto_perfil
            FROM t_amistades a
            JOIN t_usuarios u ON (u.usuario_id = a.usuario_id_2)
            WHERE a.usuario_id_1 = :user_id AND a.estado = 'aceptado'";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Devuelve una matriz asociativa que representa a las publicaciones del usuario con el $user_id dado
 *
 * @param int $user_id El ID del usuario a recuperar sus publicaciones.
 *
 * @return array Los datos de las publicaciones
 */
function getPostsByUserId($user_id)
{
  $conn = getConnection();
  $sql = "SELECT p.publicacion_id, p.contenido, p.fecha_publicacion, u.foto_perfil AS image_perfil, u.nombre AS author
            FROM t_publicaciones p
            JOIN t_usuarios u ON u.usuario_id = p.usuario_id
            WHERE p.usuario_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Devuelve una matriz asociativa que representa a los comentarios de la publicación
 * con el $post_id dado.
 *
 * @param int $post_id El ID de la publicación a recuperar sus comentarios.
 *
 * @return array Los datos de los comentarios
 */
function getCommentsByPostId($post_id)
{
  $conn = getConnection();
  $sql = "SELECT c.contenido, c.fecha_comentario, u.foto_perfil AS imagen_perfil, u.nombre AS autor_comentario
            FROM t_comentarios c
            JOIN t_usuarios u ON u.usuario_id = c.usuario_id
            WHERE c.publicacion_id = :post_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':post_id', $post_id);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getImagesByPostId($post_id)
{
  $conn = getConnection();
  $sql = "SELECT url_imagen FROM t_imagenes WHERE publicacion_id = :post_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':post_id', $post_id);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buildResponse($user_id)
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
      'imagen_portada' => $user['foto_perfil'],
      'numero_amigos' => count(getFriendsByUserId($user_id)),
      'location' => $user['ubicacion'],
      'origin' => $user['ubicacion'],
    ],
    'amigos' => getFriendsByUserId($user_id),
    'posts' => []
  ];

  $posts = getPostsByUserId($user_id) ?? [];
  foreach ($posts as $post) {
    $post_id = $post['publicacion_id'];
    $comentarios = getCommentsByPostId($post_id);

    $response['posts'][] = [
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
        'is_liked' => checkIfLikeExists($post_id, $user_id),
      ]
    ];
  }

  return $response;
}
