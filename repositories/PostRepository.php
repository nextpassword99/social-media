<?php
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


}
