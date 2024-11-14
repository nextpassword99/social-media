<?php
require_once _DIR_ . '/../models/Auth.php';

if (!Auth::validarSession()) {
    header('Location: /login');
    exit;
}

require_once _DIR_ . '/../layouts/layout.php';
$hmtl = file_get_contents(_DIR_ . '/../views/components/templates/404.html');

$final = new Layout($hmtl, ['titulo_pagina' => '404']);
$final->render();