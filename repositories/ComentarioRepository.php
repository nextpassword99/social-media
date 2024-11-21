<?php
class ComentarioRepository
{
  private $db;
  public function __construct(DB $db)
  {
    $this->db = $db;
  }
  public function getComentariosPorPostId($post_id)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT c.usuario_id, c.contenido, c.fecha_comentario, 
                     u.nombre          AS usuario_nombre, 
                     u.apellido        AS usuario_apellido, 
                     u.foto_perfil     AS usuario_foto_perfil
              FROM t_comentarios c
              JOIN t_usuarios u ON c.usuario_id = u.usuario_id
              WHERE c.post_id = :post_id
              ORDER BY c.fecha_comentario DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':limite', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
