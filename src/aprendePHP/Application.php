<?php

namespace aprendePHP;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application {

  protected $response;
  protected $request;
  protected $path;

  public function __construct(Response $response, Request $request){
    $this->response = $response;
    $this->request = $request;
    $path = [];
  }

  public function get($path, $callback){
    $this->paths[$path]['method'] = 'GET';
    $this->paths[$path]['callback']= $callback;
  }

  public function post($path, $callback){
    $this->paths[$path]['method'] = 'POST';
    $this->paths[$path]['callback']= $callback;
  }

  public function put($path, $callback){
    $this->paths[$path]['method'] = 'PUT';
    $this->paths[$path]['callback']= $callback;
  }

  public function delete($path, $callback){
    $this->paths[$path]['method'] = 'DELETE';
    $this->paths[$path]['callback']= $callback;
  }

  public function boot(){
    $path = $this->request->getPathInfo();

    // If path exist and method is correct
    if (array_key_exists($path, $this->paths) &&
      $this->paths[$path]['method'] == $this->request->getMethod()){
      $r = call_user_func($this->paths[$path]['callback']);

      if ($r instanceof Response){
        $this->response = $r;
      }
      else {
        $this->response->setContent($r);
      }
    }
    else{
      $this->response->setStatusCode(404);
      $this->response->setContent("Page not found");
    }
  }

  public function run(){
    $this->boot();
    $this->response->send();
  }

}

