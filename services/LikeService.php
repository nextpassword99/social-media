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

  public function deleteLike($post_id, $user_id): bool
  {
    return $this->LikeRepository->deleteLike($post_id, $user_id);
  }

  public function checkIfLikeExists($post_id, $user_id): bool
  {
    return $this->LikeRepository->checkIfLikeExists($post_id, $user_id);
  }
}
