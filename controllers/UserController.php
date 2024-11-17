<?php
require __DIR__ . '/../models/Usuario.php';
require __DIR__ . '/../models/Post.php';
require __DIR__ . '/../utils/HtmlHelper.php';
require __DIR__ . '/../components/PostComponent.php';

class UserController
{
  private $usuario;
  private $data_usuario_session;

  public function __construct($usuario_id_session, $usuario_id)
  {
    $this->usuario = new Usuario($usuario_id);
    $this->cargarDatos($usuario_id_session);
  }

  public function cargarDatos($usuario_id_session)
  {
    $usuario = new Usuario($usuario_id_session);
    $this->data_usuario_session = [
      'usuario_id' => $usuario->getUsuarioId(),
      'nombre_completo' => $usuario->getNombre() . ' ' . $usuario->getApellido(),
      'foto_perfil' => $usuario->getFotoPerfil(),
    ];
  }

  public function render()
  {
    $template = file_get_contents(__DIR__ . '/../views/components/templates/user.html');

    $content = str_replace(
      [
        '{{perfil_user}}',
        '{{aside}}',
        '{{content}}'
      ],
      [
        $this->encabezadoUsuario(),
        $this->generarAside(),
        $this->postsUsuario(),
      ],
      $template
    );
    $recursos = $this->cargarRecursos();
    return $content . $recursos;
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
        $this->data_usuario_session['foto_perfil'],
        $post['post_id'],
        $post['post_id'],
        $this->usuario->getFotoPerfil(),
        $this->data_usuario_session['nombre_completo'],
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
    return str_replace(
      [
        '{{nombre_usuario}}',
        '{{imagen_perfil}}',
        '{{numero_amigos}}',
        '{{imagen_fondo}}',
        '{{usuario_id_session}}',
      ],
      [
        $this->usuario->getNombre() . ' ' . $this->usuario->getApellido(),
        $this->usuario->getFotoPerfil(),
        11 . ' amigos',
        $this->usuario->getFotoPerfil() ?? '',
        $this->data_usuario_session['usuario_id'],
      ],
      $header_plantilla
    );
  }

  function generarAside()
  {
    $detalles_usuario = $this->detallesUsuario();
    $fotos = $this->fotos();

    return $detalles_usuario . $fotos;
  }

  public function detallesUsuario()
  {
    $detalles_plantilla = file_get_contents(__DIR__ . '/../views/components/aside/details.html');

    $detalles_html = str_replace(
      [
        '{{descripcion}}',
        '{{lugar_vive}}',
        '{{estado_civil}}',
      ],
      [
        $this->usuario->getDescripcion(),
        $this->usuario->getUbicacion(),
        $this->usuario->getEstadoCivil(),
      ],
      $detalles_plantilla
    );

    return $detalles_html;
  }

  public function fotos()
  {
    $detalles_plantilla = file_get_contents(__DIR__ . '/../views/components/aside/fotos.html');

    $id_usuario = $this->usuario->getUsuarioId();
    $imgs = Usuario::getImgsPorIdUsuario($id_usuario);

    $content_imgs = '';
    foreach ($imgs as $img) {
      $content_imgs .= '<img src="' . htmlspecialchars($img['url_imagen']) . '"/>';
    }

    $html = str_replace(
      [
        '{{usuario_id}}',
        '{{fotos}}',
      ],
      [
        $id_usuario,
        $content_imgs,
      ],
      $detalles_plantilla
    );

    return $html;
  }

  public function cargarRecursos()
  {
    $scripts_comentarios = HtmlHelper::extractScripts(file_get_contents(__DIR__ . '/../views/components/publication/contenedor-comentario.html'));
    $styles_burbuja = HtmlHelper::extractStyles(file_get_contents(__DIR__ . '/../views/components/publication/burbuja-comentario.html'));
    return $scripts_comentarios . $styles_burbuja;
  }
}
