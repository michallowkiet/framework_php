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
            return new Response('Tak, jest to rok przestępny');
        }

        return new Response('Nie jest to rok przestępny');
    }
}
