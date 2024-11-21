<?php
class ImgService
{
  private $ImgRepository;

  public function __construct(ImgRepository $ImgRepository)
  {
    $this->ImgRepository = $ImgRepository;
  }
  public function getImgsPorIdUsuario($usuario_id)
  {
    return $this->ImgRepository->getImgsPorIdUsuario($usuario_id);
  }
}
