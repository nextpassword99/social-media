<?php
include_once __DIR__ . "/../db_config.php";


function comprobarCredencialesDeUsuario($email, $password)
{
    $conn = getConnection();
    $sql = "SELECT usuario_id FROM t_usuarios WHERE email = :email AND contraseña = :pass";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":pass", $password, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
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
