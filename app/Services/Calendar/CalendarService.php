<?php

namespace App\Services\Calendar;

use App\Repositories\CalendarRepository;
use Exception;

class CalendarService
{
    protected CalendarRepository $calendarRepository;

    public function __construct(CalendarRepository $calendarRepository)
    {
        $this->calendarRepository = $calendarRepository;
    }

    public function getCalendarByYear(int $year): array
    {
        try {
            return $this->calendarRepository->getCalendarByYear(year: $year);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function getCalendarByMonth(int $year, int $month): array
    {
        try {
            return $this->calendarRepository->getCalendarByMonth(year: $year, month: $month);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function getCalendarByWeek(int $year, int $week): array
    {
        try {
            return $this->calendarRepository->getCalendarByWeek($year, $week);
        } catch(Exception $ex) {
            throw $ex;
        }
    }
}