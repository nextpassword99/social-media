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

  /**
   * Agrega un like a una publicación.
   *
   * @param int $post_id El ID de la publicación a la que se le va a agregar like.
   * @param int $user_id El ID del usuario que está agregando el like.
   *
   * @return bool True si se agrego el like, false de lo contrario.
   */
  public function addLike($post_id, $user_id)
  {
    $conn = $this->db->getConnection();
    $sql = "INSERT INTO t_likes (publicacion_id, usuario_id) VALUES (:post_id, :user_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
      return $stmt->rowCount() > 0;
    };
    return false;
  }

  /**
   * Elimina un like de una publicación.
   *
   * @param int $post_id El ID de la publicación a la que se le va a eliminar el like.
   * @param int $user_id El ID del usuario que est  eliminando el like.
   *
   * @return bool True si se ha eliminado el like, false de lo contrario.
   */
  public function deleteLike($post_id, $user_id)
  {
    $conn = $this->db->getConnection();
    $sql = "DELETE FROM t_likes WHERE publicacion_id = :post_id AND usuario_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
      return $stmt->rowCount() > 0;
    };
    return false;
  }

  /**
   * Verifica si un usuario ha dado like a una publicación.
   *
   * @param int $post_id El ID de la publicación a la que se va a verificar el like.
   * @param int $user_id El ID del usuario que se va a verificar si ha dado like.
   *
   * @return bool True si el usuario ha dado like, false de lo contrario.
   */
  public function checkIfLikeExists($post_id, $user_id)
  {
    $conn = $this->db->getConnection();
    $sql = "SELECT COUNT(*) FROM t_likes WHERE publicacion_id = :post_id AND usuario_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
  }
}
