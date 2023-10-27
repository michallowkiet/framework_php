<?php
require_once __DIR__ ."./../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;


$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
  extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
  ob_start();
  include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

  $response = new Response(ob_get_clean());
} catch (ResourceNotFoundException $e) {
  $response = new Response('Not Found', Response::HTTP_NOT_FOUND);
} catch (Exception $e) {
  $response = new Response('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
}

$response->send();