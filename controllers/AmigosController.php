<?php
require_once __DIR__ . '/../utils/HtmlHelper.php';

class AmigosController
{
  private $UsuarioRepository;

  public function __construct(UsuarioRepository $UsuarioRepository)
  {
    $this->UsuarioRepository = $UsuarioRepository;
  }

  public function render()
  {
    $template = file_get_contents(__DIR__ . '/../views/components/templates/amigos.html');
    $content_user = $this->generarTarjetasNoAmigos();

    $html = str_replace(
      [
        '{{content_no_amigos}}'
      ],
      [
        $content_user
      ],
      $template
    );

    $recursos = $this->cargarRecursos();

    return $html . $recursos;
  }

  public function generarTarjetasNoAmigos()
  {
    $platilla = file_get_contents(__DIR__ . '/../views/components/amigos/no-friend-card.html');
    $platilla_sin_estilos = HtmlHelper::removeStyles($platilla);

    $usuarios_desconocidos = $this->UsuarioRepository->getUsuariosDesconocidos(10);

    $html_tarjetas = '';
    foreach ($usuarios_desconocidos as $usuario) {
      $tarjeta = str_replace(
        [
          '{{nombre_no_amigo}}',
          '{{imagen_perfil}}',
          '{{amigos_relacionados}}'
        ],
        [
          $usuario->getNombreCompleto(),
          $usuario->getFotoPerfil(),
          'El amigo'
        ],
        $platilla_sin_estilos
      );
      $html_tarjetas .= $tarjeta;
    }

    return $html_tarjetas;
  }

  public function cargarRecursos()
  {
    $style_tarjeta_no_amigos = HtmlHelper::extractStyles(file_get_contents(__DIR__ . '/../views/components/amigos/no-friend-card.html'));

    return $style_tarjeta_no_amigos;
  }
}
