<?php
class ComentarioService
{
  private $ComentarioRepository;

  public function __construct(ComentarioRepository $ComentarioRepository)
  {
    $this->ComentarioRepository = $ComentarioRepository;
  }

  public function setComentario($post_id, $usuario_id, $comentario)
  {
    return $this->ComentarioRepository->setComentario($post_id, $usuario_id, $comentario);
  }
}
