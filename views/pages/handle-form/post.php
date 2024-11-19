$carpeta_uploads = 'public/uploads';

$archivos = [
  'imgs' => [],
  'videos' => []
];

if (isset($_FILES['imgs_post'])) {
  foreach ($_FILES['imgs_post']['tmp_name'] as $key => $value) {
    $nombre_archivo = $_FILES['imgs_post']['name'][$key];
    $nombre_unico = generarNombreArchivo($nombre_archivo);
    $ruta_archivo = $carpeta_uploads . '/posts/imgs/' . basename($nombre_unico);

    if (move_uploaded_file($value, $ruta_archivo)) {
      $archivos['imgs'][] = $ruta_archivo;
    }
  }
}

if (isset($_FILES['videos_post'])) {
  foreach ($_FILES['videos_post']['tmp_name'] as $key => $value) {
    $nombre_archivo = $_FILES['videos_post']['name'][$key];
    $nombre_unico = generarNombreArchivo($nombre_archivo);
    $ruta_archivo = $carpeta_uploads . '/posts/videos/' . basename($nombre_unico);

    if (move_uploaded_file($value, $ruta_archivo)) {
      $archivos['videos'][] = $ruta_archivo;
    }
  }
}
