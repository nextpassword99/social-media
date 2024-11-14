<?php
session_start();
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../controllers/indexController.php';

// if (!Auth::validarSession()) {
//   header('Location: /login');
//   exit();
// }

require_once __DIR__ . '/../layouts/layout.php';

// $index = new IndexController(1);
$index = new IndexController($_SESSION["usuario_id"]);
$html = $index->render();
$final = new Layout($html, ['titulo_pagina' => 'Social Media']);
$final->render();
