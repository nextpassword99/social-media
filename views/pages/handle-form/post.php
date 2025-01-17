<?php
session_start();
require_once __DIR__ . '/../../../models/Auth.php';

header('Content-Type: application/json');
if (!Auth::validarSession()) {
  echo json_encode([
    'procesado' => false,
    'mensaje' => 'Usuario no autenticado'
  ]);
  exit;
}

$carpeta_uploads = 'public/uploads';

if (!file_exists($carpeta_uploads)) {
  $carpeta_img = $carpeta_uploads . '/posts/imgs/';
  $carpeta_video = $carpeta_uploads . '/posts/videos/';
  mkdir($carpeta_img, 0777, true);
  mkdir($carpeta_video, 0777, true);
}

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
      $archivos['imgs'][] = '/' . $ruta_archivo;
    }
  }
}

if (isset($_FILES['videos_post'])) {
  foreach ($_FILES['videos_post']['tmp_name'] as $key => $value) {
    $nombre_archivo = $_FILES['videos_post']['name'][$key];
    $nombre_unico = generarNombreArchivo($nombre_archivo);
    $ruta_archivo = $carpeta_uploads . '/posts/videos/' . basename($nombre_unico);

    if (move_uploaded_file($value, $ruta_archivo)) {
      $archivos['videos'][] = '/' .  $ruta_archivo;
    }
  }
}

$texto_post = $_POST['texto_post'] ?? null;



require_once __DIR__ . '/../../../services/PostService.php';
require_once __DIR__ . '/../../../repositories/PostRepository.php';

$DB = new DB();
$PostRepository = new PostRepository($DB);
$PostService = new PostService($PostRepository);

$post_id = $PostService->crearPost(
  $_SESSION['usuario_id'],
  $texto_post,
  $archivos['imgs'],
  $archivos['videos']
);

if (!$post_id) {
  echo json_encode([
    'procesado' => false,
    'mensaje' => 'Error al crear el post'
  ]);
  exit;
}

echo json_encode([
  'procesado' => true,
  'data' => [
    'post_id' => $post_id
  ]
]);


function generarNombreArchivo($nombre_archivo)
{
  $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
  $nombre_unico = strtolower(uniqid() . '.' . $extension);
  return $nombre_unico;
}

function sanitizarNombreArchivo($nombre_archivo)
{
  return preg_replace('/[^a-zA-Z0-9._-]/', '-', $nombre_archivo);
}
