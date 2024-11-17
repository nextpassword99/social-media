<?php
session_start();
require_once __DIR__ . '/../../models/Auth.php';

if (Auth::validarSession()) {
  header('Location: /');
  exit;
}

$html_login = file_get_contents(__DIR__ . '/../components/user-elements/login.html');
$html_login = str_replace(
  [
    '{{mensaje_error}}',
  ],
  [
    $error ?? '',
  ],
  $html_login
);

echo $html_login;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once __DIR__ . '/../../models/Auth.php';
  function login($email, $password)
  {
    $auth = new Auth();
    $credenciales = $auth->comprobarCredencialesDeUsuario($email, $password);

    if (!$credenciales) {
      return false;
    }

    $usuario_id = $credenciales['usuario_id'];
    $token = $credenciales['token'];

    if (!$token) {
      $token = $auth->generarToken($usuario_id);
    }

    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['token'] = $token;

    return true;
  }


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
