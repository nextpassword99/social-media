<?php
class Layout
{
  private $contenido;
  private $meta_tags;

  public function __construct(string $contenido, array $meta_tags)
  {
    $this->contenido = $contenido;
    $this->meta_tags = $meta_tags;
  }

  public function render()
  {
    $layout = file_get_contents(__DIR__ . '/main.html');
    $header = file_get_contents(__DIR__ . '/header.html');
    $css = file_get_contents(__DIR__ . '/../styles/style.css');
    $css_html = '<style>' . $css . '</style>';

    $layout = str_replace('{{titulo_pagina}}', $this->meta_tags['titulo_pagina'], $layout);
    $layout = str_replace('{{estilos_css}}', $css_html, $layout);
    $layout = str_replace('{{header}}', $header, $layout);
    $layout = str_replace('{{content}}', $this->contenido, $layout);

    echo $layout;
  }
}
