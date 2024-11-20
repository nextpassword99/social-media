<?php
session_start();
require_once __DIR__ . '/../../../models/Auth.php';
if (!Auth::validarSession()) {
  header('Location: /login');
  exit;
}

require_once __DIR__ . '/../../../controllers/UserController.php';
require_once __DIR__ . '/../../layouts/layout.php';

$html_user = new UserController($_SESSION['usuario_id'], $usuario_id);

$elementos = [
  $html_user->renderHeader(),
  $html_user->renderAside(),
  $html_user->renderCrearPost() . $html_user->renderPosts(),
];

$contenido_html = $html_user->renderCompletePage($elementos);
$contenido_final = new Layout($contenido_html, ['titulo_pagina' => 'Usuario - Social Media']);
$contenido_final->render();
