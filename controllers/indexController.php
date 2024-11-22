<?php
require_once __DIR__ . '/../repositories/PostRepository.php';
require_once __DIR__ . '/../services/PostService.php';
require_once __DIR__ . '/../models/Amigo.php';
require_once __DIR__ . '/../utils/HtmlHelper.php';
require_once __DIR__ . '/../components/PostComponent.php';

class IndexController
{
  private $UsuarioSession;
  private $PostService;

  public function __construct(Usuario $UsuarioSession, PostService $PostService)
  {
    $this->UsuarioSession = $UsuarioSession;
    $this->PostService = $PostService;
  }

  public function render()
  {
    $template = file_get_contents(__DIR__ . '/../views/components/templates/index.html');
    $content = str_replace([
      '{{aside_left}}',
      '{{content}}',
      '{{aside_right}}',
    ], [
      $this->generarMenu(),
      $this->generarPosts(),
      $this->generarAmigos(),
    ], $template);
    $recursos = $this->cargarRecursos();

    return $content . $recursos;
  }

  public function generarPosts()
  {
    $posts = $this->PostService->getPostsAleatorios(50);

    $file_post = file_get_contents(__DIR__ . '/../views/components/publication/post.html');
    $file_post_sin_scripts = HtmlHelper::removeScripts($file_post);
    $file_post_sin_estilos = HtmlHelper::removeStyles($file_post_sin_scripts);

    $post_html = '';
    foreach ($posts as $post) {
      $postComponent = new PostComponent(
        $this->UsuarioSession->getUsuarioId(),
        $this->UsuarioSession->getFotoPerfil(),
        $post->getUsuarioId(),
        $post->getPostId(),
        $post->getUsuarioFotoPerfil(),
        $this->UsuarioSession->getNombre() . ' ' . $this->UsuarioSession->getApellido(),
        $post->getUsuarioNombreCompleto(),
        $post->getFechaPublicacion(),
        $post->getDescripcion(),
        $post->getLikesCount(),
        $post->getComentariosCount(),
        $post->getComentarios(),
        $post->getImgs(),
        $post->getVideos(),
        $file_post_sin_estilos
      );
      $post_html .= $postComponent->render();
    }

    return $post_html;
  }

  public function generarMenu()
  {
    $template = file_get_contents(__DIR__ . '/../views/components/aside/fast-menu.html');

    return str_replace(
      [
        '{{imagen_perfil}}',
        '{{usuario_actual}}',
        '{{id_usuario_actual}}'
      ],
      [
        $this->UsuarioSession->getFotoPerfil(),
        $this->UsuarioSession->getNombre() . ' ' . $this->UsuarioSession->getApellido(),
        $this->UsuarioSession->getUsuarioId()
      ],
      $template
    );
  }

  public function generarAmigos()
  {

    $template_container = file_get_contents((__DIR__ . '/../views/components/aside/amigos.html'));
    $template_card = file_get_contents((__DIR__ . '/../views/components/aside/amigo-card.html'));

    $amigo_card_sin_estilos = HtmlHelper::removeStyles($template_card);

    $amigos = Amigo::getAmigosPorIdUsuario($this->UsuarioSession->getUsuarioId());
    $amigo_card = HtmlHelper::extractStyles($template_card);
    foreach ($amigos as $amigo) {
      $amigo_card .= str_replace(
        [
          '{{imagen_perfil}}',
          '{{nombre_amigo}}',
        ],
        [
          $amigo['foto_perfil'],
          $amigo['nombre'] . " " . $amigo['apellido']
        ],
        $amigo_card_sin_estilos
      );
    }
    $template_container = str_replace('{{content_amigos}}', $amigo_card, $template_container);

    return $template_container;
  }

  public function cargarRecursos()
  {
    $styles_burbuja = HtmlHelper::extractStyles(file_get_contents(__DIR__ . '/../views/components/publication/burbuja-comentario.html'));
    $scripts_comentarios = HtmlHelper::extractScripts(file_get_contents(__DIR__ . '/../views/components/publication/contenedor-comentario.html'));
    $styles_file_post = HtmlHelper::extractStyles(file_get_contents(__DIR__ . '/../views/components/publication/post.html'));

    return $scripts_comentarios . $styles_burbuja . $styles_file_post;
  }
}
