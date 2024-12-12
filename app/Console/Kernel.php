<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * A console command-ek ütemezése.
     * 
     * futtatás: php artisan schedule:run
     * 
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // Példa egy parancs időzítésére
        //$schedule->command('example:command')->dailyAt('02:00');
        
        // Heti hiba riport készítése
        $schedule->command('generate:error-report')->weeklyOn(1, '03:00');
    }

    /**
     * A console artisan parancsok regisztrálása.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
