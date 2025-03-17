<?php

namespace App\Services\Calendar;

use App\Repositories\EntityCalendarRepository;

class EntityCalendarService
{
    protected EntityCalendarRepository $entityCalendarRepository;

    public function __construct(EntityCalendarRepository $entityCalendarRepository)
    {
        $this->entityCalendarRepository = $entityCalendarRepository;
    }
}