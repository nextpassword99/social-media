<?php
class UsuarioRepository
{
  private $db;
  public function __construct(DB $db)
  {
    $this->db = $db;
  }

  public function getDatosGeneralesUsuario($usuario_id)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT u.usuario_id,
                     u.nombre,
                     u.apellido,
                     u.email,
                     u.foto_perfil,
                     u.descripcion,
                     u.ubicacion,
                     u.estado_civil,
                     u.fecha_registro,
                     u.educacion
              FROM t_usuarios u 
              WHERE usuario_id = :usuario_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $Usuario = new Usuario(
      $data['usuario_id'],
      $data['nombre'],
      $data['apellido'],
      $data['email'],
      $data['foto_perfil'],
      $data['descripcion'],
      $data['ubicacion'],
      $data['estado_civil'],
      $data['fecha_registro'],
      $data['educacion']
    );

    return $Usuario;
  }

  public static function getUsuariosAleatorios($limit = 10)
  {
    $db = new DB();
    $conn = $db->getConnection();
    $sql = "SELECT * FROM t_usuarios ORDER BY RANDOM() LIMIT :limit";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
