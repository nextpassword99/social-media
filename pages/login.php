<?php
session_start();

include __DIR__ . '/../api/auth/functions.php';
define('LAYOUT_PATH', __DIR__ . '/../layouts/layout.php');
define('TEMPLATE_HTML_LOGIN', __DIR__ . '/../components/user-elements/login.html');

if (isset($_SESSION["id_usuario_login"]) && isset($_SESSION["token"])) {
  header('Location: /');
  exit;
}

$mensaje_error = str_replace('-', ' ', $error ?? ''); // `$error` Recuperar el error de la url

$titulo_pagina = "Inicio de Sesión - Social Media";

$contenido_login = crearContenidoLogin($mensaje_error);

platillaFinalSalida($contenido_login, $titulo_pagina);

function crearContenidoLogin($mensaje_error)
{
  $template_user = file_get_contents(TEMPLATE_HTML_LOGIN);
  $template_user = str_replace('{{mensaje_error}}', $mensaje_error ?? '', $template_user);
  return $template_user;
}

function platillaFinalSalida($template_user, $title_page)
{
  ob_start();
  include LAYOUT_PATH;
  $template = ob_get_clean();
  $template = str_replace('{{title}}', $title_page, $template);
  $template = str_replace('{{content}}', $template_user, $template);
  echo  $template;
}
