<?php
session_start();

if (!isset($_SESSION["usuario_id"]) && !isset($_SESSION["token"])) {
  header('Location: /login');
  exit;
}

session_abort();
session_destroy();
header('Location: /login');
exit;
