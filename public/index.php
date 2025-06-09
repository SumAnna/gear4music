<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router;
use App\Controller\ProductController;

$router = new Router();

$router->get('/', function () {
    (new ProductController())->show();
});

$router->dispatch();
