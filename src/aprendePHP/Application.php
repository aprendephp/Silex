<?php

namespace aprendePHP;

class Application {

  public function index(){

    $uri = $_SERVER['REQUEST_URI'];
    $foo = $_GET['foo'];

    header('Content-type: text/html');
    echo 'La URI solicitada es: '.$uri . '<br>';
    echo 'El valor del parametro "foo" es: '.$foo;
  }

}
