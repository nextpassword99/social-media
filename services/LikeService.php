<?php
class LikeService
{
  private $LikeRepository;

  public function __construct(LikeRepository $likeRepository)
  {
    $this->LikeRepository = $likeRepository;
  }

  public function getCountLikesPorIdPost($post_id): int
  {
    return $this->LikeRepository->getCountLikesPorIdPost($post_id);
  }
}
