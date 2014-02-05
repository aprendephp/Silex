<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$response = new Response();
$request = Request::createFromGlobals();

$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../templates');
$twig = new \Twig_Environment($loader);

$app = new aprendePHP\Application($response, $request);

return $app;