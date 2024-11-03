<?php
include_once __DIR__ . '/../api/main/main.php';
include_once __DIR__ . '/../utils/functions.php';

$id = 1;
$response = buildResponseMain($id);

$user_name = $response['user']['name'];
$profile_image = $response['user']['profile_image'];
$title_page = "Social Media";

$create_post = file_get_contents(__DIR__ . '/../components/publication/input-post.html');
$create_post = str_replace('{{title}}', "Usuario $user_name", $create_post);
$create_post = str_replace('{{imagen_perfil}}', $profile_image, $create_post);

$content_user = $create_post;

$post_publicado = file_get_contents(__DIR__ . '/../components/publication/post.html');
$content_user .= extractStyles($post_publicado);
$template_post = removeStyles($post_publicado);
foreach ($response['posts'] as $post) {
  $post_publicado = $template_post;
  $post_publicado = str_replace('{{imagen_perfil}}', $post['image_perfil'], $post_publicado);
  $post_publicado = str_replace('{{autor_post}}', $post['author'], $post_publicado);
  $post_publicado = str_replace('{{fecha_post}}', $post['date'], $post_publicado);
  $post_publicado = str_replace('{{content_text}}', $post['content_text'], $post_publicado);
  $post_publicado = str_replace('{{numero_reacciones}}', $post['reacciones']['numero_reacciones'], $post_publicado);
  $post_publicado = str_replace('{{numero_comentarios}}', $post['comentarios']['numero_comentarios'], $post_publicado);
  $post_publicado = str_replace('{{post_id}}', $post['publicacion_id'], $post_publicado);
  $post_publicado = str_replace('{{usuario_actual}}', $user_name, $post_publicado);
  $post_publicado = str_replace('{{user_id}}', $id, $post_publicado);
  $post_publicado = str_replace('{{is_liked}}', $post['reacciones']['is_liked'] ? 'fas' : 'far', $post_publicado);

  $comentarios = $post['comentarios']['content_comentarios'] ?? [];
  $primer_comentario = $comentarios[0] ?? null;

  $post_publicado = str_replace('{{view_comment}}', $post['comentarios']['numero_comentarios'] > 0 ? 'active' : 'disabled', $post_publicado);
  $post_publicado = str_replace('{{imagen_autor_comentario}}', $primer_comentario['imagen_perfil'] ?? '', $post_publicado);
  $post_publicado = str_replace('{{usuario_comentario}}', $primer_comentario['autor_comentario'] ?? '', $post_publicado);
  $post_publicado = str_replace('{{contenido_comentario}}', $primer_comentario['contenido_comentario'] ?? '', $post_publicado);

  $content_visual = '';
  if (count($post['content_images']) > 0) {
    foreach ($post['content_images'] as $image_url) {
      $content_visual .= '<img alt="Post image" src="' . htmlspecialchars($image_url) . '" />';
    }
  }
  $post_publicado = str_replace('{{content_visual}}', $content_visual, $post_publicado);

  $content_user .= $post_publicado;
}

$aside_left = file_get_contents(__DIR__ . '/../components/aside/fast-menu.html');
$aside_left = str_replace('{{imagen_perfil}}', $profile_image, $aside_left);
$aside_left = str_replace('{{usuario_actual}}', $user_name, $aside_left);

$template_user = file_get_contents(__DIR__ . '/../components/templates/index.html');
$template_user = str_replace('{{content}}', $content_user, $template_user);
$template_user = str_replace('{{aside_left}}', $aside_left, $template_user);

ob_start();
include __DIR__ . '/../layouts/layout.php';
$template = ob_get_clean();
$template = str_replace('{{content}}', $template_user, $template);
echo $template;
