<?php
class ComentarioService
{
  private $ComentarioRepository;

  public function __construct(ComentarioRepository $ComentarioRepository)
  {
    $this->ComentarioRepository = $ComentarioRepository;
  }
}
