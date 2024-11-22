<?php
require __DIR__ . '/../repositories/UsuarioRepository.php';
require __DIR__ . '/../repositories/PostRepository.php';
require __DIR__ . '/../services/ImgService.php';
require __DIR__ . '/../services/UsuarioService.php';
require __DIR__ . '/../services/PostService.php';
require __DIR__ . '/../utils/HtmlHelper.php';
require __DIR__ . '/../components/PostComponent.php';

class UserController
{
  private $UsuarioView;
  private $UsuarioSession;
  private $db;

  public function __construct($usuario_id_session, $usuario_id, DB $db)
  {
    $this->db = $db;
    $UsuarioRepository = new UsuarioRepository($db);
    $UserService = new UsuarioService($UsuarioRepository);
    $this->UsuarioSession = $UserService->getDatosGeneralesUsuario($usuario_id_session);
    $this->UsuarioView = $UserService->getDatosGeneralesUsuario($usuario_id);
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
    $postRepository = new PostRepository($this->db);
    $postService = new PostService($postRepository);
    $posts_data = $postService->getPostsPorUsuarioId($this->UsuarioView->getUsuarioId());

    $post_platilla = file_get_contents(__DIR__ . '/../views/components/publication/post.html');
    $post_platilla_sin_estilos = HtmlHelper::removeStyles($post_platilla);

    $post_html = "";
    foreach ($posts_data as $post) {
      $post_component = new PostComponent(
        $this->UsuarioSession->getUsuarioId(),
        $this->UsuarioSession->getFotoPerfil(),
        $post->getUsuarioId(),
        $post->getPostId(),
        $this->UsuarioView->getFotoPerfil(),
        $this->UsuarioSession->getNombreCompleto(),
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
        $this->UsuarioView->getNombreCompleto(),
        $this->UsuarioView->getFotoPerfil(),
        11 . ' amigos',
        $this->UsuarioView->getFotoPerfil() ?? '',
        $this->UsuarioSession->getUsuarioId(),
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
        $this->UsuarioView->getDescripcion(),
        $this->UsuarioView->getUbicacion(),
        $this->UsuarioView->getEstadoCivil(),
      ],
      $detalles_plantilla
    );

    return $detalles_html;
  }

  private function fotos(): string
  {
    $detalles_plantilla = file_get_contents(__DIR__ . '/../views/components/aside/fotos.html');

    $id_usuario = $this->UsuarioView->getUsuarioId();
    $ImgRepository = new ImgRepository($this->db);
    $ImgService = new ImgService($ImgRepository);
    $imgs = $ImgService->getImgsPorIdUsuario($id_usuario);

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
      $this->UsuarioView->getNombre(),
      $this->UsuarioView->getApellido(),
      $this->UsuarioView->getEmail(),
      $this->UsuarioView->getDescripcion(),
      $this->UsuarioView->getFecha_registro(),
      $this->UsuarioView->getUbicacion(),
      $this->UsuarioView->getEstadoCivil(),
      $this->UsuarioView->getEducacion(),
    ], $template);
    return $template;
  }

  private function cargarRecursos(): string
  {
    $scripts_comentarios = HtmlHelper::extractScripts(file_get_contents(__DIR__ . '/../views/components/publication/contenedor-comentario.html'));
    $styles_burbuja = HtmlHelper::extractStyles(file_get_contents(__DIR__ . '/../views/components/publication/burbuja-comentario.html'));
    $styles_post = HtmlHelper::extractStyles(file_get_contents(__DIR__ . '/../views/components/publication/post.html'));

    return $scripts_comentarios . $styles_burbuja . $styles_post;
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
        $this->UsuarioSession->getFotoPerfil(),
        $this->UsuarioSession->getNombreCompleto(),
      ],
      $template_crear_post
    );
  }
}
