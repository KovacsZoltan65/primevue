<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Services\Calendar\CalendarService;
use App\Traits\Functions;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Laravel\Telescope\AuthorizesRequests;

class CalendarController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected CalendarService $calendarService;

    protected string $tag = '';

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;

        $this->tag = Calendar::getTag();
    }

    public function index()
    {
        return Inertia::render('Calendar/Index');
    }

    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function ($query, string $search) {
            $query->where('date', 'like', "%{$search}%");
        });
    }

    public function getCalendarByYear(int $year): JsonResponse
    {
        try {
            $calendar = $this->calendarService->getCalendarByYear(year: $year);
            return response()->json($calendar, Response::HTTP_OK);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getCalendarByYear query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getCalendarByYear general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCalendarByMonth(int $year, int $month): JsonResponse
    {
        try {
            $calendar = $this->calendarService->getCalendarByMonth(year: $year, month: $month);
            return response()->json($calendar, Response::HTTP_OK);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getCalendarByMonth query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getCalendarByMonth general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCalendarByWeek(int $year, int $week): JsonResponse
    {
        try {
            $calendar = $this->calendarService->getCalendarByWeek(year: $year, week: $week);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getCalendarByWeek query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getCalendarByWeek general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
