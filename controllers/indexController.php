<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Amigo.php';
require_once __DIR__ . '/../utils/HtmlHelper.php';
require_once __DIR__ . '/../components/PostComponent.php';
class IndexController
{
  private $UsuarioSession;
  private $DB;

  public function __construct(Usuario $UsuarioSession, DB $DB)
  {
    $this->UsuarioSession = $UsuarioSession;
    $this->DB = $DB;
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
    $PostRepository = new PostRepository($this->DB);
    $PostService = new PostService($PostRepository);
    $posts = $PostService->getPostsAleatorios(50);

    $file_post = file_get_contents(__DIR__ . '/../views/components/publication/post.html');
    $file_post_estilos = HtmlHelper::extractStyles($file_post);
    $file_post_sin_estilos = HtmlHelper::removeStyles($file_post);

    $post_html = '';
    foreach ($posts as $post) {
      $postComponent = new PostComponent(
        $this->usuario_id_session,
        $this->data_usuario_session['foto_perfil'],
        $post["usuario_id"],
        $post['post_id'],
        $post['foto_perfil'],
        $this->data_usuario_session['nombre_completo'],
        $post["nombre"] . " " . $post["apellido"],
        $post["fecha_publicacion"],
        $post["descripcion"],
        $file_post_sin_estilos
      );
      $post_html .= $postComponent->render();
    }
    $post_html .= $file_post_estilos;

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
        $this->data_usuario_session['foto_perfil'],
        $this->data_usuario_session['nombre_completo'],
        $this->usuario_id_session ?? 1
      ],
      $template
    );
  }

  public function generarAmigos()
  {

    $template_container = file_get_contents((__DIR__ . '/../views/components/aside/amigos.html'));
    $template_card = file_get_contents((__DIR__ . '/../views/components/aside/amigo-card.html'));

    $amigo_card_sin_estilos = HtmlHelper::removeStyles($template_card);

    $amigos = Amigo::getAmigosPorIdUsuario($this->usuario_id_session);
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
    return $scripts_comentarios . $styles_burbuja;
  }
}
