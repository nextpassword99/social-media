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
}
