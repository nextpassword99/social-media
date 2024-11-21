<?php
require_once __DIR__ . '/../models/Usuario.php';

class ComentarioComponent
{
  private $img_perfil;
  private $nombre_session;
  private $usuario_id_session;
  private $post_id;
  private $comentarios;

  public function __construct($img_perfil, $nombre_session, $usuario_id, $post_id, array $comentarios = [])
  {
    $this->img_perfil = $img_perfil;
    $this->nombre_session = $nombre_session;
    $this->usuario_id_session = $usuario_id;
    $this->post_id = $post_id;
    $this->comentarios = $comentarios;
  }

  public function setComentarios(array $comentarios)
  {
    $this->comentarios = $comentarios;
  }

  public function render()
  {
    $comentarios_contenedor = HtmlHelper::removeScripts(file_get_contents(__DIR__ . '/../views/components/publication/contenedor-comentario.html'));
    $comentario_burbuja = file_get_contents(__DIR__ . '/../views/components/publication/burbuja-comentario.html');

    $comentarios_burbujas = $this->generarBurbujas($comentario_burbuja);

    return str_replace([
      '{{content_comentarios_video}}',
      '{{imagen_perfil}}',
      '{{usuario_actual}}',
      '{{post_id}}',
    ], [
      $comentarios_burbujas,
      $this->img_perfil,
      $this->nombre_session,
      $this->post_id,
    ], $comentarios_contenedor);
  }

  private function generarBurbujas($comentario_burbuja)
  {
    $burbuja_platilla = HtmlHelper::removeStyles($comentario_burbuja);
    $comentarios_burbujas = '';

    foreach ($this->comentarios as $comentario) {
      if (isset($comentario['usuario_id_comentario'])) {
        $comentarios_burbujas .= str_replace([
          '{{imagen_autor_comentario}}',
          '{{usuario_comentario}}',
          '{{contenido_comentario}}',
          '{{usuario_id_comentario}}',
        ], [
          $comentario['comentario_usuario_foto_perfil'],
          $comentario['comentario_usuario_nombre'] . ' ' . $comentario['comentario_usuario_apellido'],
          $comentario['comentario'],
          $comentario['usuario_id_comentario'],
        ], $burbuja_platilla);
      }
    }

    return $comentarios_burbujas;
  }
}
