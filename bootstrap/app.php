<?php 

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

// $configuration = [
//    'settings' => [
//        'displayErrorDetails' => true,
//     //    'outputBuffering' => false,
//    ],
// ];

$app = new \Slim\App();

$container = $app->getContainer();

$container['db'] = function (){
   return new PDO('mysql:host=localhost;port=3306;dbname=product_crud','root','');
};

$container['view'] = function ($container) {

   $view = new \Slim\Views\Twig('../views', [
       'cache' => false
   ]);

   // Instantiate and add Slim specific extension
   $router = $container->get('router');
   $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
   $view->addExtension(new Slim\Views\TwigExtension($router, $uri));

   return $view;
};



require __DIR__."/../routes/web.php";