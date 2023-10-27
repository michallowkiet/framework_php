<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
require_once __DIR__ ."./../vendor/autoload.php";


$request = Request::createFromGlobals();
$response = new Response();

$routes = [
  '/hello' => __DIR__ .'./../src/pages/hello.php',
  '/bye' => __DIR__ .'./../src/pages/bye.php',
];

$path = $request->getPathInfo();

if (isset($routes[$path])) {
  ob_start();
  require $routes[$path];
  $response->setContent(ob_get_clean());
} else {
  $response->setStatusCode(Response::HTTP_NOT_FOUND);
  $response->setContent('Not Found');
}

$response->send();