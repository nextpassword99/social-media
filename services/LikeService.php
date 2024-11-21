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

  public function addLike($post_id, $user_id): bool
  {
    return $this->LikeRepository->addLike($post_id, $user_id);
  }

}
