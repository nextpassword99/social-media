<?php
session_start();
require_once __DIR__ . '/../../models/Auth.php';

if (!Auth::validarSession()) {
  header('Location: /login');
  exit();
}


require_once __DIR__ . '/../layouts/layout.php';
require_once __DIR__ . '/../../controllers/AmigosController.php';

$DB = new DB();
$html = new Layout($amigos->render(), ['titulo_pagina' => 'Amigos']);;
$html->render();