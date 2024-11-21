<?php
class ImgService
{
  private $ImgRepository;

  public function __construct(ImgRepository $ImgRepository)
  {
    $this->ImgRepository = $ImgRepository;
  }
}
