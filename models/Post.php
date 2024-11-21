<?php
require_once __DIR__ . '/DB.php';
class Post
{
  private $post_id;
  private $usuario_id;
  private $descripcion;
  private $fecha_publicacion;
  private $usuario_nombre;
  private $usuario_apellido;
  private $usuario_foto_perfil;
  private $likes_count;
  private $comentarios_count;
  private $comentarios = [];
  private $imgs = [];
  private $videos = [];

  public function __construct(
    $post_id,
    $usuario_id,
    $descripcion,
    $fecha_publicacion,
    $usuario_nombre,
    $usuario_apellido,
    $usuario_foto_perfil,
    $likes_count,
    $comentarios_count,
    $comentarios = [],
    $imgs = [],
    $videos = []
  ) {
    $this->post_id = $post_id;
    $this->usuario_id = $usuario_id;
    $this->descripcion = $descripcion;
    $this->fecha_publicacion = $fecha_publicacion;
    $this->usuario_nombre = $usuario_nombre;
    $this->usuario_apellido = $usuario_apellido;
    $this->usuario_foto_perfil = $usuario_foto_perfil;
    $this->comentarios_count = $comentarios_count;
    $this->likes_count = $likes_count;
    $this->comentarios = $comentarios;
    $this->imgs = $imgs;
    $this->videos = $videos;
  }

  public function getPostId(): int
  {
    return $this->post_id;
  }
  public function getUsuarioId(): int
  {
    return $this->usuario_id;
  }
  public function getDescripcion(): string
  {
    return $this->descripcion;
  }
  public function getFechaPublicacion(): string
  {
    return $this->fecha_publicacion;
  }
  public function getUsuarioNombreCompleto(): string
  {
    return $this->usuario_nombre . ' ' . $this->usuario_apellido;
  }
  public function getUsuarioFotoPerfil(): string
  {
    return $this->usuario_foto_perfil;
  }
  public function getLikesCount(): int
  {
    return $this->likes_count;
  }
  public function getComentariosCount(): int
  {
    return $this->comentarios_count;
  }
  public function getComentarios(): array
  {
    return $this->comentarios;
  }

  public function getImgs(): array
  {
    return $this->imgs;
  }
  public function getVideos(): array
  {
    return $this->videos;
  }
}
