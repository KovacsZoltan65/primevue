<?php

namespace App\Repositories;

use App\Interfaces\CalendarRepositoryInterface;
use App\Services\CacheService;
use App\Traits\Functions;
use Exception;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Calendar;


/**
 * Class CalendarRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CalendarRepository extends BaseRepository implements CalendarRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'calendars';
    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
        $this->tag = Calendar::getTag();
    }

    public function getCalendarByYear(Request $request): array
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->year));

            return $this->cacheService->remember($this->tag, $cacheKey, function() use($request->year) {
                $calendarQuery = Calendar::search($request->year);
                return $calendarQuery->get();
            });
        } catch (Exception $ex) {
            $this->logError($ex, 'getCalendarByYear error', ['year' => $request->year]);
            throw $ex;
        }
    }

    public function getCalendarByMonth(int $month, int $year): array
    {
        try {
            return Calendar::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->orderBy('date', 'asc')
                ->pluck('date')->toArray();
        } catch( Exception $ex ) {
            $this->logError($ex, 'getCalendarByMonth error', ['year' => $year, 'month' => $month]);
            throw $ex;
        }
    }
    public function getCalendarByWeek(int $week, int $year): array
    {
        try {
            return Calendar::whereYear('date', $year)
                ->whereRaw('WEEK(date, 1) = ?', [$week])
                ->orderBy('date', 'asc')
                ->pluck('date')->toArray();
        } catch( Exception $ex ) {
            $this->logError($ex, 'getCalendarByWeek error', ['year' => $year, 'week' => $week]);
            throw $ex;
        }
    }
    public function getCalendarByDay(int $day, int $month, int $year): array
    {
        return Calendar::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereDay('date', $day)
            ->pluck('date')
            ->toArray();
    }
    public function getCalendarByRange($startDate, $endDate): array{}
    public function getCalendarByDate($date): Calendar{}
    public function getCalendarByWeekday($weekday, $week, $year){}
    public function getCalendarByWeekdayRange($startDate, $endDate){}
    public function createCalendars(int $year){}


    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Calendar::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
