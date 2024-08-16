<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('cities')->truncate();
        
        $cities = [
            ['id' => 955428, 'region_id' => 1555, 'country_id' => 92, 'latitude' => '47.50000000', 'longitude' => '19.08333330', 'name' => 'Budapest',],
            ['id' => 958977, 'region_id' => 1562, 'country_id' => 92, 'latitude' => '47.68333330', 'longitude' => '17.63333330', 'name' => 'Győr',],
            ['id' => 961049, 'region_id' => 1545, 'country_id' => 92, 'latitude' => '47.68333330', 'longitude' => '17.78333330', 'name' => 'Kecskemét',],
            ['id' => 961048, 'region_id' => 1548, 'country_id' => 92, 'latitude' => '46.90000000', 'longitude' => '19.78333330', 'name' => 'Kecskemét',],
            ['id' => 968570, 'region_id' => 1539, 'country_id' => 92, 'latitude' => '47.20000000', 'longitude' => '18.41666670', 'name' => 'Székesfehérvár',],
        ];
        
        foreach($cities as $city)
        {
            \App\Models\City::create($city);
        }
    }
}
