<?php

namespace aprendePHP;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application {

  public function __construct(){
    $this->response = new Response();
    $this->request = Request::createFromGlobals();
  }

  function index(){
    $path = $this->request->getPathInfo();
    $this->response->setContent(
      "<html><head></head><body>Esta peticion fue hecha en la url: " . $path . "</body></html>"
    );
  }

  public function run(){
    $this->response->send();
  }

}

