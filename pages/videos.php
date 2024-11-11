<?php
session_start();

if (!isset($_SESSION["usuario_id"]) || !isset($_SESSION["token"])) {
  header('Location: /login');
  exit;
}

define('LAYOUT_PATH', __DIR__ . '/../layouts/layout.php');
define('TEMPLATE_HTML_VIDEOS', __DIR__ . '/../components/templates/videos.html');
define('POST_HTML_PATH', __DIR__ . '/../components/publication/post.html');

include_once __DIR__ . "/../api/videos/videos.php";
include_once __DIR__ . '/../utils/functions.php';


$titulo_pagina = "Videos - Social Media";
$videos_aleatorios = obtenerVideosAleatorios();
$contenido_post_videos = contenidoPostsVideos($videos_aleatorios);

$contenido_videos = file_get_contents(TEMPLATE_HTML_VIDEOS);
$contenido_videos = str_replace("{{videos}}", $contenido_post_videos, $contenido_videos);

platillaFinalSalida($contenido_videos, $titulo_pagina);

function contenidoPostsVideos($videos)
{
  $template_post = file_get_contents(POST_HTML_PATH);

  $styles = extractStyles($template_post);
  $template_post = removeStyles($template_post);
  
  $contenido_videos = '';
  foreach ($videos as $video) {
    // $contenido_videos .= crearPostVideo($video, $template_post);
  }

  $contenido_videos .= $styles;

  return $contenido_videos;
}

function crearPostVideo($user, $post, $visual, $platilla)
{
  $post_video = $platilla;
  $post_video = str_replace("{{imagen_perfil}}", $user['foto_perfil'], $post_video);
  $post_video = str_replace("{{autor_post}}", $user['nombre'] . ' ' . $user['apellido'], $post_video);
  $post_video = str_replace("{{fecha_post}}", $post['fecha_publicacion'], $post_video);
  $post_video = str_replace("{{content_text}}", $post['contenido'], $post_video);
  $post_video = str_replace("{{numero_reacciones}}", $post['contenido'], $post_video);
  $html_video =  "<video class='visual' controls><source src=" . $visual['url_video'] . "></video>";
  $post_video = str_replace("{{content_visual}}", $html_video, $post_video);
  return $post_video;
}

function platillaFinalSalida($template_user, $title_page)
{
  ob_start();
  include LAYOUT_PATH;
  $template = ob_get_clean();
  $template = str_replace('{{title}}', $title_page, $template);
  $template = str_replace('{{content}}', $template_user, $template);
  echo $template;
}
