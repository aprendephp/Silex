<?php
/**
 * File content aprendePHP\Application
 */

namespace aprendePHP;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

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
   * @Symfony\Component\Routing\RouteCollection
   */
  private $routes;

  /**
   * @param $response Symfony\Component\HttpFoundation\Response
   * @param $request Symfony\Component\HttpFoundation\Request 
   */
  public function __construct(Response $response, Request $request){
    $this->response = $response;
    $this->request = $request;
    $this->routes = new RouteCollection();
  }

  public function get($path, $callback){
    $route = new Route($path,[
      '_controller'=> $callback,
      'method' => 'GET'
    ]);
    $this->routes->add($path,$route);
  }

  public function post($path, $callback){
   $route = new Route($path,[
      '_controller'=> $callback,
      'method' => 'POST'
    ]);
    $this->routes->add($path,$route); 
  }

  public function delete($path, $callback){
    $route = new Route($path,[
      '_controller'=> $callback,
      'method' => 'DELETE'
    ]);
    $this->routes->add($path,$route);
  }

  public function put($path, $callback){
    $route = new Route($path,[
      '_controller'=> $callback,
      'method' => 'PUT'
    ]);
    $this->routes->add($path,$route);
  }

  public function boot(){

    $context = new RequestContext();
    $context->fromRequest($this->request);
    $matcher = new UrlMatcher($this->routes, $context);
    $resolver = new ControllerResolver();

    $path = $this->request->getPathInfo();
    try{

      $params = $matcher->match($path);
      $this->request->attributes->add($params);

      if ($this->request->getMethod() == $params['method']){
        
        $controller = $resolver->getController($this->request);
        $arguments = $resolver->getArguments($this->request, $controller);
        
        $response = call_user_func_array(
          $controller,
          $arguments
        );

        if ($response instanceof Response){
          $this->response = $response;
        }
        else{
          $this->response->setContent($response);
        }
      }
      else{
        $this->response->setStatusCode(404);
        $this->response->setContent('Page not found');  
      }
    }
    catch( ResourceNotFoundException $e){
      $this->response->setStatusCode(404);
      $this->response->setContent('Page not found');
    }

  }

  public function run(){
    $this->boot();
    $this->response->send();
  }

}

