<?php
require_once __DIR__ . '/../repositories/UsuarioRepository.php';
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

    return str_replace(
      [
        '{{content_no_amigos}}'
      ],
      [
        $content_user
      ],
      $template
    );
  }

  public function generarTarjetasNoAmigos()
  {
    $platilla = file_get_contents(__DIR__ . '/../views/components/amigos/no-friend-card.html');
    $platilla_sin_estilos = HtmlHelper::removeStyles($platilla);

    $usuarios_aleatorios = Usuario::getUsuariosAleatorios(10);

    $html_tarjetas = HtmlHelper::extractStyles($platilla);
    foreach ($usuarios_aleatorios as $usuario) {
      $tarjeta = str_replace(
        [
          '{{nombre_no_amigo}}',
          '{{imagen_perfil}}',
          '{{amigos_relacionados}}'
        ],
        [
          $usuario['nombre'] . ' ' . $usuario['apellido'],
          $usuario['foto_perfil'],
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
