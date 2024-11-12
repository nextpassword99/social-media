<?php
class Auth
{
  private $db;

  public function __construct()
  {
    $this->db = new DB();
  }

  /**
   * Comprueba las credenciales de un usuario y devuelve un array con el id del
   * usuario y su token de inicio de sesión si es v lido, o false de lo contrario.
   *
   * @param string $email El correo electrónico del usuario a verificar.
   * @param string $password La contraseña del usuario a verificar.
   *
   * @return array|false Un array con el id del usuario y su token de inicio de
   *         sesión si es válido, o false de lo contrario.
   */
  public function comprobarCredencialesDeUsuario($email, $password)
  {
    $conn = $this->db->getConnection();
    $sql = "SELECT usuario_id, token, contraseña FROM t_usuarios WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['contraseña'])) {
      return [
        'usuario_id' => $usuario['usuario_id'],
        'token' => $usuario['token']
      ];
    } else {
      return false;
    }
  }

  /**
   * Genera un token aleatorio para el usuario con el ID dado.
   *
   * @param int $usuario_id El ID del usuario al que se le va a generar el token.
   *
   * @return string El token generado.
   */
  public function generarToken($usuario_id)
  {
    $token = bin2hex(random_bytes(32));

    $conn = $this->db->getConnection();
    $sql = "UPDATE t_usuarios SET token = :token WHERE usuario_id = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":token", $token, PDO::PARAM_STR);
    $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

    return $token;
  }

  /**
   * Verifica que el token sea válido para el usuario con el ID dado.
   *
   * @param int    $usuario_id El ID del usuario al que se le va a verificar el token.
   * @param string $token      El token a verificar.
   *
   * @return bool True si el token es válido, false de lo contrario.
   */
  public function verificarToken($usuario_id, $token)
  {
    $conn = $this->db->getConnection();
    $sql = "SELECT token FROM t_usuarios WHERE usuario_id = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    return $usuario && $usuario['token'] === $token;
  }

  /**
   * Verifica si existe un usuario con el email dado.
   *
   * @param string $email El email a verificar.
   *
   * @return bool True si existe un usuario con ese email, false de lo contrario.
   */
  public function comprobarSiExisteEmail($email)
  {
    $conn = $this->db->getConnection();
    $sql = "SELECT usuario_id FROM t_usuarios WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result > 0;
  }

  /**
   * Crea una cuenta de usuario con los datos dados.
   *
   * @param string $nombre El nombre del usuario.
   * @param string $apellido El apellido del usuario.
   * @param string $email El email del usuario.
   * @param string $pass La contraseña del usuario.
   *
   * @return int El ID del usuario creado o 0 si no se pudo crear.
   */
  public function crearCuentaDeUsuario($nombre, $apellido, $email, $pass)
  {
    $conn = $this->db->getConnection();
    $sql = "INSERT INTO t_usuarios (nombre, apellido, email, contraseña) VALUES (:nombre, :apellido, :email, :pass)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
    $stmt->bindParam(":apellido", $apellido, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":pass", $pass, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result > 0) {
      return $result["id_usuario"];
    } else {
      return 0;
    }
  }

  /**
   * Verifica si la sesión actual es válida.
   *
   * Verifica que se hayan establecido las variables de sesión 'usuario_id' y
   * 'token'. Si se han establecido, devuelve true, de lo contrario devuelve
   * false.
   *
   * @return bool True si la sesión es válida, false de lo contrario.
   */
  public function validarSession()
  {
    return isset($_SESSION['usuario_id']) && isset($_SESSION['token']);
  }
}
