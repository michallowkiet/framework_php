<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function index(int $year): Response
    {
        if (isLeapYear($year)) {
            return new Response('Tak, jest to rok przestępny');
        }

        return new Response('Nie jest to rok przestępny');
    }
}
