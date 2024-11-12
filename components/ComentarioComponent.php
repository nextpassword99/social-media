<?php

class ComentarioComponent
{
  private $img_perfil;
  private $nombre_session;
  private $usuario_id;
  private $post_id;
  private $comentarios;

  public function __construct($img_perfil, $nombre_session, $usuario_id, $post_id, array $comentarios = [])
  {
    $this->img_perfil = $img_perfil;
    $this->nombre_session = $nombre_session;
    $this->usuario_id = $usuario_id;
    $this->post_id = $post_id;
    $this->comentarios = $comentarios;
  }

  public function setComentarios(array $comentarios)
  {
    $this->comentarios = $comentarios;
  }

  public function render()
  {
    $comentarios_contenedor = file_get_contents(__DIR__ . '/../views/components/publication/contenedor-comentario.html');
    $comentario_burbuja = file_get_contents(__DIR__ . '/../views/components/publication/burbuja-comentario.html');

    $comentarios_burbujas = $this->generarBurbujas($comentario_burbuja);

    return str_replace([
      '{{content_comentarios_video}}',
      '{{imagen_perfil}}',
      '{{usuario_actual}}'
    ], [
      $comentarios_burbujas,
      $this->img_perfil,
      $this->nombre_session
    ], $comentarios_contenedor);
  }

  private function generarBurbujas($comentario_burbuja)
  {
    $comentarios_burbujas = '';

    foreach ($this->comentarios as $comentario) {
      $usuario = new Usuario($comentario['usuario_id']);

      $comentarios_burbujas .= str_replace([
        '{{imagen_autor_comentario}}',
        '{{usuario_comentario}}',
        '{{contenido_comentario}}'
      ], [
        $usuario->getFotoPerfil(),
        $usuario->getNombre() . ' ' . $usuario->getApellido(),
        $comentario['contenido']
      ], HtmlHelper::removeStyles($comentario_burbuja));
    }

    return $comentarios_burbujas . HtmlHelper::extractStyles($comentario_burbuja);
  }
}
