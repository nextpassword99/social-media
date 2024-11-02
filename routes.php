<?php
require_once __DIR__.'/router.php';


get('/', 'pages/index.php');

get('/user/$id', 'pages/user');

get('/user/$name/$last_name', 'pages/full_name.php');

get('/product/$type/color/$color', 'product.php');

get('/handle-form/like', 'pages/handle-form/add_like.php');
