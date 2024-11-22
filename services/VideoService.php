<?php
class VideoService
{
  private VideoRepository $VideoRepository;

  public function __construct(VideoRepository $VideoRepository)
  {
    $this->VideoRepository = $VideoRepository;
  }

}
