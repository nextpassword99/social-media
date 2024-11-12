<?php
require __DIR__ . '/../models/Usuario.php';
require __DIR__ . '/../models/Post.php';
require __DIR__ . '/../utils/HtmlHelper.php';
require __DIR__ . '/../components/PostComponent.php';

class UserController
{
  private $usuario;

  public function __construct($usuario_id)
  {
    $this->usuario = new Usuario($usuario_id);
  }

  public function render()
  {
    $template = file_get_contents(__DIR__ . '/../views/components/templates/user.html');

    return str_replace(
      [
        '{{perfil_user}}',
        '{{aside}}',
        '{{content}}'
      ],
      [
        $this->encabezadoUsuario(),
        $this->detallesUsuario(),
        $this->postsUsuario(),
      ],
      $template
    );
  }

  public function postsUsuario()
  {
    // $usuario = new Usuario($usuario_id);
    $postObject = new Post($this->usuario->getUsuarioId());
    $posts_data = $postObject->getPostsPorIdUsuario($this->usuario->getUsuarioId());

    $post_platilla = file_get_contents(__DIR__ . '/../views/components/publication/post.html');
    $post_plantilla_estilos = HtmlHelper::extractStyles($post_platilla);
    $post_platilla_sin_estilos = HtmlHelper::removeStyles($post_platilla);

    $post_html = "";
    foreach ($posts_data as $post) {
      $post_component = new PostComponent(
        $post['usuario_id'],
        $post['post_id'],
        $this->usuario->getFotoPerfil(),
        "Edison",
        $post["nombre"] . " " . $post["apellido"],
        $post["fecha_publicacion"],
        $post["descripcion"],
        $post_platilla_sin_estilos,
      );
      $post_html .= $post_component->render();
    }

    $post_html .= $post_plantilla_estilos;

    return $post_html;
  }

  public function encabezadoUsuario()
  {
    $header_plantilla = file_get_contents(__DIR__ . '/../views/components/user-elements/header.html');
    $header_plantilla = str_replace('{{nombre_usuario}}', $this->usuario->getNombre(), $header_plantilla);
    $header_plantilla = str_replace('{{imagen_perfil}}', $this->usuario->getFotoPerfil(), $header_plantilla);
    $header_plantilla = str_replace('{{numero_amigos}}', '12', $header_plantilla);
    $header_plantilla = str_replace('{{imagen_fondo}}', $this->usuario->getFotoPerfil(), $header_plantilla);

    return $header_plantilla;
  }

  public function detallesUsuario()
  {
    $detalles_plantilla = file_get_contents(__DIR__ . '/../views/components/aside/details.html');

    $detalles_html = str_replace(
      [
        '{{descripcion}}',
        '{{lugar_vive}}',
        '{{lugar_origen}}',
      ],
      [
        $this->usuario->getNombre(),
        $this->usuario->getNombre(),
        $this->usuario->getNombre(),
      ],
      $detalles_plantilla
    );

    return $detalles_html;
  }
}
