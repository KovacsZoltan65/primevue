<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Activitylog\Models\Activity;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,

            CountriesTableSeeder::class,        // - Ne változtasd meg a sorrendet!!
            RegionsTableSeeder::class,          // - Ne változtasd meg a sorrendet!!
            CitySeeder::class,                  // - Ne változtasd meg a sorrendet!!

            CompanySeeder::class,             // - Ne változtasd meg a sorrendet!!

            SubdomainStateSeeder::class,        // - Ne változtasd meg a sorrendet!!
            ACSSeeder::class,                   // - Ne változtasd meg a sorrendet!!
            SubdomainsSeeder::class,            // - Ne változtasd meg a sorrendet!!

            // Összevonva az users -el
            //PersonSeeder::class,              // - Ne változtasd meg a sorrendet!!

            EntitySeeder::class,              // - Ne változtasd meg a sorrendet!!
            //CompanyEntitySeeder::class,
            HierarchySeeder::class,

            MenuItemSeeder::class,

            RoleAndPermissionSeeder::class,

            AppSettingSeeder::class,
            CompSettingSeeder::class,

            SettingsMetadataSeeder::class,

            // Műszak típusok
            ShiftTypeSeeder::class,
            // Műszakok
            EntityShiftSeeder::class,

        ]);

        // User::factory(10)->create();

        //User::factory()->create([
        //    'name' => 'Test User',
        //    'email' => 'test@example.com',
        //]);
    }
}
