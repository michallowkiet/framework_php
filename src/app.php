<?php

use App\Controllers\LeapYearController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

function isLeapYear(int $year = null): bool
{
    if ($year === null) {
        $year = (int) date('Y');
    }

    return 0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100);
}

$routes = new RouteCollection();

$routes->add('leap-year', new Route('/is-leap-year/{year}', [
  'year' => null,
  '_controller' => [new LeapYearController(), 'index'],
]));

return $routes;
