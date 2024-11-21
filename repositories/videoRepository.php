<?php
class VideoRepository
{
  private $db;

  public function __construct(DB $db)
  {
    $this->db = $db;
  }


  public function agregarVideoPost($post_id, $video_url)
  {
    $conn = $this->db->getConnection();
    $query = "INSERT INTO t_videos (post_id, url_video) VALUES (:post_id, :url_video)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':url_video', $video_url, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

}
