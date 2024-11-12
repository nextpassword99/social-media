<?php
require_once __DIR__ . '/DB.php';
class Post
{
  private $post_id;
  private $usuario_id;
  private $descripcion;
  private $fecha_publicacion;
  private $db;

  public function __construct($post_id)
  {
    $this->post_id = $post_id;
    $this->db = new DB();
  }

  private function cargarPost()
  {
    $conn = $this->db->getConnection();
    $query = "SELECT * FROM t_posts WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":post_id", $this->post_id);
    $stmt->execute();
  }
  public function getDescripcion()
  {
    return $this->descripcion;
  }

  public function getFechaPublicacion()
  {
    return $this->fecha_publicacion;
  }

  public function getPostsPorIdUsuario($user_id)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT u.usuario_id, p.post_id, p.descripcion, p.fecha_publicacion, u.foto_perfil, u.nombre, u.apellido
              FROM t_posts p
                      JOIN t_usuarios u ON u.usuario_id = p.usuario_id
              WHERE p.usuario_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getComentariosPorIdPost($post_id)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT c.usuario_id, c.contenido, c.fecha_comentario, u.foto_perfil, u.nombre, u.apellido
            FROM t_comentarios c
              JOIN t_usuarios u ON u.usuario_id = c.usuario_id
            WHERE c.post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getImgsPorIdPost($post_id)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT url_imagen FROM t_imagenes WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getPostsAleatorios($limit = 10)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT p.post_id, p.descripcion, p.fecha_publicacion, u.foto_perfil, u.nombre, u.apellido
              FROM t_publicaciones p
                JOIN t_usuarios u ON u.usuario_id = p.usuario_id
              ORDER BY RANDOM() 
              LIMIT :limit";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
