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
    $this->cargarPost();
  }

  private function cargarPost()
  {
    $conn = $this->db->getConnection();
    $query = "SELECT * FROM t_posts WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":post_id", $this->post_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->fecha_publicacion = $result['fecha_publicacion'];
    $this->usuario_id = $result['usuario_id'];
    $this->descripcion = $result['descripcion'];
  }
  public function getUsuarioId()
  {
    return $this->usuario_id;
  }
  public function getDescripcion()
  {
    return $this->descripcion;
  }

  public function getFechaPublicacion()
  {
    return $this->fecha_publicacion;
  }

  public static function getPostsPorIdUsuario($user_id)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "SELECT u.usuario_id, p.post_id, p.descripcion, p.fecha_publicacion, u.foto_perfil, u.nombre, u.apellido
              FROM t_posts p
                      JOIN t_usuarios u ON u.usuario_id = p.usuario_id
              WHERE p.usuario_id = :user_id
              ORDER BY p.post_id DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getComentariosPorIdPost($post_id)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "SELECT c.usuario_id, c.contenido, c.fecha_comentario, u.foto_perfil, u.nombre, u.apellido
            FROM t_comentarios c
              JOIN t_usuarios u ON u.usuario_id = c.usuario_id
            WHERE c.post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getCountComentariosPorIdPost($post_id)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "SELECT COUNT(post_id) as count FROM t_comentarios WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
  }

  public static function getUnComentarioPorIdPost($post_id)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "SELECT * FROM t_comentarios WHERE post_id = :post_id ORDER BY fecha_comentario DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function setComentario($post_id, $usuario_id, $comentario)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "INSERT INTO t_comentarios (post_id, usuario_id, contenido) VALUES (:post_id, :usuario_id, :contenido)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':contenido', $comentario, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  public static function getImgsPorIdPost($post_id)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "SELECT * FROM t_imagenes WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Devuelve los videos de una publicación dada.
   *
   * @param int $post_id El ID de la publicación.
   *
   * @return array Los videos de la publicación.
   */
  public static function getVideosPorIdPost($post_id)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "SELECT * FROM t_videos WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getPostsAleatorios($limit = 10)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "SELECT p.usuario_id, p.post_id, p.descripcion, p.fecha_publicacion, u.foto_perfil, u.nombre, u.apellido
              FROM t_posts p
                JOIN t_usuarios u ON u.usuario_id = p.usuario_id
              ORDER BY RANDOM() 
              LIMIT :limit";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getVideosAleatorios($limit = 10)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = 'SELECT * FROM t_videos ORDER BY RANDOM() LIMIT :limite';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':limite', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Devuelve el número de likes de una publicación específica.
   *
   * @param int $post_id El ID de la publicación.
   *
   * @return int El número total de likes.
   */
  public static function getCountLikesPorIdPost($post_id): int
  {
    $db = new DB();
    $conn = $db->getConnection();
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
  public static function addLike($post_id, $user_id)
  {
    $db = new DB();
    $conn = $db->getConnection();
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
  public static function deleteLike($post_id, $user_id)
  {
    $db = new DB();
    $conn = $db->getConnection();
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
  public static function checkIfLikeExists($post_id, $user_id)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $sql = "SELECT COUNT(*) FROM t_likes WHERE publicacion_id = :post_id AND usuario_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
  }

  public static function setPost($usuario_id, $post_text)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "INSERT INTO t_posts (usuario_id, descripcion) VALUES (:usuario_id, :descripcion)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':descripcion', $post_text, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $conn->lastInsertId();
    };

    return false;
  }

  public static function setImgPost($post_id, $img_url)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "INSERT INTO t_imagenes (post_id, url_imagen) VALUES (:post_id, :url_imagen)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':url_imagen', $img_url, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  public static function setVideoPost($post_id, $video_url)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "INSERT INTO t_videos (post_id, url_video) VALUES (:post_id, :url_video)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':url_video', $video_url, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }
}
