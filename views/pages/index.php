<?php
session_start();
require_once __DIR__ . '/../../models/Auth.php';

if (!Auth::validarSession()) {
  header('Location: /login');
  exit();
}

require_once __DIR__ . '/../layouts/layout.php';
require_once __DIR__ . '/../../controllers/indexController.php';
require_once __DIR__ . '/../../repositories/UsuarioRepository.php';
require_once __DIR__ . '/../../services/UsuarioService.php';

$DB = new DB();
$UsuarioRepository = new UsuarioRepository($DB);
$PostRepository = new PostRepository($DB);
$PostService = new PostService($PostRepository);

$index = new IndexController($Usuario, $PostService);
$html = $index->render();
$final = new Layout($html, ['titulo_pagina' => 'Social Media']);
$final->render();
