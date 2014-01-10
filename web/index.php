<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;

$response = new Response();
$request = Request::createFromGlobals();

$app = new aprendePHP\Application($response, $request);

$app->get('/', function() use($request){

	if ($request->cookies->get('colaborador') == 'on')
		return "Gracias por tu aporte";
	else
		return "Bienvenido";
});

$app->get('/contacto', function(){ 
	return "contacto"; 
});

$app->get('/quienes', function(){ 
	return new RedirectResponse('contacto'); 
});

$app->post('/enviar-email', function(){
	return "Email enviado";
});

$app->post('/beer/add', function() use($request){
	$name = $request->request->get('name');
	$image = $request->files->get('image');
	$image->move(__DIR__ . '/uploads', $image->getClientOriginalName());
	
	$r = new RedirectResponse('/web/index.php');
	$r->headers->setCookie(new Cookie('colaborador','on'));
	return $r;
});


$app->put('/beer/update', function(){ return "update"; });
$app->delete('/beer/delete', function(){ return "delete"; });

$app->run();





