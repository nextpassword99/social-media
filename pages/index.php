<?php
include_once __DIR__ . '/../api/main/main.php';
include_once __DIR__ . '/../utils/functions.php';

define('POST_HTML_PATH', __DIR__ . '/../components/publication/post.html');
define('INPUT_POST_HTML_PATH', __DIR__ . '/../components/publication/input-post.html');
define('ASIDE_LEFT_HTML_PATH', __DIR__ . '/../components/aside/fast-menu.html');
define('ASIDE_RIGHT_HTML_PATH', __DIR__ . '/../components/aside/amigos.html');
define('TEMPLATE_HTML_PATH', __DIR__ . '/../components/templates/index.html');
define('LAYOUT_PATH', __DIR__ . '/../layouts/layout.php');


$id = 1;
$response = buildResponseMain($id);

$user_name = $response['user']['name'];
$profile_image = $response['user']['profile_image'];
$title_page = "Social Media";

$content_user = generateUserContent($response, $user_name, $profile_image);
$aside_left = generateAsideMenu($profile_image, $user_name);
$aside_right = generateFriendsPanel($response['amigos']);
$template_user = generateMainTemplate($content_user, $aside_left, $aside_right);
outputFinalLayout($template_user, $title_page);

function generateUserContent($response, $user_name, $profile_image)
{
  $create_post = file_get_contents(INPUT_POST_HTML_PATH);
  $create_post = str_replace('{{title}}', "Usuario $user_name", $create_post);
  $create_post = str_replace('{{imagen_perfil}}', $profile_image, $create_post);

  $content_user = $create_post;
  $content_user .= extractStyles(file_get_contents(POST_HTML_PATH));
  $post_template = removeStyles(file_get_contents(POST_HTML_PATH));

  foreach ($response['posts'] as $post) {
    $content_user .= buildPostHtml($post, $post_template, $user_name);
  }

  return $content_user;
}

function buildPostHtml($post, $post_template, $user_name)
{
  $post_html = $post_template;
  $post_html = str_replace('{{imagen_perfil}}', $post['image_perfil'], $post_html);
  $post_html = str_replace('{{autor_post}}', $post['author'], $post_html);
  $post_html = str_replace('{{fecha_post}}', $post['date'], $post_html);
  $post_html = str_replace('{{content_text}}', $post['content_text'], $post_html);
  $post_html = str_replace('{{numero_reacciones}}', $post['reacciones']['numero_reacciones'], $post_html);
  $post_html = str_replace('{{numero_comentarios}}', $post['comentarios']['numero_comentarios'], $post_html);
  $post_html = str_replace('{{post_id}}', $post['publicacion_id'], $post_html);
  $post_html = str_replace('{{usuario_actual}}', $user_name, $post_html);
  $post_html = str_replace('{{user_id}}', 1, $post_html);
  $post_html = str_replace('{{is_liked}}', $post['reacciones']['is_liked'] ? 'fas' : 'far', $post_html);

  $comentarios = $post['comentarios']['content_comentarios'] ?? [];
  $primer_comentario = $comentarios[0] ?? null;

  $post_html = str_replace('{{view_comment}}', $post['comentarios']['numero_comentarios'] > 0 ? 'active' : 'disabled', $post_html);
  $post_html = str_replace('{{imagen_autor_comentario}}', $primer_comentario['imagen_perfil'] ?? '', $post_html);
  $post_html = str_replace('{{usuario_comentario}}', $primer_comentario['autor_comentario'] ?? '', $post_html);
  $post_html = str_replace('{{contenido_comentario}}', $primer_comentario['contenido_comentario'] ?? '', $post_html);

  $content_visual = '';
  foreach ($post['content_images'] as $image_url) {
    $content_visual .= '<img alt="Post image" src="' . htmlspecialchars($image_url) . '" />';
  }
  $post_html = str_replace('{{content_visual}}', $content_visual, $post_html);

  return $post_html;
}

function generateAsideMenu($profile_image, $user_name)
{
  $aside_left = file_get_contents(ASIDE_LEFT_HTML_PATH);
  $aside_left = str_replace('{{imagen_perfil}}', $profile_image, $aside_left);
  $aside_left = str_replace('{{usuario_actual}}', $user_name, $aside_left);

  return $aside_left;
}

function generateFriendsPanel($friends)
{
  $aside_right = file_get_contents(ASIDE_RIGHT_HTML_PATH);
  $card_amigo = file_get_contents(__DIR__ . '/../components/aside/amigo-card.html');

  $card_amigo_styles = extractStyles($card_amigo);
  $card_amigo = removeStyles($card_amigo);

  $content_amigos = '';
  foreach ($friends as $friend) {
    $card_amigo = str_replace('{{imagen_perfil}}', $friend['profile_image'], $card_amigo);
    $card_amigo = str_replace('{{nombre_amigo}}', $friend['name'], $card_amigo);
    $content_amigos .= $card_amigo;
  }

  $aside_right = str_replace('{{content_amigos}}', $content_amigos, $aside_right);
  $aside_right .= $card_amigo_styles;

  return $aside_right;
}

function generateMainTemplate($content_user, $aside_left, $aside_right)
{
  $template_user = file_get_contents(TEMPLATE_HTML_PATH);
  $template_user = str_replace('{{content}}', $content_user, $template_user);
  $template_user = str_replace('{{aside_left}}', $aside_left, $template_user);
  $template_user = str_replace('{{aside_right}}', $aside_right, $template_user);

  return $template_user;
}

function outputFinalLayout($template_user, $title_page)
{
  ob_start();
  include LAYOUT_PATH;
  $template = ob_get_clean();
  $template = str_replace('{{title}}', $title_page, $template);
  $template = str_replace('{{content}}', $template_user, $template);
  echo  $template;
}
