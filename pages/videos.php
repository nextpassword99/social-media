<?php
session_start();
include_once __DIR__ . "/../api/user/perfil_user.php";
include_once __DIR__ . "/../api/videos/videos.php";

define('LAYOUT_PATH', __DIR__ . '/../layouts/layout.php');
define('TEMPLATE_HTML_VIDEOS', __DIR__ . '/../components/templates/videos.html');
define('VIDEO_PLAYER', __DIR__ . '/../components/publication/video-player.html');
define('CONTENT_COMENTARIOS', __DIR__ . '/../components/publication/contenedor-comentario.html');

$datos_video = obtenerVideoPorIdPost($post_id);
if (!$datos_video) {
    header('Location: /404');
    exit;
}

$datos_post = obtenerPostPorId($post_id);
$datos_usuario_session = getUserById($_SESSION["usuario_id"]);
$datos_usuario_post = getUserById($datos_post["usuario_id"]);

$titulo_pagina = "Videos - Social Media";

$content_comentarios = crearContentPost($datos_usuario_session, $datos_usuario_post, $datos_post);
$content_video = crearContentVideo($datos_video);
$contenido_videos = crearContenidoVideos($content_video, $content_comentarios);

platillaFinalSalida($contenido_videos, $titulo_pagina);

function crearContentVideo($video)
{
    $video_player = file_get_contents(VIDEO_PLAYER);
    return str_replace("{{url_video}}", $video['url_video'], $video_player);
}

function crearContentPost($usuario_session, $usuario_post, $post)
{
    $nombre_completo = $usuario_post['nombre'] . ' ' . $usuario_post['apellido'];
    $nombre_completo_session = $usuario_session['nombre'] . ' ' . $usuario_session['apellido'];
    $contenido_comentarios = file_get_contents(CONTENT_COMENTARIOS);
    $contenido_comentarios = str_replace("{{autor_post}}", $nombre_completo, $contenido_comentarios);
    $contenido_comentarios = str_replace("{{imagen_perfil}}", $usuario_post['foto_perfil'], $contenido_comentarios);
    $contenido_comentarios = str_replace("{{fecha_post}}", $post['fecha_publicacion'], $contenido_comentarios);
    $contenido_comentarios = str_replace("{{content_description}}", $post['contenido'], $contenido_comentarios);
    $contenido_comentarios = str_replace("{{usuario_actual}}", $nombre_completo_session, $contenido_comentarios);

    return $contenido_comentarios;
}

function crearContenidoVideos($video, $comentarios)
{
    $template_user = file_get_contents(TEMPLATE_HTML_VIDEOS);
    $template_user = str_replace('{{content_video}}', $video, $template_user);
    return str_replace('{{content_comentarios}}', $comentarios, $template_user);
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
