<?php
session_start();
require_once __DIR__ . '/../../models/Auth.php';
if (!Auth::validarSession()) {
  header('Location: /login');
  exit;
}

require_once __DIR__ . '/../layouts/layout.php';
require_once __DIR__ . '/../../controllers/VideosController.php';

$videos =  new VideosController($_SESSION['usuario_id']);
$html =  new Layout($videos->render(), ['titulo_pagina' => 'Videos']);
$html->render();