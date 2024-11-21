<?php
class ImgRepository
{
  private $db;
  
  public function __construct(DB $db)
  {
    $this->db = $db;
  }


  public function agregarImgPost($post_id, $img_url)
  {
    $conn = $this->db->getConnection();
    $query = "INSERT INTO t_imagenes (post_id, url_imagen) VALUES (:post_id, :url_imagen)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':url_imagen', $img_url, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  public function getImgsPorPostId($post_id)
  {
    $conn = $this->db->getConnection();
    $query = "SELECT url_imagen 
              FROM t_imagenes 
              WHERE post_id = :post_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

}
