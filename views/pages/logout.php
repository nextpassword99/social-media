<?php
session_start();
require_once __DIR__ . '/../../models/Auth.php';

if (!Auth::validarSession()) {
  header('Location: /login');
  exit;
}

session_abort();
session_destroy();
header('Location: /login');
exit;
