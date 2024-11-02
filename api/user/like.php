<?php
include_once __DIR__ . '/../db_config.php';

/**
 * Agrega un like a una publicación.
 *
 * @param int $post_id El ID de la publicación a la que se le va a agregar like.
 * @param int $user_id El ID del usuario que está agregando el like.
 *
 * @return bool True si se agrego el like, false de lo contrario.
 */
function addLike($post_id, $user_id)
{
  $conn = getConnection();
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
function deleteLike($post_id, $user_id)
{
  $conn = getConnection();
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
function checkIfLikeExists($post_id, $user_id)
{
  $conn = getConnection();
  $sql = "SELECT COUNT(*) FROM t_likes WHERE publicacion_id = :post_id AND usuario_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':post_id', $post_id);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();

  return $stmt->fetchColumn() > 0;
}
