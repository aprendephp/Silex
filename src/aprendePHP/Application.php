<?php
/**
 * File content aprendePHP\Application
 */

namespace aprendePHP;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application {

  /**
   * @Symfony\Component\HttpFoundation\Response
   */
  private $response;

  /**
   * @Symfony\Component\HttpFoundation\Request
   */
  private $request;

  /**
   * @Array
   */
  private $paths;

  /**
   * @param $response Symfony\Component\HttpFoundation\Response
   * @param $request Symfony\Component\HttpFoundation\Request 
   */
  public function __construct(Response $response, Request $request){
    $this->response = $response;
    $this->request = $request;
    $this->paths = [];
  }

  public function get($path, $callback){
  	$this->paths[$path]['callback'] = $callback;
    $this->paths[$path]['method'] = 'GET';
  }

  public function post($path, $callback){
    $this->paths[$path]['callback'] = $callback;
    $this->paths[$path]['method'] = 'POST';
  }

  public function delete($path, $callback){
    $this->paths[$path]['callback'] = $callback;
    $this->paths[$path]['method'] = 'DELETE';
  }

  public function put($path, $callback){
    $this->paths[$path]['callback'] = $callback;
    $this->paths[$path]['method'] = 'PUT';
  }

  public function boot(){
    $path = $this->request->getPathInfo();
    if (array_key_exists($path, $this->paths) && 
      $this->paths[$path]['method'] == $this->request->getMethod()) {
      $respuesta = call_user_func($this->paths[$path]['callback']);

      if ($respuesta instanceof Response){
        $this->response = $respuesta;
      }
      else{
        $this->response->setContent($respuesta);
      }
    }
    else{
      $this->response->setStatusCode(404);
      $this->response->setContent('Page not found');
    }
  }

  public function run(){
    $this->boot();
    $this->response->send();
  }

}

