<?php 
namespace App\Controllers;


use Psr\Container\ContainerInterface;


abstract class BaseController {
    protected $c;

    public function __construct(ContainerInterface $c){
        $this->c = $c;
    }
    
    protected function render404($response){
    return $this->c->view->render($response->withStatus(404), 'errors/404.twig');
    }
}