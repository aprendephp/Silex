<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$response = new Response();
$request = Request::createFromGlobals();

$app = new aprendePHP\Application($response, $request);

return $app;