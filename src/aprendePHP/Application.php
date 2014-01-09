<?php

namespace aprendePHP;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application {

  public function __construct(Response $response, Request $request){
    $this->response = $response;
    $this->request = $request;
  }

  function index(){
    $path = $this->request->getPathInfo();
    
    $this->get($path);
  }

  public function get($path){

  	switch ($path) {
  		case '/':
  			$this->response->setContent('Index');
  			break;
  		case '/contacto':
  			$this->response->setContent('Contacto');
  			break;
  		default:
  			$this->response->setContent('Pagina no encontrada.');
  			break;
  	}
  	
  }

  public function run(){
    $this->response->send();
  }

}

