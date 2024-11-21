<?php
class UserService
{
  private $UsuarioRepository;

  public function __construct(UsuarioRepository $UsuarioRepository)
  {
    $this->UsuarioRepository = $UsuarioRepository;
  }
  
  public function getDatosGeneralesUsuario($usuario_id)
  {
    return $this->UsuarioRepository->getDatosGeneralesUsuario($usuario_id);
  }
}
