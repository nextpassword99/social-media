<?php
class ImgRepository
{
  private $db;

  public function __construct(DB $db)
  {
    $this->db = $db;
  }

  public function setImgPost($post_id, $img_url)
  {
    $conn = $this->db->getConnection();
    $query = "INSERT INTO t_imagenes (post_id, url_imagen) VALUES (:post_id, :url_imagen)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':url_imagen', $img_url, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  public function getImgsPorPostId($post_id)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT url_imagen 
              FROM t_imagenes 
              WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getImgsPorIdUsuario($usuario_id)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT i.imagen_id,
                     i.post_id,
                     i.url_imagen,
                     i.fecha_subida
              FROM t_imagenes i
                     JOIN t_posts p ON i.post_id = p.post_id
              WHERE p.usuario_id = :usuario_id
              ORDER BY i.fecha_subida DESC
              LIMIT 9";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
