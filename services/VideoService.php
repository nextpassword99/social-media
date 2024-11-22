<?php
class VideoService
{
  private VideoRepository $VideoRepository;

  public function __construct(VideoRepository $VideoRepository)
  {
    $this->VideoRepository = $VideoRepository;
  }

  public function getVideosAleatorios($limit = 10)
  {
    return $this->VideoRepository->getVideosAleatorios($limit);
  }
}
