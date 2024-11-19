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

$texto_post = $_POST['texto_post'] ?? null;



require_once __DIR__ . '/../../../models/Post.php';

$post_id = Post::setPost($_SESSION['usuario_id'], $texto_post);

if (!$post_id) {
  echo json_encode([
    'procesado' => false,
    'mensaje' => 'Error al crear el post'
  ]);
  exit;
}

if (count($archivos['imgs']) > 0) {
  foreach ($archivos['imgs'] as $key => $value) {
    Post::setImgPost($post_id, $value);
  }
}

if (count($archivos['videos']) > 0) {
  foreach ($archivos['videos'] as $key => $value) {
    Post::setImgPost($post_id, $value);
  }
}

echo json_encode([
  'procesado' => true,
  'mensaje' => 'Post creado correctamente',
]);


function generarNombreArchivo($nombre_archivo)
{
  $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
  $nombre_unico = uniqid() . '.' . $extension;
  return $nombre_unico;
}

function sanitizarNombreArchivo($nombre_archivo)
{
  return preg_replace('/[^a-zA-Z0-9._-]/', '-', $nombre_archivo);
}
