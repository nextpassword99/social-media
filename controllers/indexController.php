<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../utils/HtmlHelper.php';
require_once __DIR__ . '/../components/PostComponent.php';
class IndexController
{
  private $usuario_id_session;
  private $data_usuario_session;
  public function __construct($usuario_id_session)
  {
    $this->usuario_id_session = $usuario_id_session;
    $this->cargarDatosSession();
  }
  private function cargarDatosSession()
  {
    $usuario = new Usuario($this->usuario_id_session ?? 1);
    $this->data_usuario_session = $usuario = [
      // 'usuario_id' => $usuario->getUsuarioId(),
      'nombre' => $usuario->getNombre(),
      'apellido' => $usuario->getApellido(),
      'email' => $usuario->getEmail(),
      'foto_perfil' => $usuario->getFotoPerfil()
    ];
  }
  public function render()
  {
    $template = file_get_contents(__DIR__ . '/../views/components/templates/index.html');
    return str_replace([
      '{{aside_left}}',
      '{{content}}'
    ], [
      $this->generarMenu(),
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
        $this->data_usuario_session['nombre'] . " " . $this->data_usuario_session['apellido'],
        $this->usuario_id_session ?? 1
      ],
      $template
    );
  }
}
