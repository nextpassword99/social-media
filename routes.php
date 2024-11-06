<?php
require_once __DIR__.'/router.php';


get('/', 'pages/index.php');
get('/user/$id', 'pages/user');
get('/user/$id/amigos', 'pages/amigos.php');
post('/handle-form/like', 'pages/handle-form/add_like.php');
