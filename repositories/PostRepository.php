<?php
require_once __DIR__ . '/ImgRepository.php';
require_once __DIR__ . '/VideoRepository.php';
require_once __DIR__ . '/../models/Post.php';

class PostRepository
{
  private $db;

  public function __construct(DB $db)
  {
    $this->db = $db;
  }

  public function crearPost($usuario_id, $post_text, $imgs = [], $videos = [])
  {
    $conn = $this->db->getConnection();
    $query = "INSERT INTO t_posts (usuario_id, descripcion) VALUES (:usuario_id, :descripcion)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':descripcion', $post_text, PDO::PARAM_STR);
    $stmt->execute();

    $post_id = $conn->lastInsertId();

    foreach ($imgs as $key => $value) {
      $query = "INSERT INTO t_imagenes (post_id, url_imagen) VALUES (:post_id, :url_imagen)";
      $stmt = $conn->prepare($query);
      $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
      $stmt->bindParam(':url_imagen', $value, PDO::PARAM_STR);
      $stmt->execute();
    }


    foreach ($videos as $key => $value) {
      $query = "INSERT INTO t_videos (post_id, url_video) VALUES (:post_id, :url_video)";
      $stmt = $conn->prepare($query);
      $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
      $stmt->bindParam(':url_video', $value, PDO::PARAM_STR);
      $stmt->execute();
    }

    if ($stmt->rowCount() > 0) {
      return $conn->lastInsertId();
    }

    return false;
  }


  public function getPostsPorUsuarioId($usuario_id)
  {
    $conn = $this->db->getConnection();
    $query = "WITH comentario AS (SELECT c.post_id,
                                        c.contenido                                                                 AS comentario,
                                        u_comentario.usuario_id                                                     AS usuario_id_comentario,
                                        u_comentario.nombre                                                         AS comentario_usuario_nombre,
                                        u_comentario.apellido                                                       AS comentario_usuario_apellido,
                                        u_comentario.foto_perfil                                                    AS comentario_usuario_foto_perfil,
                                        ROW_NUMBER() OVER (PARTITION BY c.post_id ORDER BY c.fecha_comentario DESC) AS rn
                                  FROM t_comentarios c
                                          JOIN t_usuarios u_comentario ON c.usuario_id = u_comentario.usuario_id),
                  like_status AS (SELECT publicacion_id, usuario_id
                                  FROM t_likes
                                  WHERE usuario_id = :user_id)
              SELECT p.post_id,
                    p.usuario_id,
                    p.descripcion,
                    p.fecha_publicacion,
                    u.nombre                AS usuario_nombre,
                    u.apellido              AS usuario_apellido,
                    u.foto_perfil           AS usuario_foto_perfil,
                    COUNT(l.publicacion_id) AS likes_count,
                    COUNT(c.post_id)        AS comentarios_count,
                    lc.comentario,
                    lc.usuario_id_comentario,
                    lc.comentario_usuario_nombre,
                    lc.comentario_usuario_apellido,
                    lc.comentario_usuario_foto_perfil,
                    CASE
                        WHEN ls.publicacion_id IS NOT NULL THEN true
                        ELSE false
                        END                 AS is_like
              FROM t_posts p
                      JOIN t_usuarios u ON p.usuario_id = u.usuario_id
                      LEFT JOIN t_likes l ON p.post_id = l.publicacion_id
                      LEFT JOIN t_comentarios c ON p.post_id = c.post_id
                      LEFT JOIN comentario lc ON p.post_id = lc.post_id AND lc.rn = 1
                      LEFT JOIN like_status ls ON p.post_id = ls.publicacion_id
              WHERE p.usuario_id = :user_id
              GROUP BY p.post_id, u.usuario_id, lc.comentario, lc.usuario_id_comentario, lc.comentario_usuario_nombre,
                      lc.comentario_usuario_apellido, lc.comentario_usuario_foto_perfil, ls.publicacion_id
              ORDER BY p.fecha_publicacion DESC;
";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $posts_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $posts = [];

