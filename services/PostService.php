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

  public function getPostsAleatorios($limit = 10)
  {
    return $this->postRepository->getPostsAleatorios($limit);
  }

  public function crearPost($usuario_id, $post_text, $imgs = [], $videos = [])
  {
    return $this->postRepository->crearPost($usuario_id, $post_text, $imgs, $videos);
  }

}
