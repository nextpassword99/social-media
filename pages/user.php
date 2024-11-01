<?php
$response = [
  'user' => [
    'id' => 12,
    'name' => 'Edison Pontecil',
    'profile_image' => 'https://portal.andina.pe/EDPfotografia3/Thumbnail/2021/08/05/000796433W.jpg',
    'imagen_portada' => 'https://portal.andina.pe/EDPfotografia3/Thumbnail/2021/08/05/000796433W.jpg',
    'numero_amigos' => 25,
    'location' => 'Perú, Cusco',
    'origin' => 'Cusco'
  ],
  'amigos' => [
    [
      'image_perfil' => 'https://portal.andina.pe/EDPfotografia3/Thumbnail/2021/08/05/000796433W.jpg',
      'name' => 'Edison Pontecil',
      'location' => 'Perú, Cusco'
    ]
  ],
  'posts' => [
    [
      'image_perfil' => 'https://portal.andina.pe/EDPfotografia3/Thumbnail/2021/08/05/000796433W.jpg',
      'author' => 'Edison Pontecil',
      'date' => '31 de octubre de 2024',
      'content_text' => 'Hola mundo. Este es mi primera publicación.',
      'content_images' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/d/df/Family_Portrait.jpg/1200px-Family_Portrait.jpg',
      ],
      'comentarios' => [
        'numero_comentarios' => 8,
        'content_comentarios' => [
          [
            'imagen_perfil' => 'https://img.olympics.com/images/image/private/t_1-1_300/f_auto/v1687307644/primary/cqxzrctscdr8x47rly1g',
            'autor_comentario' => 'Alex Perez',
            'fecha_comentario' => '31 de octubre de 2024',
            'contenido_comentario' => 'Buen día.',
          ]
        ]
      ],
      'reacciones' => [
        'numero_reacciones' => 12,
        'usuarios_reacciones' => [
          'Edison Pontecil',
          'Edison Pontecil',
          'Edison Pontecil',
          'Edison Pontecil',
        ]
      ]
    ],
  ]
];



$user_name = $response['user']['name'];
$profile_image = $response['user']['profile_image'];
$title_page = "$user_name | Social Media";

$create_post = file_get_contents(__DIR__ . '/../components/publication/input-post.html');
$create_post = str_replace('{{title}}', "Usuario $user_name", $create_post);
$create_post = str_replace('{{imagen_perfil}}', $profile_image, $create_post);

$content_user = $create_post;
foreach ($response['posts'] as $post) {
  $post_publicado = file_get_contents(__DIR__ . '/../components/publication/post.html');
  $post_publicado = str_replace('{{imagen_perfil}}', $post['image_perfil'], $post_publicado);
  $post_publicado = str_replace('{{autor_post}}', $post['author'], $post_publicado);
  $post_publicado = str_replace('{{fecha_post}}', $post['date'], $post_publicado);
  $post_publicado = str_replace('{{content_text}}', $post['content_text'], $post_publicado);
  $post_publicado = str_replace('{{numero_reacciones}}', $post['reacciones']['numero_reacciones'], $post_publicado);
  $post_publicado = str_replace('{{numero_comentarios}}', $post['comentarios']['numero_comentarios'], $post_publicado);
  $post_publicado = str_replace('{{imagen_autor_comentario}}', $post['comentarios']['content_comentarios'][0]['imagen_perfil'], $post_publicado);
  $post_publicado = str_replace('{{usuario_comentario}}', $post['comentarios']['content_comentarios'][0]['autor_comentario'], $post_publicado);
  $post_publicado = str_replace('{{contenido_comentario}}', $post['comentarios']['content_comentarios'][0]['contenido_comentario'], $post_publicado);

  $content_visual = '';
  if (count($post['content_images']) > 0) {
    foreach ($post['content_images'] as $image_url) {
      $content_visual .= '<img alt="Post image" src="' . htmlspecialchars($image_url) . '" />';
    }
  }
  $post_publicado = str_replace('{{content_visual}}', $content_visual, $post_publicado);

  $content_user .= $post_publicado;
}

$content_aside = file_get_contents(__DIR__ . '/../components/aside/details.html');
$content_aside = str_replace('{{lugar_vive}}', $response['user']['location'], $content_aside);
$content_aside = str_replace('{{lugar_origen}}', $response['user']['origin'], $content_aside);

$content_header = file_get_contents(__DIR__ . '/../components/user-elements/header.html');
$content_header = str_replace('{{nombre_usuario}}', $user_name, $content_header);
$content_header = str_replace('{{imagen_perfil}}', $profile_image, $content_header);
$content_header = str_replace('{{numero_amigos}}', $response['user']['numero_amigos'], $content_header);
$content_header = str_replace('{{imagen_fondo}}', $response['user']['imagen_portada'], $content_header);

$template_user = file_get_contents(__DIR__ . '/../components/templates/user.html');
$template_user = str_replace('{{content}}', $content_user, $template_user);
$template_user = str_replace('{{aside}}', $content_aside, $template_user);
$template_user = str_replace('{{perfil_user}}', $content_header, $template_user);

ob_start();
include __DIR__ . '/../layouts/layout.php';
$template = ob_get_clean();
$template = str_replace('{{content}}', $template_user, $template);
echo $template;
