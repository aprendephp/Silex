<?php

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\HttpKernel\Exception\FlattenException;

use aprendePHP\EventListener\StringResponse;
use aprendePHP\Application;

$dispatcher = new EventDispatcher();
$resolver = new ControllerResolver();

$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../templates');
$twig = new \Twig_Environment($loader);

$errorHandler = function (FlattenException $exception) use($twig){
  return $twig->render('error.html.twig',[
    'message' => $exception->getMessage(),
    'code' => $exception->getStatusCode()
  ]);
};

$dispatcher->addSubscriber(new ExceptionListener($errorHandler));
$dispatcher->addSubscriber(new StringResponse());

$app = new Application($dispatcher, $resolver);

return $app;
