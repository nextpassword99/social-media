<?php
require_once __DIR__ . '/../models/Auth.php';

if (!Auth::validarSession()) {
    header('Location: /login');
    exit;
}

require_once __DIR__ . '/../layouts/layout.php';
$hmtl = file_get_contents(__DIR__ . '/../views/components/templates/404.html');

$final = new Layout($hmtl, ['titulo_pagina' => '404']);
$final->render();