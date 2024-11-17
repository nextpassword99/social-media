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

  /**
   * Elimina todas las etiquetas <script> de un contenido HTML y devuelve
   * el contenido sin scripts.
   *
   * @param string $htmlContent El contenido de HTML para eliminar scripts.
   *
   * @return string El contenido de HTML sin scripts.
   */
  public static function removeScripts($htmlContent)
  {
    $pattern = '/<script>(.*?)<\/script>/si';
    return preg_replace($pattern, '', $htmlContent);
  }
  /**
   * Extrae todas las etiquetas <script> de un contenido HTML y devuelve
   * un string con todos los scripts encontrados.
   *
   * @param string $htmlContent El contenido de HTML para extraer scripts.
   *
   * @return string Todas las etiquetas <script> en una sola cadena.
   */
  public static function extractScripts($htmlContent)
  {
    $pattern = '/<script>(.*?)<\/script>/si';
    preg_match_all($pattern, $htmlContent, $matches);
    $consolidatedScripts = isset($matches[0]) ? implode("\n", $matches[0]) : '';
    return $consolidatedScripts;
  }
}
