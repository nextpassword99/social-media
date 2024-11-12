<?php

class HtmlHelper
{
  /**
   * Extrae todas las etiquetas <style> de un contenido HTML y devuelve
   * en una sola cadena.
   *
   * @param string $htmlContent El contenido de HTML para extraer estilos.
   *
   * @return string Las etiqueta <style> en una sola cadena.
   */
  public static function extractStyles($htmlContent)
  {
    $pattern = '/<style>(.*?)<\/style>/si';
    preg_match_all($pattern, $htmlContent, $matches);
    $consolidatedStyles = isset($matches[0]) ? implode("\n", $matches[0]) : '';
    return $consolidatedStyles;
  }


  /**
   * Elimina todas las etiquetas <style> de un contenido HTML y devuelve
   * el contenido sin estilos.
   *
   * @param string $htmlContent El contenido de HTML para eliminar estilos.
   *
   * @return string El contenido de HTML sin estilos.
   */
  public static function removeStyles($htmlContent)
  {
    $pattern = '/<style>(.*?)<\/style>/si';
    return preg_replace($pattern, '', $htmlContent);
  }
}
