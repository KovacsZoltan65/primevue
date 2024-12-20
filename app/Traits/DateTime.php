<?php

/**
 * Dátum függvények
 * 
 * @author Kovács Zoltán <zoltan1_kovacs@msn.com>
 * @date 2023-08-01
 */

namespace App\Traits;

use Carbon\Carbon;

trait DateTime
{
    protected $format = 'Y-m-d H:i:s';

    protected $localization = 'hu';

    protected $timezone_name = 'Europe/Budapest';

    public function actualDate(string $format = null): string
    {
        if( $format === null ) { $format = $this->format; }
        
        return Carbon::now()->format($format);
    }

    /**
     * ============================================
     * Hónapok
     * ============================================
     */

     // aktuális hónap kezdete
    public function startThisMonth(string $format = null): string
    {
        if( $format === null ) { $format = $this->format; }
        
        return Carbon::now()->startOfMonth()->format($format);
    }

    // aktuális hónap vége
    public function endOfThisMonth(string $format = null): string
    {
        if( $format === null ) { $format = $this->format; }
        
        return Carbon::now()->endOfMonth()->format($format);
    }

    // előző hónap kezdete
    public function startLastMonth(string $format = null): string
    {
        if( $format === null ) { $format = $this->format; }
        
        return Carbon::now()->subMonth()->startOfMonth()->format($format);
    }

    // előző hónap vége
    public function endOfLastMonth(string $format = null): string
    {
        if( $format === null ) { $format = $this->format; }
        
        return Carbon::now()->subMonth()->endOfMonth()->format($format);
    }
    
    public function getTranslatedMonthName(string $date): string
    {
        return Carbon::parse($date)
            ->locale($this->localization)
            ->getTranslatedMonthName();
    }
    
    /**
     * ============================================
     * Hetek
     * ============================================
     */
    public function startOfWeek(string $date): string
    {
        return Carbon::parse($date)->locale($this->localization)->startOfWeek();
    }
    
    public function endOfWeek(string $date): string
    {
        return Carbon::parse($date)->locale($this->localization)->endOfWeek();
    }
    
    public function startLastWeek(string $format = null): string
    {
        if( $format === null ) { $format = $this->format; }
        
        return Carbon::now()->subWeek()->startOfWeek()->format($format);
    }

    public function endOfLastWeek(string $format = null): string
    {
        if( $format === null ) { $format = $this->format; }
        
        return Carbon::now()->subWeek()->endOfWeek()->format($format);
    }

    /**
     * ============================================
     * Napok
     * ============================================
     */


    public function aa()
    {
        Carbon::parse('')->getDays();
    }
    
    public function getTranslatedDayName(string $date): string
    {
        return Carbon::parse($date)
            ->locale($this->localization)
            ->getTranslatedDayName();
    }
    
    /**
     * ============================================
     * Összehasonlítás
     * ============================================
     */
    
    public function compareDates(string $date_01, string $date_02, string $format = null): bool
    {
        if( $format === null ){ $format = $this->format; }

        $d_date_01 = Carbon::createFromFormat($format, $date_01, $this->timezone_name);
        $d_date_02 = Carbon::createFromFormat($format, $date_02, $this->timezone_name);

        return $d_date_01->equalTo($d_date_02);
    }

    /**
     * ============================================
     * Vizsgálatok
     * ============================================
     */
    
    public function isWeekday(string $date): bool
    {
        return Carbon::parse($date)->isWeekday();
    }

    public function isWeekend(string $date, string $localization = null): bool
    {
        return Carbon::parse($date)->isWeekend();
    }

    public function isDate(string $date, string $format): bool
    {
        return \DateTime::createFromFormat($format, $date) !== false;
    }
}
