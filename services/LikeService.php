<?php
class LikeService
{
  private $LikeRepository;

  public function __construct(LikeRepository $likeRepository)
  {
    $this->LikeRepository = $likeRepository;
  }
}
