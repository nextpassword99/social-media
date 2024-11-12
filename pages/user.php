<?php
require_once __DIR__ . '/../models/Auth.php';
if (!Auth::validarSession()) {
  header('Location: /login');
}

require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../layouts/layout.php';

$html_user = new UserController($usuario_id);
$contenido_html = $html_user->render();
$contenido_final = new Layout($contenido_html, ['titulo_pagina' => 'Hola mundo']);
$contenido_final->render();
