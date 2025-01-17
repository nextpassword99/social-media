<?php
class UsuarioService
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

  public function getUsuariosDesconocidos($limit = 10)
  {
    return $this->UsuarioRepository->getUsuariosDesconocidos($limit);
  }
}
