<?php
session_start();
require_once __DIR__ . '/../../../models/Auth.php';
if (!Auth::validarSession()) {
  header('Location: /login');
  exit;
}

require_once __DIR__ . '/../../../controllers/UserController.php';
require_once __DIR__ . '/../../layouts/layout.php';
require_once __DIR__ . '/../../../models/DB.php';

$DB = new DB();
$html_user = new UserController($_SESSION['usuario_id'], $usuario_id, $DB);


$unido = [
  $html_user->renderHeader(),
  $html_user->renderInfoUsuario()
];

$contenido_html = $html_user->renderCompletePage($unido);
$contenido_final = new Layout($contenido_html, ['titulo_pagina' => 'Detalles de Usuario']);
$contenido_final->render();
