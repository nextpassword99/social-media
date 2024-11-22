<?php
session_start();
require_once __DIR__ . '/../../models/Auth.php';
if (!Auth::validarSession()) {
  header('Location: /login');
  exit;
}

require_once __DIR__ . '/../layouts/layout.php';
require_once __DIR__ . '/../../controllers/VideosController.php';
require_once __DIR__ . '/../../repositories/UsuarioRepository.php';
require_once __DIR__ . '/../../services/UsuarioService.php';
require_once __DIR__ . '/../../repositories/VideoRepository.php';
require_once __DIR__ . '/../../services/VideoService.php';

$DB = new DB();
$UsuarioRepository = new UsuarioRepository($DB);
$UsuarioService = new UsuarioService($UsuarioRepository);
$Usuario = $UsuarioService->getDatosGeneralesUsuario($_SESSION['usuario_id']);
$VideoRepository = new VideoRepository($DB);
$VideoService = new VideoService($VideoRepository);

$html =  new Layout($videos->render(), ['titulo_pagina' => 'Videos']);
$html->render();