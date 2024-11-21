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


  public function getVideosPorPostId($post_id)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT url_video
              FROM t_videos
              WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getVideosAleatorios($limit = 10)
  {
    $conn = $this->db->getConnection();
    $query = 'SELECT * FROM t_videos ORDER BY RANDOM() LIMIT :limite';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':limite', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
