<?php
class Amigo
{
  private $usuario_id_1;
  private $usuario_id_2;
  private $db;

  public function __construct($usuario_id_1, $usuario_id_2)
  {
    $this->db = new DB();
    $this->usuario_id_1 = $usuario_id_1;
    $this->usuario_id_2 = $usuario_id_2;
  }

  public function enviarSolicitudAmistad()
  {
    $conn = $this->db->getConnection();
    $query = "INSERT INTO t_amigos (usuario_id_1, usuario_id_2) VALUES (:usuario_id_1, :usuario_id_2)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":usuario_id_1", $this->usuario_id_1);
    $stmt->bindParam(":usuario_id_2", $this->usuario_id_2);
    $stmt->execute();
  }

  public function aceptarSolicitudAmistad()
  {
    $conn = $this->db->getConnection();
    $query = "UPDATE t_amigos SET estado = 'aceptado' WHERE usuario_id_1 = :usuario_id_1 AND usuario_id_2 = :usuario_id_2";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":usuario_id_1", $this->usuario_id_1);
    $stmt->bindParam(":usuario_id_2", $this->usuario_id_2);
    $stmt->execute();
  }
  public static function getAmigosPorIdUsuario($user_id)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $query = "SELECT u.usuario_id, u.nombre, u.apellido, u.ubicacion, u.foto_perfil
              FROM t_amigos a
              JOIN t_usuarios u ON (u.usuario_id = a.usuario_id_2)
              WHERE a.usuario_id_1 = :user_id AND a.estado = 'aceptado'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
