<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        //\DB::table('cities')->truncate();
        City::truncate();

        Schema::enableForeignKeyConstraints();

        $cities = [
            ['id' => 955428, 'region_id' => 1555, 'country_id' => 92, 'latitude' => '47.50000000', 'longitude' => '19.08333330', 'name' => 'Budapest',       'active' => 1],
            ['id' => 958977, 'region_id' => 1562, 'country_id' => 92, 'latitude' => '47.68333330', 'longitude' => '17.63333330', 'name' => 'Győr',           'active' => 1],
            ['id' => 961049, 'region_id' => 1545, 'country_id' => 92, 'latitude' => '47.68333330', 'longitude' => '17.78333330', 'name' => 'Kecskemét',      'active' => 1],
            ['id' => 961048, 'region_id' => 1548, 'country_id' => 92, 'latitude' => '46.90000000', 'longitude' => '19.78333330', 'name' => 'Kecskemét',      'active' => 1],
            ['id' => 955545, 'region_id' => 1546, 'country_id' => 92, 'latitude' => '47.16666670', 'longitude' => '19.80000000', 'name' => 'Cegléd',         'active' => 1],
            ['id' => 964383, 'region_id' => 1556, 'country_id' => 92, 'latitude' => '48.10000000', 'longitude' => '20.78333330', 'name' => 'Miskolc',         'active' => 1],
            ['id' => 966284, 'region_id' => 1561, 'country_id' => 92, 'latitude' => '46.08333330', 'longitude' => '18.23333330', 'name' => 'Pécs',           'active' => 1],
            ['id' => 968014, 'region_id' => 1545, 'country_id' => 92, 'latitude' => '47.68333330', 'longitude' => '16.60000000', 'name' => 'Sopron',         'active' => 1],
            ['id' => 968570, 'region_id' => 1539, 'country_id' => 92, 'latitude' => '47.20000000', 'longitude' => '18.41666670', 'name' => 'Székesfehérvár', 'active' => 1],
            ['id' => 969175, 'region_id' => 1549, 'country_id' => 92, 'latitude' => '47.23333330', 'longitude' => '16.61666670', 'name' => 'Szombathely', 'active' => 1],
            ['id' => 971009, 'region_id' => 1547, 'country_id' => 92, 'latitude' => '47.10000000', 'longitude' => '17.91666670', 'name' => 'Veszprém', 'active' => 1],
        ];

        foreach($cities as $city)
        {
            \App\Models\City::create($city);
        }
    }
}
