<?php
require_once __DIR__ . '/../components/ComentarioComponent.php';

class PostComponent
{
  private $user_id_session;
  private $foto_perfil_session;
  private $user_id;
  private $post_id;
  private $img_perfil;
  private $nombre_session;
  private $nombre_post;
  private $fecha_post;
  private $content_text;
  private $post_template;
  private $visual;

  public function __construct(
    int $user_id_session,
    string $foto_perfil_session,
    int $user_id,
    int $post_id,
    string $img_perfil,
    string $nombre_session,
    string $nombre_post,
    string $fecha_post,
    string $content_text,
    string $post_template,
    string $visual = 'Mixto',
  ) {
    $this->user_id_session = $user_id_session;
    $this->foto_perfil_session = $foto_perfil_session;
    $this->user_id = $user_id;
    $this->post_id = $post_id;
    $this->img_perfil = $img_perfil;
    $this->nombre_session = $nombre_session;
    $this->nombre_post = $nombre_post;
    $this->fecha_post = $fecha_post;
    $this->content_text = $content_text;
    $this->post_template = $post_template;
    $this->visual = $visual;
  }

  public function render()
  {

    $postObject = new Post($this->post_id);
    $count_comentarios = $postObject->getCountComentariosPorIdPost();
    $images = $this->visual == 'Mixto' || $this->visual == 'img' ? $postObject->getImgsPorIdPost($this->post_id) : [];
    $videos = $this->visual == 'Mixto' || $this->visual == 'video' ? $postObject->getVideosPorIdPost($this->post_id) : [];
    $countLikes = $postObject->getCountLikesPorIdPost($this->post_id);
    $existeLike = $postObject->checkIfLikeExists($this->post_id, $this->user_id_session);

    $comentarios = $postObject->getUnComentarioPorIdPost();

    $contenido_visual = $this->generarContenidoVisual($images, $videos);

    $post_html = $this->remplazarVariablesHtml($count_comentarios, $contenido_visual, $countLikes, $existeLike);

    $comentario = new ComentarioComponent($this->foto_perfil_session, $this->nombre_session, $this->user_id_session, $this->post_id, $comentarios);
    $post_html = str_replace('{{content_comentarios}}', $comentario->render(), $post_html);

    return $post_html;
  }

  private function generarContenidoVisual($images, $videos)
  {
    $contenido_visual = '';

    foreach ($images as $image) {
      $contenido_visual .= '<img class="visual" src="' . htmlspecialchars($image['url_imagen']) . '" />';
    }

    foreach ($videos as $video) {
      $contenido_visual .= '<video class="visual" src="' . htmlspecialchars($video['url_video']) . '" controls></video>';
    }

    return $contenido_visual;
  }

  private function remplazarVariablesHtml($count_comentarios, $contenido_visual, $countLikes, $existeLike)
  {
    return str_replace([
      '{{imagen_perfil}}',
      '{{autor_post}}',
      '{{fecha_post}}',
      '{{content_text}}',
      '{{content_visual}}',
      '{{numero_reacciones}}',
      '{{numero_comentarios}}',
      '{{post_id}}',
      '{{user_id}}',
      '{{usuario_actual}}',
      '{{is_liked}}'
    ], [
      $this->img_perfil,
      $this->nombre_post,
      $this->fecha_post,
      $this->content_text,
      $contenido_visual,
      $countLikes,
      $count_comentarios,
      $this->post_id,
      $this->user_id_session,
      $this->nombre_session,
      $existeLike ? 'fas' : 'far'
    ], $this->post_template);
  }
}
