<?php
session_start();
include_once __DIR__ . '/functions.php';

function login($email, $password)
{
    $credenciales = comprobarCredencialesDeUsuario($email, $password);

    if (!$credenciales) {
        return false;
    }

    $usuario_id = $credenciales['usuario_id'];
    $token = $credenciales['token'];

    if (!$token) {
        $token = generarToken($usuario_id);
    }

    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['token'] = $token;

    return true;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);
    $email = $_POST["email"] ?? null;
    $password = $_POST["password"] ?? null;

    if (!isset($email) || !isset($password)) {
        header('Location: /login/datos-incompletos');
        exit;
    }


    if ($email && $password) {
        $result = login($email, $password);

        if (!$result) {
            header('Location: /login/no-existe-esta-cuenta');
            exit;
        }
        header('Location: /');
    }
}
