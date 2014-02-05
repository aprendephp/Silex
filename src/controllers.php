<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/', function () use ($app) {
    return $app['twig']->render('layout.html', array());
  })
  ->bind('homepage')
;

$app->get('/api/beers', function() use($app){
	$beers = $app['db']->fetchAll("SELECT * FROM beers");
	return $app->json($beers);
});

$app->get('/api/beers/{code}', function($code) use($app){

	$beer = $app['db']->fetchAssoc("SELECT * FROM beers WHERE code='$code'");
	return $app->json($beer);
});

$app->post('/api/beers/add', function(Request $request) use($app){

	$imagen = $request->files->get('image');
	$image_name = $imagen->getClientOriginalName();
	$imagen->move( __DIR__ . '/../web/images', $image_name);
	
	$beer = [
		'code' => $request->request->get('code'),
		'name' => $request->request->get('name'),
		'type' => $request->request->get('type'),
		'country' => $request->request->get('country'),
		'description' => $request->request->get('description'),
		'image' => $image_name,
	];

	$insert = $app['db']->insert('beers',$beer);

	if ($insert){
		return $app->json([
			'status' => 200
		]);
	}
	else{
		return $app->json([
			'status' => 403
		]);
	}

});

$app->get('/api/page/{page}', function($page){

	if ($page == 'home'){
		return "Bienvenido";
	}

});

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }
    $page = 404 == $code ? '404.html' : '500.html';
    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
