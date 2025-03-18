<?php

namespace App\Http\Controllers\Entity;

use App\Models\EntityCalendar;
use App\Services\Calendar\EntityCalendarService;
use App\Traits\Functions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class EntityCalendarController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected EntityCalendarService $entityCalendarService;

    protected string $tag = '';

    public function __construct(EntityCalendarService $entityCalendarService)
    {
        $this->entityCalendarService = $entityCalendarService;

        $this->tag = EntityCalendar::getTag();
    }

    public function index()
    {
        return Inertia::render('EntityCalendar/Index');
    }

    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function($query, $search){
            $query->where('', '', '');
        });
    }
}
