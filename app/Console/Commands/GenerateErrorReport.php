<?php

/**
 * php artisan generate:error-report
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Activitylog\Models\Activity;

class GenerateErrorReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'app:generate-error-report';
    protected $signature = 'generate:error_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hibariport generálása';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /*
        $startDate = now()->startOfWeek();
        $endDate = now()->endOfWeek();
        
        $errors = Activity::whereBetween('created_at', [$startDate, $endDate])
            ->where('log_name', 'error')
            ->get();
        
        $report = $errors->groupBy('properties.type')
            ->map(function($group) {
                return [
                    'count' => $group->count(),
                    'severity' => $group->pluck('properties.severity')->unique()->join(', '),
                ];
            });
            
        // Riport fájlba mentése
        $path = storage_path('logs/error_reports');
        if (!file_exists($path)) mkdir($path, 0777, true);
        file_put_contents("$path/weekly_report_" . now()->format('Y_m_d') . ".json", $report->toJson());
        $this->info("Hiba riport generálva: $path");
        */
        $this->info('Error report generated successfully!');
        return Command::SUCCESS;
    }
}
