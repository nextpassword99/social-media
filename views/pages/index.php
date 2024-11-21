<?php
session_start();
require_once __DIR__ . '/../../models/Auth.php';

if (!Auth::validarSession()) {
  header('Location: /login');
  exit();
}

require_once __DIR__ . '/../layouts/layout.php';
require_once __DIR__ . '/../../controllers/indexController.php';

$DB = new DB();
$Usuario = new Usuario($_SESSION['usuario_id']);
$PostReository = new PostRepository($DB);
$PostService = new PostService($PostReository);

$index = new IndexController($Usuario, $PostService);
$html = $index->render();
$final = new Layout($html, ['titulo_pagina' => 'Social Media']);
$final->render();
