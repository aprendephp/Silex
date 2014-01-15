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
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\EventListener\RouterListener;

class Application extends HttpKernel implements HttpKernelInterface{

  /**
   * @Symfony\Component\Routing\RouteCollection
   */
  private $routes;

  /**
   * @param $response Symfony\Component\HttpFoundation\Response
   * @param $request Symfony\Component\HttpFoundation\Request
   */
  public function __construct(EventDispatcher $dispatcher, ControllerResolver $resolver){
    $this->dispatch = $dispatcher;
    $this->resolver = $resolver;
    $this->routes = new RouteCollection();
    parent::__construct($dispatcher,$this->resolver);
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

  public function handle(Request $request, $type = 1, $catch = true){

    $context = new RequestContext();
    $context->fromRequest($request);
    $matcher = new UrlMatcher($this->routes, $context);

    $this->dispatcher->addSubscriber(new RouterListener($matcher));
    $response = parent::handle($request, $type, $catch);

    return $response;
  }

  public function run(Request $request = null){

    if (null === $request){
      $request = Request::createFromGlobals();
    }

    $response = $this->handle($request);
    $response->send();
    $this->terminate($request,$response);
  }

  public function terminate(Request $request, Response $response){
    parent::terminate($request, $response);
  }

}

