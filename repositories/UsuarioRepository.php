<?php
require_once __DIR__ . '/../models/Usuario.php';

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

  public function getUsuariosDesconocidos($limit = 10)
  {
    $conn = $this->db->getConnection();
    $sql = "SELECT u.usuario_id,
            u.nombre,
            u.apellido,
            u.email,
            u.descripcion,
            u.fecha_registro,
            u.foto_perfil,
            u.ubicacion,
            u.estado_civil,
            u.educacion

      FROM t_usuarios u
      WHERE NOT EXISTS (SELECT 1
                        FROM t_amigos a
                        WHERE u.usuario_id = a.usuario_id_1
                          OR u.usuario_id = a.usuario_id_2)
      ORDER BY u.usuario_id DESC
      LIMIT :limit";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $desconocidos_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $desconocidos = [];
    foreach ($desconocidos_data as $desconocido) {
      $desconocidos[] = new Usuario(
        $desconocido['usuario_id'],
        $desconocido['nombre'],
        $desconocido['apellido'],
        $desconocido['email'],
        $desconocido['foto_perfil'],
        $desconocido['descripcion'],
        $desconocido['ubicacion'],
        $desconocido['estado_civil'],
        $desconocido['fecha_registro'],
        $desconocido['educacion']
      );
    }

    return $desconocidos;
  }
}
