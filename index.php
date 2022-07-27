<?php

require './vendor/autoload.php';
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

$configuration = [
   'settings' => [
       'displayErrorDetails' => true,
       'outputBuffering' => false,
   ],
];


$app = new \Slim\App($configuration);

$container = $app->getContainer();

$container['db'] = function (){
   return new PDO('mysql:host=localhost;port=3306;dbname=users','root','');
};

$container['view'] = function ($container) {

   $view = new \Slim\Views\Twig('./views', [
       'cache' => false
   ]);

   // Instantiate and add Slim specific extension
   $router = $container->get('router');
   $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
   $view->addExtension(new Slim\Views\TwigExtension($router, $uri));

   return $view;
};

$app->get('/', function($request, $response){
    $users = $this->db->query('SELECT * FROM users')->fetchAll(PDO::FETCH_ASSOC);
    return $response->write(print_r($users, true)); 
});

// $app->get('/users/{username}', function($request, $response, $args){
//    $statement = $this->db->prepare("SELECT * FROM users_table WHERE username = :username");
//    $statement->execute([
//       'username'=> $args['username']
//    ]);

//    $user = $statement->fetch(PDO::FETCH_ASSOC);
   
//    return $this->view->render($response,"/users/profile.twig",[
//             'user'=>$user, 
//            ]);

// });

$app->run();
























// $app->get('/', function ($request,$response) {
//    return $this->view->render($response,"home.twig");
// })->setName('home');

// $app->get('/users', function ($request,$response) {
//    return $this->view->render($response,"users.twig");
// })->setName('users.index');

// $app->get('/contact', function ($request,$response) {
//    return $this->view->render($response,"contact.twig");
// });

// $app->post('/contact-res', function(\Slim\Http\Request $request, \Slim\Http\Response $response){
//      $result= $request->getParam('name');
//      return $response->getBody()->write(print_r($result, true)); 
// })->setName('contact');




// $app->get('/contact', function ($request,$response) {
//    return $this->view->render($response,"contact.twig");
// });

// $app->post('/contact-res', function(\Slim\Http\Request $request, \Slim\Http\Response $response){
   //      $result= $request->getParams();

   //      return $this->view->render($response,"check.twig",[
      //       'result'=>$result,
      //      ]);

// })->setName('contact');

// $app->get('/users/{userId}', function(\Slim\Http\Request $request, \Slim\Http\Response $response , $args){
//    return $response->write(print_r($args['userId'], true)); 
// });




// $app->get('/contact', function(\Slim\Http\Request $request, \Slim\Http\Response $response){
//    return $this->view->render($response, "contact.twig");
// });

// $app->get('/contact/confirm', function(\Slim\Http\Request $request, \Slim\Http\Response $response){
//    return $this->view->render($response, "contact-confirm.twig");
// })->setName('contact-confirm');


// $app->post('/contact', function(\Slim\Http\Request $request, \Slim\Http\Response $response){
//    // $router = $this->router;
//    // return $response->withRedirect($router->pathFor('contact-confirm'));
//       return $response->withRedirect('contact/confirm');
// })->setName('contact.form');