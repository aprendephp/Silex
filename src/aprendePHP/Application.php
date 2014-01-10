<?php

namespace aprendePHP;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Application {

  protected $response;
  protected $request;
  protected $path;

  public function __construct(Response $response, Request $request){
    $this->response = $response;
    $this->request = $request;
    $this->path = [];
    $this->routes = new RouteCollection();
  }

  public function get($path, $callback){
    $this->routes->add($path, new Route($path,[
      'callback'=>$callback,
      'method' => 'GET'
    ]));
  }

  public function post($path, $callback){
    $this->routes->add($path, new Route($path,[
      'callback'=>$callback,
      'method' => 'POST'
    ]));
  }

  public function put($path, $callback){
    $this->routes->add($path, new Route($path,[
      'callback'=>$callback,
      'method' => 'PUT'
    ]));
  }

  public function delete($path, $callback){
    $this->paths[$path]['method'] = 'DELETE';
    $this->paths[$path]['callback']= $callback;
  }

  public function boot(){
    $path = $this->request->getPathInfo();

    $context = new RequestContext();
    $context->fromRequest($this->request);
    $matcher = new UrlMatcher($this->routes, $context);

    try {

      $this->request->attributes->add(
        $matcher->match(
          $this->request->getPathInfo()
        )
      );

      if ($this->request->getMethod() == $this->request->attributes->get('method')){
        $response = call_user_func(
          $this->request->attributes->get('callback'),
          $this->request
        );

        if ($response instanceof Response){
          $this->response = $response;
        }
        else {
          $this->response->setContent($response);
        }
      }
      else{
        $this->response = new Response('Not Found', 404);
      }
    } catch (ResourceNotFoundException $e) {
      $this->response = new Response('Not Found', 404);
    }
  }

  public function run(){
    $this->boot();
    $this->response->send();
  }

}

