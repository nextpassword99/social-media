<?php
class PostService
{
  private $postRepository;

  public function __construct(PostRepository $postRepository)
  {
    $this->postRepository = $postRepository;
  }

  public function getPostsPorUsuarioId($usuario_id)
  {
    return $this->postRepository->getPostsPorUsuarioId($usuario_id);
  }

  public function getPostsAleatorios($usuario_id, $limit = 10)
  {
    return $this->postRepository->getPostsAleatorios($usuario_id, $limit);
  }

  public function crearPost($usuario_id, $post_text, $imgs = [], $videos = [])
  {
    return $this->postRepository->crearPost($usuario_id, $post_text, $imgs, $videos);
  }

  public function eliminarPost($post_id)
  {
    return $this->postRepository->eliminarPost($post_id);
  }
}
