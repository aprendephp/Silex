<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;

$app->get('/', function() use($request){
	if ($request->cookies->get('colaborador') == 'on')
		return "Gracias por tu aporte";
	else
		return "Bienvenido";
});

$app->get('/hello/{name}/{lastname}',function($name, $lastname){
	return "Hola " . $name . ' ' . $lastname . '<br>attributes: ';
});

$app->get('/noticias/{year}/{category}/{slug}',function ($year, $category, $slug){
	$texto = "year: " . $year . "<br>";
	$texto .= "category " . $category . "<br>";
	$texto .= "slug " . $slug . "<br>"; 

	return $texto;
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