<?php
include_once __DIR__ . "/../db_config.php";


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
