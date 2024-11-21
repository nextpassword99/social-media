<?php

class LikeRepository
{
  private $db;

  public function __construct(DB $db)
  {
    $this->db = $db;
  }
  /**
   * Devuelve el número de likes de una publicación específica.
   *
   * @param int $post_id El ID de la publicación.
   *
   * @return int El número total de likes.
   */
  public function getCountLikesPorIdPost($post_id): int
  {
    $conn = $this->db->getConnection();
    $query = "SELECT COUNT(*) as count_likes FROM t_likes WHERE publicacion_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC)['count_likes'];
  }

}
