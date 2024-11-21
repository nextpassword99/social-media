<?php
require __DIR__ . '/../models/Usuario.php';
require __DIR__ . '/../repositories/PostRepository.php';
require __DIR__ . '/../services/PostService.php';
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

  public function renderHeader(): string
  {
    return $this->encabezadoUsuario();
  }

  public function renderInfoUsuario(): string
  {
    return $this->generarInfoUsuario();
  }

  public function renderAside(): string
  {
    return $this->generarAside();
  }

  public function renderPosts(): string
  {
    return $this->postsUsuario();
  }

  public function renderCrearPost(): string
  {
    return $this->generarCrearPost();
  }

  public function renderCompletePage($elementos = []): string
  {

    $template = file_get_contents(__DIR__ . '/../views/components/templates/user.html');


    $content = str_replace(
      [
        '{{perfil_user}}',
        '{{aside}}',
        '{{content}}'
      ],
      $elementos,
      $template
    );


    $recursos = $this->cargarRecursos();

    return $content . $recursos;
  }

  private function postsUsuario(): string
  {
    $db = new DB();
    $postRepository = new PostRepository($db);
    $postService = new PostService($postRepository);
    $posts_data = $postService->getPostsPorUsuarioId($this->usuario->getUsuarioId());

    $post_platilla = file_get_contents(__DIR__ . '/../views/components/publication/post.html');
    $post_plantilla_estilos = HtmlHelper::extractStyles($post_platilla);
    $post_platilla_sin_estilos = HtmlHelper::removeStyles($post_platilla);

    $post_html = "";
    foreach ($posts_data as $post) {
      $post_component = new PostComponent(
        $this->UsuarioSession->getUsuarioId(),
        $this->UsuarioSession->getFotoPerfil(),
        $post->getUsuarioId(),
        $post->getPostId(),
        $this->UsuarioView->getFotoPerfil(),
        $this->UsuarioSession->getNombre(),
        $post->getUsuarioNombreCompleto(),
        $post->getFechaPublicacion(),
        $post->getDescripcion(),
        $post->getLikesCount(),
        $post->getComentariosCount(),
        $post->getComentarios(),
        $post->getImgs(),
        $post->getVideos(),
        $post_platilla_sin_estilos,
      );
      $post_html .= $post_component->render();
    }

    $post_html .= $post_plantilla_estilos;

    return $post_html;
  }

  private function encabezadoUsuario(): string
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

  private function generarAside(): string
  {
    $detalles_usuario = $this->detallesUsuario();
    $fotos = $this->fotos();

    return $detalles_usuario . $fotos;
  }

  private function detallesUsuario(): string
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

  private function fotos(): string
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

  private function generarInfoUsuario(): string
  {
    $template = file_get_contents(__DIR__ . '/../views/components/user/detalles-expandido.html');
    $template = str_replace([
      '{{nombre}}',
      '{{apellido}}',
      '{{email}}',
      '{{descripcion}}',
      '{{fecha_registro}}',
      '{{ubicacion}}',
      '{{estado_civil}}',
      '{{educacion}}',
    ], [
      $this->usuario->getNombre(),
      $this->usuario->getApellido(),
      $this->usuario->getEmail(),
      $this->usuario->getDescripcion(),
      $this->usuario->getFecha_registro(),
      $this->usuario->getUbicacion(),
      $this->usuario->getEstadoCivil(),
      $this->usuario->getEducacion(),
    ], $template);
    return $template;
  }

  private function cargarRecursos(): string
  {
    $scripts_comentarios = HtmlHelper::extractScripts(file_get_contents(__DIR__ . '/../views/components/publication/contenedor-comentario.html'));
    $styles_burbuja = HtmlHelper::extractStyles(file_get_contents(__DIR__ . '/../views/components/publication/burbuja-comentario.html'));
    return $scripts_comentarios . $styles_burbuja;
  }

  private function generarCrearPost(): string
  {
    $template_crear_post = file_get_contents(__DIR__ . '/../views/components/publication/input-post.html');
    return str_replace(
      [
        '{{imagen_perfil}}',
        '{{autor_post}}',
      ],
      [
        $this->data_usuario_session['foto_perfil'],
        $this->data_usuario_session['nombre_completo'],
      ],
      $template_crear_post
    );
  }
}
