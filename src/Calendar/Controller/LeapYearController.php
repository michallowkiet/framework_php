<?php

namespace Calendar\Controller;

use Calendar\Model\LeapYear;

class LeapYearController
{
    public function index(int $year): string
    {
        $leaYear = new LeapYear();

        if ($leaYear->isLeapYear($year)) {
        }

        return 'Nie jest to rok przestępny';
    }
}
