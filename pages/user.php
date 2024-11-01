<?php

$user_name = $id = 12 ? 'Edison Pontecil' : 'Alex Perez';


$title = "Usuario $user_name";
$create_post = file_get_contents(__DIR__ . '/../components/publication/input-post.html');


$create_post = str_replace('{{title}}', $title, $create_post);
$create_post = str_replace('{{imagen_perfil}}', 'https://storage.googleapis.com/a1aa/image/6SgPkjKVXc6PFNe9bv0qtvqeoM0UeiQoT6NBoqzHzI4ptexOB.jpg', $create_post);

$post_publicado = file_get_contents(__DIR__ . '/../components/publication/post.html');
$post_publicado = str_replace('{{imagen_perfil}}', 'https://storage.googleapis.com/a1aa/image/cua7Wu1SxFbgI91fSQUxxY5kpwoU3hOQyf56BdnScRZkmgsTA.jpg', $post_publicado);
$post_publicado = str_replace('{{autor_post}}', $user_name, $post_publicado);
$post_publicado = str_replace('{{fecha_post}}', '31 de octubre de 2024', $post_publicado);
$post_publicado = str_replace('{{content_text}}', 'Hola mundo. Este es mi primera publicación.', $post_publicado);
$post_publicado = str_replace('{{content_visual}}', 'https://storage.googleapis.com/a1aa/image/cua7Wu1SxFbgI91fSQUxxY5kpwoU3hOQyf56BdnScRZkmgsTA.jpg', $post_publicado);
$post_publicado = str_replace('{{usuario_actual}}', 'Alex Perez', $post_publicado);

$content_aside = file_get_contents(__DIR__ . '/../components/aside/details.html');
$content_aside = str_replace('{{lugar_vive}}', 'Perú, Cusco', $content_aside);
$content_aside = str_replace('{{lugar_origen}}', 'Cusco', $content_aside);

$content_header = file_get_contents(__DIR__ . '/../components/user-elements/header.html');
$content_header = str_replace('{{nombre_usuario}}', $user_name, $content_header);

$content_user = $create_post . $post_publicado;

$template_user = file_get_contents(__DIR__ . '/../components/templates/user.html');
$template_user = str_replace('{{content}}', $content_user, $template_user);
$template_user = str_replace('{{aside}}', $content_aside, $template_user);
$template_user = str_replace('{{perfil_user}}', $content_header, $template_user);


$all_content = $template_user;


ob_start();
include __DIR__ . '/../layouts/layout.php';
$template = ob_get_clean();


$template = str_replace('{{content}}', $all_content, $template);


echo $template;
