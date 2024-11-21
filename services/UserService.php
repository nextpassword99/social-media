<?php
class UserService
{
  private $UsuarioRepository;

  public function __construct(UsuarioRepository $UsuarioRepository)
  {
    $this->UsuarioRepository = $UsuarioRepository;
  }
}
