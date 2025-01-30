<?php
require_once __DIR__ . '/router.php';


get('/', 'views/pages/index.php');
get('/user/$usuario_id', 'views/pages/user/user.php');
get('/user/$usuario_id/detalles', 'views/pages/user/detalles.php');
get('/amigos', 'views/pages/amigos.php');
post('/handle-form/like', 'views/pages/handle-form/like.php');
GET('/login', 'views/pages/login.php');
GET('/login/$error', 'views/pages/login.php');
POST('/login-process', 'views/pages/login.php');
GET('/videos', 'views/pages/videos.php');
GET('/videos/$post_id', 'views/pages/video_id.php');
POST('/handle-form/comentario', 'views/pages/handle-form/comentario.php');
POST('/handle-form/post', 'views/pages/handle-form/post.php');
GET('/logout', 'views/pages/logout.php');

any('/404', 'views/pages/404.php');
