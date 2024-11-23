<?php
session_start();
require_once __DIR__ . '/../../models/Auth.php';


session_unset();
session_destroy();
header('Location: /login');
exit;
