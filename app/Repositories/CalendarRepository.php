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

    /**
     * Egy adott évhez tartozó naptári dátumok tömbjét kéri le.
     *
     * @param int $year  Az év, amelyre vonatkozóan a naptári dátumokat le kell kérni.
     * @return array     Az adott évnek megfelelő dátumok tömbje.
     * @throws Exception Ha hiba történik a visszakeresési folyamat során.
     */
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

    /**
     * Lekéri a naptári dátumok tömbjét egy adott hónapra és évre vonatkozóan.
     *
     * @param int $month  Az év hónapja.
     * @param int $year   Az év, amelyre vonatkozóan a dátumokat le kell kérni.
     * @return array      Dátumokból álló tömb, amely a megadott hónapra és évben
     *                    található naptári dátumokat tartalmazza.
     * @throws Exception  Kivétel Ha hiba történik a visszakeresés során.
     */
    public function getCalendarByMonth(int $year, int $month): array
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode(['year' => $year, 'month' => $month]));

            return $this->cacheService->remember($this->tag, $cacheKey, fn() => Calendar::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->orderBy('date', 'asc')
                ->pluck('date')->toArray()
            );
            
        } catch( Exception $ex ) {
            $this->logError($ex, 'getCalendarByMonth error', ['year' => $year, 'month' => $month]);
            throw $ex;
        }
    }
    
    /**
     * Lekéri a naptári dátumok tömbjét egy adott hétre és évre vonatkozóan.
     *
     * @param int $week  Az ISO-8601 hét száma.
     * @param int $year  Az év, amelyre vonatkozóan a dátumokat le kell kérni.
     * @return array     Dátumokból álló tömb, amely a megadott hétre és évre esik.
     * @throws Exception Kivétel Ha hiba történik a visszakeresés során.
     */
    public function getCalendarByWeek(int $year, int $week): array
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
    
    /**
     * Egy adott napra, hónapra és évre vonatkozó naptári dátumok tömbjét kéri le.
     *
     * @param int $day   A hónap napja.
     * @param int $month Az év hónapja.
     * @param int $year  Az év a dátumok lekéréséhez.
     * @return array     A megadott napnak, hónapnak és évnek megfelelő dátumok tömbje.
     * @throws Exception Ha hiba történik a visszakeresési folyamat során.
     */
    public function getCalendarByDay(int $day, int $month, int $year): array
    {
        try {
            return Calendar::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->whereDay('date', $day)
                ->pluck('date')
                ->toArray();
        } catch(Exception $ex) {
            $this->logError($ex, 'getCalendarByDay error', ['year' => $year, 'month' => $month, 'day' => $day]);
            throw $ex;
        }
    }

    /**
     * A megadott dátumtartományon belülre eső naptárbejegyzések lekérdezése.
     *
     * @param string $startDate A tartomány kezdő dátuma "Y-m-d" formátumban.
     * @param string $endDate   A tartomány befejezési dátuma "Y-m-d" formátumban.
     * @return array            Dátumok tömbje a megadott tartományon belül.
     * @throws Exception        Kivétel Ha hiba történik a visszakeresés során.
     */
    public function getCalendarByRange(string $startDate, string $endDate): array
    {
        try {
            return Calendar::whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'asc')
                ->pluck('date')->toArray();
        } catch( Exception $ex ) {
            $this->logError($ex, 'getCalendarByRange error', ['startDate' => $startDate, 'endDate' => $endDate]);
            throw $ex;
        }
    }

    /**
     * Egy adott dátumhoz tartozó naptárbejegyzést kér le.
     *
     * @param string $date      A naptárbejegyzés lekérésének dátuma „Y-m-d” formátumban.
     * @return Calendar|null    A megadott dátum naptárbejegyzése, vagy nulla, ha nem található.
     * @throws Exception        Ha hiba történik a visszakeresés során.
     */
    public function getCalendarByDate(string $date): ?Calendar
    {
        try {
            return Calendar::where('date', $date)->first();
        } catch(Exception $ex) {
            $this->logError($ex, 'getCalendarByDate error', ['date' => $date]);
            throw $ex;
        }
    }

    /**
     * Lekéri a naptári dátumok tömbjét egy adott hét és év egy adott napjára vonatkozóan.
     *
     * @param int $weekday  A hét napja (0 a hétfő, 6 a vasárnap).
     * @param int $week     Az ISO-8601 hét száma.
     * @param int $year     Az év, amelyre vonatkozóan a dátumokat le kell kérni.
     * @return array        Dátumokból álló tömb, amely a megadott hét és év meghatározott napjára esik.
     * @throws Exception    Kivétel Ha hiba történik a visszakeresés során.
     */
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

    
    /**
     * Egy adott dátumtartományon belüli naptári dátumok tömbjét kéri le
     * ahol a hét napja a kezdő és befejező dátum hétköznapjai közé esik.
     *
     * @param string        $startDate A tartomány kezdő dátuma „Y-m-d” formátumban.
     * @param string        $endDate A tartomány befejezési dátuma „Y-h-d” formátumban.
     * @return array        Dátumok tömbje a megadott tartományon belül és a hétköznapokon belül.
     * @throws Exception    Kivétel Ha hiba történik a visszakeresés során.
     */
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
