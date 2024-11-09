<?php
include_once __DIR__ . "/../db_config.php";


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
function comprobarCredencialesDeUsuario($email, $password)
{
    $conn = getConnection();
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
function generarToken($usuario_id)
{
    $token = bin2hex(random_bytes(32));

    $conn = getConnection();
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
function verificarToken($usuario_id, $token)
{
    $conn = getConnection();
    $sql = "SELECT token FROM t_usuarios WHERE usuario_id = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && $usuario['token'] === $token) {
        return true;
    } else {
        return false;
    }
}


function comprobarSiExisteEmail($email)
{
    $conn = getConnection();
    $sql = "SELECT usuario_id FROM t_usuarios WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result > 0;
}

function crearCuentaDeUsuario($nombre, $apellido, $email, $pass)
{
    $conn = getConnection();
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
 * Comprueba si se ha iniciado una sesión de usuario.
 * La sesión se considera iniciada si se ha establecido el id_usuario
 * y el token de sesión.
 *
 * @param int $id_usuario El id del usuario.
 * @param string $token El token de sesión.
 *
 * @return bool True si se ha iniciado una sesión, false en caso contrario.
 */
function comprobarInicioSession($id_usuario, $token)
{
    return (isset($id_usuario) && isset($token));
}
