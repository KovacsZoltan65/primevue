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

    public function getCalendarByYear(int $year): array
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $year);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($year) {
                return Calendar::whereYear('date', $year)
                    ->orderBy('date', 'asc')
                    ->pluck('date')->toArray();
            });
        } catch (Exception $ex) {
            $this->logError($ex, 'getCalendarByYear error', ['year' => $year]);
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
        try {
            return Calendar::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->whereDay('date', $day)
                ->pluck('date')
                ->toArray();
        } catch(Exception $ex) {
            $this->logError($ex, 'getCalendarByWeek error', ['year' => $year, 'month' => $month, 'day' => $day]);
            throw $ex;
        }
    }
    public function getCalendarByRange(string $startDate, string $endDate): array
    {
        try {
            return Calendar::whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'asc')
                ->pluck('date')->toArray();
        } catch( Exception $ex ) {
            $this->logError($ex, 'getCalendarByWeek error', ['startDate' => $startDate, 'endDate' => $endDate]);
            throw $ex;
        }
    }
    public function getCalendarByDate(string $date): ?Calendar
    {
        try {
            return Calendar::where('date', $date)->first();
        } catch(Exception $ex) {
            $this->logError($ex, 'getCalendarByWeek error', ['date' => $date]);
            throw $ex;
        }
    }
    public function getCalendarByWeekday(int $weekday, int $week, int $year): array
    {
        try {
            return Calendar::whereYear('date', $year)
                ->whereRaw('WEEKDAY(date) =?', [$weekday])
                ->whereRaw('WEEK(date, 1) =?', [$week])
                ->orderBy('date', 'asc')
                ->pluck('date')->toArray();
        } catch( Exception $ex ) {
            $this->logError($ex, 'getCalendarByWeekday error', ['weekday' => $weekday, 'week' => $week, 'year' => $year]);
            throw $ex;
        }
    }
    public function getCalendarByWeekdayRange($startDate, $endDate): array
    {
        try {
            return Calendar::whereBetween('date', [$startDate, $endDate])
               ->whereRaw('WEEKDAY(date) BETWEEN ? AND ?', [
                   (date('w', strtotime($startDate)) + 6) % 7,
                   (date('w', strtotime($endDate)) + 6) % 7
               ])
               ->orderBy('date', 'asc')
               ->pluck('date')
               ->toArray();
        } catch(Exception $ex) {
            $this->logError($ex, 'getCalendarByWeekdayRange error', ['startDate' => $startDate, 'endDate' => $endDate]);
            throw $ex;
        }
    }
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
