<?php
include_once __DIR__ . '/../api/db_config.php';
include_once __DIR__ . '/../api/friends/inicio.php';
include_once __DIR__ . '/../utils/functions.php';

define('LAYOUT_PATH', __DIR__ . '/../layouts/layout.php');
define('TEMPLATE_HTML_PATH', __DIR__ . '/../components/templates/amigos.html');
define('CARD_NO_AMIGOS', __DIR__ . '/../components/amigos/no-friend-card.html');

$title_page = "Amigos";

$content_user = crearContenidoNoAmigos();
$template_user = plantillaPrincipal($content_user);
plantillaSalidaFinal($template_user, $title_page);

function crearContenidoNoAmigos()
{

  $plantilla_no_amigos = file_get_contents(CARD_NO_AMIGOS);
  $usuarios_aleatorios = obtenerUsuariosAleatorios();

  $content_no_amigos = extractStyles($plantilla_no_amigos);
  $plantilla_no_amigos = removeStyles($plantilla_no_amigos);

  foreach ($usuarios_aleatorios as $usuario) {
    $content_no_amigos .= crearTarjetaNoAmigos($plantilla_no_amigos, $usuario['nombre'] . ' ' . $usuario['apellido'], $usuario['foto_perfil'], 'Alfred');
  }

  return $content_no_amigos;
}


function crearTarjetaNoAmigos($plantilla_no_amigos, $nombre, $img_perfil, $amigos_relacionados)
{
  $tarjeta_no_amigos = $plantilla_no_amigos;
  $tarjeta_no_amigos = str_replace('{{nombre_no_amigo}}', $nombre, $tarjeta_no_amigos);
  $tarjeta_no_amigos = str_replace('{{imagen_perfil}}', $img_perfil, $tarjeta_no_amigos);
  $tarjeta_no_amigos = str_replace('{{amigos_relacionados}}', $amigos_relacionados, $tarjeta_no_amigos);

  return $tarjeta_no_amigos;
}

function plantillaPrincipal($content_user)
{
  $template_user = file_get_contents(TEMPLATE_HTML_PATH);
  $template_user = str_replace('{{content_no_amigos}}', $content_user, $template_user);

  return $template_user;
}

function plantillaSalidaFinal($template_user, $title_page)
{
  ob_start();
  include LAYOUT_PATH;
  $template = ob_get_clean();
  $template = str_replace('{{title}}', $title_page, $template);
  $template = str_replace('{{content}}', $template_user, $template);
  echo  $template;
}
