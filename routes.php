<?php
require_once __DIR__ . '/router.php';


get('/', 'pages/index.php');
get('/user/$usuario_id', 'pages/user');
get('/amigos', 'pages/amigos.php');
post('/handle-form/like', 'pages/handle-form/like.php');
GET('/login', 'pages/login.php');
GET('/login/$error', 'pages/login.php');
POST('/login-process', 'pages/login.php');
GET('/videos', 'pages/videos.php');
GET('/videos/$post_id', 'pages/video_id.php');
POST('/handle-form/comentario', 'pages/handle-form/comentario.php');

any('/404', 'pages/404.php');
