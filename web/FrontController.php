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
  $request->attributes->add($matcher->match($request->getPathInfo()));

  $response = call_user_func($request->attributes->get('_controller'), $request);
} catch (ResourceNotFoundException $e) {
  $response = new Response('Not Found', Response::HTTP_NOT_FOUND);
} catch (Exception $e) {
  $response = new Response('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
}

$response->send();