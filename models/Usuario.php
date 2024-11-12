<?php
class Usuario
{
  private $usuario_id;
  private $nombre;
  private $apellido;
  private $email;
  private $foto_perfil;
  private $db;

  public function __construct($usuario_id)
  {
    $this->usuario_id = $usuario_id;
    $this->db = new DB();
    $this->cargarDatos();
  }

  private function cargarDatos()
  {
    $conn = $this->db->getConnection();
    $query = "SELECT * FROM t_usuarios WHERE usuario_id = :usuario_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":usuario_id", $this->usuario_id);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->nombre = $data['nombre'];
    $this->apellido = $data['apellido'];
    $this->email = $data['email'];
    $this->foto_perfil = $data['foto_perfil'];
  }

  public function getNombre()
  {
    return $this->nombre;
  }

  public function getApellido()
  {
    return $this->apellido;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getFotoPerfil()
  {
    return $this->foto_perfil;
  }

  /**
   * Devuelve una lista aleatoria de usuarios.
   *
   * @param int $limit El número de usuarios a recuperar.
   *
   * @return array Los datos de los usuarios
   */
  public function getUsuariosAleatorios($limit = 10)
  {
    $conn = $this->db->getConnection();
    $sql = "SELECT * FROM t_usuarios ORDER BY RANDOM() LIMIT :limit";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
