<?php

namespace Database\Seeders;

use App\Models\Calendar;
use App\Models\Entity;
use App\Models\EntityCalendar;
use App\Repositories\CalendarRepository;
use App\Services\CacheService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class EntityCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        EntityCalendar::truncate();
        Schema::enableForeignKeyConstraints();

        activity()->disableLogging();

        $year = now()->year;
        $now = now();
        $batchSize = 1000;
        $bulkInsertData = [];

        $entities = Entity::pluck(column: 'id')->toArray();
        $dates = Calendar::whereYear(column: 'date', operator: $year)->get(columns: ['id', 'date']);

        foreach ($entities as $entityId) {
            foreach ($dates as $date) {
                $bulkInsertData[] = [
                    'entity_id' => $entityId,
                    'calendar_id' => $date->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // **Ha elértük a batch limitet, beszúrjuk az adatokat és kiürítjük a tömböt**
                if (count($bulkInsertData) >= $batchSize) {
                    EntityCalendar::insert($bulkInsertData);
                    $bulkInsertData = []; // Kiürítjük a memóriát
                }
            }
        }

        if (!empty($bulkInsertData)) {
            EntityCalendar::insert($bulkInsertData);
        }
        
        activity()->enableLogging();
    }
}