    foreach ($posts_data as $post_data) {
      $ImgRepository = new ImgRepository($this->db);
      $VideoRepository = new VideoRepository($this->db);
      $imgs = $ImgRepository->getImgsPorPostId($post_data['post_id']);
      $videos = $VideoRepository->getVideosPorPostId($post_data['post_id']);
      $comentarios = [
        'comentario' => $post_data['comentario'] ?? '',
        'comentario_usuario_nombre' => $post_data['comentario_usuario_nombre'] ?? '',
        'comentario_usuario_apellido' => $post_data['comentario_usuario_apellido'] ?? '',
        'comentario_usuario_foto_perfil' => $post_data['comentario_usuario_foto_perfil'] ?? '',
      ];

      $posts[] = new Post(
        $post_data['post_id'],
        $post_data['usuario_id'],
        $post_data['descripcion'],
        $post_data['fecha_publicacion'],
        $post_data['usuario_nombre'],
        $post_data['usuario_apellido'],
        $post_data['usuario_foto_perfil'],
        $post_data['is_like'],
        $post_data['likes_count'],
        $post_data['comentarios_count'],
        [$comentarios],
        $imgs,
        $videos
      );
    }

    return $posts;
  }

  public function eliminarPost($post_id)
  {
    $conn = $this->db->getConnection();
    $query = "DELETE FROM t_posts WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    $query = "DELETE FROM t_imagenes WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    $query = "DELETE FROM t_videos WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  public function getPostsAleatorios($usuario_id, $limit = 10)
  {
    $conn = $this->db->getConnection();
    $query = "WITH comentario AS (SELECT c.post_id,
                           c.contenido                                                                 AS comentario,
                           u_comentario.usuario_id                                                     AS usuario_id_comentario,
                           u_comentario.nombre                                                         AS comentario_usuario_nombre,
                           u_comentario.apellido                                                       AS comentario_usuario_apellido,
                           u_comentario.foto_perfil                                                    AS comentario_usuario_foto_perfil,
                           ROW_NUMBER() OVER (PARTITION BY c.post_id ORDER BY c.fecha_comentario DESC) AS rn
                    FROM t_comentarios c
                             JOIN t_usuarios u_comentario ON c.usuario_id = u_comentario.usuario_id),
     like_status AS (SELECT publicacion_id, usuario_id
                     FROM t_likes
                     WHERE usuario_id = :usuario_id)
              SELECT p.post_id,
                    p.usuario_id,
                    p.descripcion,
                    p.fecha_publicacion,
                    u.nombre                AS usuario_nombre,
                    u.apellido              AS usuario_apellido,
                    u.foto_perfil           AS usuario_foto_perfil,
                    COUNT(l.publicacion_id) AS likes_count,
                    COUNT(c.post_id)        AS comentarios_count,
                    lc.comentario,
                    lc.usuario_id_comentario,
                    lc.comentario_usuario_nombre,
                    lc.comentario_usuario_apellido,
                    lc.comentario_usuario_foto_perfil,
                    CASE
                        WHEN ls.publicacion_id IS NOT NULL THEN true
                        ELSE false
                        END                 AS is_like
              FROM t_posts p
                      JOIN t_usuarios u ON p.usuario_id = u.usuario_id
                      LEFT JOIN t_likes l ON p.post_id = l.publicacion_id
                      LEFT JOIN t_comentarios c ON p.post_id = c.post_id
                      LEFT JOIN comentario lc ON p.post_id = lc.post_id AND lc.rn = 1 
                      LEFT JOIN like_status ls ON p.post_id = ls.publicacion_id 
              GROUP BY p.post_id, u.usuario_id, lc.comentario, lc.usuario_id_comentario, lc.comentario_usuario_nombre,
                      lc.comentario_usuario_apellido, lc.comentario_usuario_foto_perfil, ls.publicacion_id
              ORDER BY RANDOM() 
              LIMIT :limit; ";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $data_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $posts = [];

    foreach ($data_posts as $post_data) {
      $ImgRepository = new ImgRepository($this->db);
      $VideoRepository = new VideoRepository($this->db);

      $imgs = $ImgRepository->getImgsPorPostId($post_data['post_id']);
      $videos = $VideoRepository->getVideosPorPostId($post_data['post_id']);
      $comentarios = [
        'comentario' => $post_data['comentario'] ?? '',
        'comentario_usuario_nombre' => $post_data['comentario_usuario_nombre'] ?? '',
        'comentario_usuario_apellido' => $post_data['comentario_usuario_apellido'] ?? '',
        'comentario_usuario_foto_perfil' => $post_data['comentario_usuario_foto_perfil'] ?? '',
      ];

      $posts[] = new Post(
        $post_data['post_id'],
        $post_data['usuario_id'],
        $post_data['descripcion'],
        $post_data['fecha_publicacion'],
        $post_data['usuario_nombre'],
        $post_data['usuario_apellido'],
        $post_data['usuario_foto_perfil'],
        $post_data['is_like'],
        $post_data['likes_count'],
        $post_data['comentarios_count'],
        [$comentarios],
        $imgs,
        $videos
      );
    }

    return $posts;
  }
}
