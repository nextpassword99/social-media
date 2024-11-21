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

  public function crearPost($usuario_id, $post_text)
  {
    $conn = $this->db->getConnection();
    $query = "INSERT INTO t_posts (usuario_id, descripcion) VALUES (:usuario_id, :descripcion)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':descripcion', $post_text, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $conn->lastInsertId();
    }

    return false;
  }


  public function getPostsPorUsuarioId($usuario_id)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT p.post_id,
                    p.usuario_id,
                    p.descripcion,
                    p.fecha_publicacion,
                    u.nombre                AS usuario_nombre,
                    u.apellido              AS usuario_apellido,
                    u.foto_perfil           AS usuario_foto_perfil,
                    COUNT(l.publicacion_id) AS likes_count,
                    COUNT(c.post_id)        AS comentarios_count,
                    -- Sub consulta último comentario
                    (SELECT c.contenido
                      FROM t_comentarios c
                      WHERE c.post_id = p.post_id
                      ORDER BY c.fecha_comentario DESC
                      LIMIT 1)               AS comentario,
                    (SELECT u_comentario.usuario_id
                      FROM t_comentarios c
                              JOIN t_usuarios u_comentario ON c.usuario_id = u_comentario.usuario_id
                      WHERE c.post_id = p.post_id
                      ORDER BY c.fecha_comentario DESC
                      LIMIT 1)               AS usuario_id_comentario,
                    (SELECT u_comentario.nombre
                      FROM t_comentarios c
                              JOIN t_usuarios u_comentario ON c.usuario_id = u_comentario.usuario_id
                      WHERE c.post_id = p.post_id
                      ORDER BY c.fecha_comentario DESC
                      LIMIT 1)               AS comentario_usuario_nombre,
                    (SELECT u_comentario.apellido
                      FROM t_comentarios c
                              JOIN t_usuarios u_comentario ON c.usuario_id = u_comentario.usuario_id
                      WHERE c.post_id = p.post_id
                      ORDER BY c.fecha_comentario DESC
                      LIMIT 1)               AS comentario_usuario_apellido,
                    (SELECT u_comentario.foto_perfil
                      FROM t_comentarios c
                              JOIN t_usuarios u_comentario ON c.usuario_id = u_comentario.usuario_id
                      WHERE c.post_id = p.post_id
                      ORDER BY c.fecha_comentario DESC
                      LIMIT 1)               AS comentario_usuario_foto_perfil
              FROM t_posts p
                      JOIN t_usuarios u ON p.usuario_id = u.usuario_id
                      LEFT JOIN t_likes l ON p.post_id = l.publicacion_id
                      LEFT JOIN t_comentarios c ON p.post_id = c.post_id
              WHERE p.usuario_id = :user_id
              GROUP BY p.post_id, u.usuario_id
              ORDER BY p.fecha_publicacion DESC
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

  public function getPostsAleatorios($limit = 10)
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
                              JOIN t_usuarios u_comentario ON c.usuario_id = u_comentario.usuario_id)
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
                    lc.comentario_usuario_foto_perfil
              FROM t_posts p
                      JOIN t_usuarios u ON p.usuario_id = u.usuario_id
                      LEFT JOIN t_likes l ON p.post_id = l.publicacion_id
                      LEFT JOIN t_comentarios c ON p.post_id = c.post_id
                      LEFT JOIN comentario lc ON p.post_id = lc.post_id AND lc.rn = 1
              GROUP BY p.post_id, u.usuario_id, lc.comentario, lc.usuario_id_comentario, lc.comentario_usuario_nombre,
                      lc.comentario_usuario_apellido, lc.comentario_usuario_foto_perfil
              ORDER BY RANDOM() 
              LIMIT :limit;";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
