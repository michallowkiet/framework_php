<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('leap-year', new Route('/is-leap-year/{year}', [
  'year' => null,
  '_controller' => 'Calendar\Controller\LeapYearController::index',
]));

return $routes;
