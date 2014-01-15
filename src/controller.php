<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;

$app->get('/', function(Request $request) use($twig){
	if ($request->cookies->get('colaborador') == 'on')
		$mensaje = 'Gracias por colaborar';
	else
		$mensaje = 'Bienvenido';

	return $twig->render('index.html.twig',['mensaje'=>$mensaje]);
});

$app->get('/hello/{name}',function($name) use($twig){
	return $twig->render('hello.html.twig',[
		'name'=> $name
	]);
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

$app->post('/beer/add', function(Request $request) {
	$name = $request->request->get('name');
	$image = $request->files->get('image');
	$image->move(__DIR__ . '/uploads', $image->getClientOriginalName());

	$r = new RedirectResponse('/web/index.php');
	$r->headers->setCookie(new Cookie('colaborador','on'));
	return $r;
});

$app->put('/beer/update', function(){ return "update"; });
$app->delete('/beer/delete', function(){ return "delete"; });
