<?php 

use App\Controllers\ProductController;


// Read
$app->get('/products', ProductController::class .':index')->setName('home');

//Create
$app->get('/product/create', ProductController::class .':create')->setName('create.form');
$app->post('/product/createOne', ProductController::class. ':createOne')->setName('create');

$app->get('/product/delete/{id}', ProductController::class. ':delete')->setName('delete');