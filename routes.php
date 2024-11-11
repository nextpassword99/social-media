<?php
require_once __DIR__.'/router.php';


get('/', 'pages/index.php');
get('/user/$id', 'pages/user');
get('/amigos', 'pages/amigos.php');
post('/handle-form/like', 'pages/handle-form/add_like.php');
GET('/login', 'pages/login.php');
GET('/login/$error', 'pages/login.php');
POST('/login-process', 'api/auth/login.php');

any('/404', 'pages/404.php');
