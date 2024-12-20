<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;


class CityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'city {action : create or update a city }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $data = $this->argument('data');
        $cityId = $this->option('id');
        $count = max((int) $this->option('count'), 1);
        $random = $this->option('random');
        $filter = $this->option('filter');
        $ids = explode(',', $cityId);

        if ($action === 'create') {
            if ( $random ) {
                $cities = City::factory()->count($count)->create();
                $this->info( __('command_city_random_created', ['count' => $count]) );
            } else {
                $validationRules = json_decode(file_get_contents(resource_path('js/Validation/ValidationRules.json')), true);
                $validator = Validator::make(json_decode($data, true), [
                    'region_id' => ['required'], 
                    'country_id' => ['required'], 
                    'latitude' => ['decimal'], 
                    'longitude' => ['decimal'], 
                    'name' => ['required']
                ]);

                if( $validator->fails() ) {
                    $this->error( __('command_city_validate', ['error' => implode(', ', $validator->errors()->all())]) );
                    return;
                }

                $company = City::create(json_decode($data, true));
                $this->info("");
            }
        } elseif( $action === 'update' ) {
            //
        } elseif( $action === 'delete' ) {
            //
        } elseif( $action === 'restore' ) {
            //
        } elseif( $action === 'force-restore' ) {
            //
        } elseif( $action === 'list' ) {
            //
        }
    }
}
