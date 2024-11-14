<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../utils/HtmlHelper.php';
require_once __DIR__ . '/../components/PostComponent.php';
class IndexController
{
  private $usuario_id_session;
  public function __construct($usuario_id_session)
  {
    $this->usuario_id_session = $usuario_id_session;
  }
  public function render()
  {
    $template = file_get_contents(__DIR__ . '/../views/components/templates/index.html');
    return str_replace([
      '{{content}}'
    ], [
      $this->generarPosts()
    ], $template);
  }

  public function generarPosts()
  {
    $postObject = new Post($this->usuario_id_session);
    $posts = $postObject->getPostsAleatorios();

    $file_post = file_get_contents(__DIR__ . '/../views/components/publication/post.html');
    $file_post_estilos = HtmlHelper::extractStyles($file_post);
    $file_post_sin_estilos = HtmlHelper::removeStyles($file_post);

    $post_html = '';
    foreach ($posts as $post) {
      $postComponent = new PostComponent(
        $post["usuario_id"],
        $post['post_id'],
        $post['foto_perfil'],
        "Edison",
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
}
