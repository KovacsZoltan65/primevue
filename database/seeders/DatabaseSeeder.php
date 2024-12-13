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

            CountriesTableSeeder::class,    // - Ne változtasd meg a sorrendet!!
            RegionsTableSeeder::class,      // - Ne változtasd meg a sorrendet!!
            CitySeeder::class,              // - Ne változtasd meg a sorrendet!!

            //CompanySeeder::class,           // - Ne változtasd meg a sorrendet!!

            SubdomainStateSeeder::class,    // - Ne változtasd meg a sorrendet!!
            SubdomainsSeeder::class,        // - Ne változtasd meg a sorrendet!!

            //PersonSeeder::class,            // - Ne változtasd meg a sorrendet!!
            //EntitySeeder::class,            // - Ne változtasd meg a sorrendet!!
            PersonCompanyEntitySeeder::class,

            MenuItemSeeder::class,
            MenuItemEntitiesSeeder::class,
            
            RoleAndPermissionSeeder::class,

            ApplicationSettingSeeder::class,

        ]);

        // User::factory(10)->create();

        //User::factory()->create([
        //    'name' => 'Test User',
        //    'email' => 'test@example.com',
        //]);
    }
}
