<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;

$response = new Response();
$request = Request::createFromGlobals();

$app = new aprendePHP\Application($response, $request);

$app->get('/', function () use($app){
  return "index";
});

$app->get('/hello/{name}', function ($request) use($app){
  return "Hello " . $request->attributes->get('name');
});

$app->get('/images', function () use($app, $request){

  $images = scandir(__DIR__.'/uploads');

  if ($request->cookies->get('colaborador') == 'on'){
    $respuesta = '<h1>Gracias por tus aportes</h1>';
  }
  else{
    $respuesta = '<h1>Lista de cervezas</h1>';
  }
  $respuesta .= '<ul>';
  foreach (array_diff($images, ['.','..']) as $key => $value) {
    $respuesta .= '<li>'.$key.' - <img src="/uploads/' . $value . '" width="100"/></li>';
  }
  $respuesta .= '</ul>';

  return $respuesta;
});

$app->get('/images/last', function(){

  $images = glob(__DIR__ . '/uploads/*');
  $images = array_combine($images, array_map('filemtime', $images));
  arsort($images);

  return key($images);
});

$app->post('/alta-cerveza', function () use($app, $request){

  $name = $request->request->get('name');
  $image = $request->files->get('imagen');
  $image->move(__DIR__. '/uploads/', $image->getClientOriginalName());

  $response = new RedirectResponse('images');
  $response->headers->setCookie(new Cookie('colaborador','on'));
  return $response;
});


$app->run();
