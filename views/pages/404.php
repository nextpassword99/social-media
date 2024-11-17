<?php
require_once __DIR__ . '/../../models/Auth.php';

if (!Auth::validarSession()) {
    header('Location: /login');
    exit;
}

require_once __DIR__ . '/../layouts/layout.php';
$html = file_get_contents(__DIR__ . '/../components/templates/404.html');

$final = new Layout($html, ['titulo_pagina' => '404']);
$final->render();