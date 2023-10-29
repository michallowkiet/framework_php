<?php

namespace Calendar\Controller;

use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function index(int $year): Response
    {
        $leaYear = new LeapYear();

        if ($leaYear->isLeapYear($year)) {
            $response = new Response('Tak, jest to rok przestępny');
        } else {
            $response = new Response('Nie jest to rok przestępny'.rand());
        }

        $response->setTtl(10);

        return $response;
    }
}
