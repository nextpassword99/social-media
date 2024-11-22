<?php
class VideoRepository
{
  private $db;

  public function __construct(DB $db)
  {
    $this->db = $db;
  }


  public function agregarVideoPost($post_id, $video_url)
  {
    $conn = $this->db->getConnection();
    $query = "INSERT INTO t_videos (post_id, url_video) VALUES (:post_id, :url_video)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':url_video', $video_url, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }


  public function getVideosPorPostId($post_id)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT url_video
              FROM t_videos
              WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getVideosAleatorios($limit = 10)
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
                    u.nombre                         AS usuario_nombre,
                    u.apellido                       AS usuario_apellido,
                    u.foto_perfil                    AS usuario_foto_perfil,
                    COUNT(DISTINCT l.publicacion_id) AS likes_count,
                    COUNT(DISTINCT c.post_id)        AS comentarios_count,
                    lc.comentario,
                    lc.usuario_id_comentario,
                    lc.comentario_usuario_nombre,
                    lc.comentario_usuario_apellido,
                    lc.comentario_usuario_foto_perfil,
                    CASE
                        WHEN ls.publicacion_id IS NOT NULL THEN true
                        ELSE false
                        END                          AS is_like
              FROM t_posts p
                      JOIN t_usuarios u ON p.usuario_id = u.usuario_id
                      LEFT JOIN t_likes l ON p.post_id = l.publicacion_id
                      LEFT JOIN t_comentarios c ON p.post_id = c.post_id
                      LEFT JOIN comentario lc ON p.post_id = lc.post_id AND lc.rn = 1
                      LEFT JOIN like_status ls ON p.post_id = ls.publicacion_id
                      INNER JOIN t_videos v ON p.post_id = v.post_id
              GROUP BY p.post_id, u.usuario_id, lc.comentario, lc.usuario_id_comentario, lc.comentario_usuario_nombre,
                      lc.comentario_usuario_apellido, lc.comentario_usuario_foto_perfil, ls.publicacion_id
              ORDER BY p.fecha_publicacion DESC";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':limite', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
