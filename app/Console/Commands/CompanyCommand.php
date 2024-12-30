<?php

namespace App\Console\Commands;

/**
 * ===================================================
 * Használat
 * ===================================================
 *
 * Új cég létrehozása véletlenszerű adatokkal:
 * php artisan company create --random
 *
 * Tömeges generálás random adatokkal:
 * php artisan company create --random --count=5
 *
 * Új cég létrehozása megadott adatokkal:
 * php artisan company create '{"name": "My Company", "email": "mycompany@example.com", "address": "123 Main St"}'
 *
 * Létező cég módosítása megadott adatokkal:
 * php artisan company update '{"name": "Updated Company"}' --id=1
 *
 * Létező cég módosítása véletlenszerű adatokkal:
 * php artisan company update --random --id=1
 *
 * Létező cég törlése az id megadásával:
 * php artisan company delete --id=1
 * Több cég törlése:
 * php artisan company delete --id=1,2,3
 * Végleges törlés:
 * php artisan company delete --id=1 --force
 *
 * Soft Deleted Record Restore
 * php artisan company restore --id=1
 *
 * Listázás:
 * php artisan company list
 * php artisan company list --filter=quis  (directory)
 * php artisan company list --filter=Kozey (name)
 */

use Illuminate\Console\Command;
use App\Models\Company;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompanyCommand extends Command
{
    public function __construct()
    {
        $this->description = __('command_company_description');
        
        parent::__construct();
    }

    protected $signature = 'company {action : cég létrehozása, frissítése, törlése vagy listázása}
                    {data? : JSON formátumú adatok a vállalat számára}
                    {--id= : A cég azonosítója frissítéshez vagy törléshez}
                    {--random : Használja a Fakert véletlenszerű adatok generálására}
                    {--count=1 : A véletlenszerű adatokkal létrehozandó vállalatok száma (a --random szükséges)}
                    {--filter= : Vállalatok szűrése egy adott mező (pl. név) alapján}
                    {--force : A soft delete használata helyett véglegesen törölje a céget}
                    {--restore : A törölt cégek visszaállítása}';



    public function handle()
    {
        $action = $this->argument('action');
        $data = $this->argument('data');
        $companyId = $this->option('id');
        $count = max((int) $this->option('count'), 1);
        $random = $this->option('random');
        $filter = $this->option('filter');
        $ids = explode(',', $companyId);

        if ($action === 'create') {
            if ($random) {
                $companies = Company::factory()->count($count)->create();
                $this->info( __('command_company_random_created', ['count' => $count]) );
            } else {

                $validationRules = json_decode(file_get_contents(resource_path('js/Validation/ValidationRules.json')), true);

                $validator = Validator::make(json_decode($data, true), [
                    'name' => [
                        'required', 'string', 
                        "min:{$validationRules['minStringLength']}", 
                        "max:{$validationRules['maxStringLength']}",
                        Rule::unique('companies', 'name')
                    ],
                    'directory' => [
                        'required', 'string', 
                        "min:{$validationRules['minStringLength']}", 
                        "max:{$validationRules['maxStringLength']}",
                        Rule::unique('companies', 'directory')
                    ],
                    'registration_number' => 'required',
                    'tax_id' => 'required',
                    'country_id' => 'required',
                    'city_id' => 'required',
                    'address' => 'required',
                ]);

                if ($validator->fails()) {
                    $this->error( __('command_company_validate', ['error' => implode(', ', $validator->errors()->all())]) );
                    return;
                }

                $company = Company::create(json_decode($data, true));
                $this->info("command_company_created");
            }

        } elseif ($action === 'update') {
            $company = Company::findOrFail($companyId);
            $company->update(json_decode($data, true));
            $this->info(__('command_company_created', ['id' => $company->id]));

        } elseif ($action === 'delete') {

            if (!$companyId) {
                $this->error('You must provide a company ID or a comma-separated list of IDs to delete.');
                return;
            }

            // Törölni kívánt cégek azonosítóinak kezelése
            $ids = explode(',', $companyId);
            $companies = Company::whereIn('id', $ids)->get();

            if ($companies->isEmpty()) {
                $this->error(__('command_company_not_found_with_id'));
                return;
            }

            if( $this->option('force') ) {
                
                $companies->each(function (Company $company) {
                    // Törlés előtti adatmentés
                    $fileName = 'deleted_company_' . $company->id . '_' . now()->format('Ymd_His') . '.json';
                    Storage::put($fileName, json_encode($company->toArray(), JSON_PRETTY_PRINT));
        
                    // Force delete (végleges törlés)
                    //$company->forceDelete();
        
                    //$this->info("Company with ID {$company->id} has been permanently deleted, and data saved to storage/app/{$fileName}");
                    $this->info("Company with ID {$company->id} saved to storage/app/{$fileName}");
                });

                Company::whereIn('id', $ids)->forceDelete();
                $this->info('Companies permanently deleted: ' . implode(', ', $ids));

            } else {
                Company::whereIn('id', $ids)->delete();
                $this->info('Companies soft deleted: ' . implode(', ', $ids));
            }

            $this->info('All specified companies have been deleted successfully.');
        } elseif ($action === 'restore') {
            if (!$companyId) {
                $this->error('You must provide a company ID or a comma-separated list of IDs to restore.');
                return;
            }

            // Visszaállítandó cégek azonosítóinak kezelése
            $ids = explode(',', $companyId);

            // Visszaállítás soft delete-ből
            $companies = Company::onlyTrashed()->whereIn('id', $ids)->get();

            if ($companies->isEmpty()) {
                $this->error('No soft deleted companies found with the provided ID(s).');
                return;
            }

            $companies->each(function (Company $company) {
                $company->restore();
                $this->info("Company with ID {$company->id} has been restored from soft delete.");
            });
    
            $this->info('Restoration process completed for all specified IDs.');

        } elseif($action === 'force-restore') {

            if (!$companyId) {
                $this->error('You must provide a company ID or a comma-separated list of IDs to restore.');
                return;
            }
    
            // Visszaállítandó cégek azonosítóinak kezelése
            $ids = explode(',', $companyId);

            foreach ($ids as $id) {
                $pattern = 'deleted_company_' . $id . '_*.json';
                $files = Storage::files();

                // Keresés a megfelelő fájlra
                $file = collect($files)->first(function ($file) use ($pattern) {
                    return fnmatch($pattern, basename($file));
                });

                if (!$file) {
                    $this->error('No backup file found for company with ID ' . $id);
                    continue;
                }

                // JSON fájl tartalmának beolvasása és visszaállítása
                $companyData = json_decode(Storage::get($file), true);

                // Ha létezik a cég a soft delete miatt, akkor visszaállítjuk a modellt, különben újra létrehozzuk.
                $company = Company::withTrashed()->find($id);
                if ($company) {
                    $company->restore();
                    $this->info('Company with ID ' . $id . ' has been restored from soft delete.');
                } else {
                    Company::create($companyData);
                    $this->info('Company with ID ' . $id . ' has been recreated from backup data.');
                }
            }

            $this->info('Restoration process completed for all specified IDs.');

        } elseif ($action == 'list') {
            $query = Company::query();
            if ($filter) {
                $query->where('name', 'like', "%{$filter}%")
                    ->orWhere('directory', 'like', "%{$filter}%");
            }

            $companies = $query->get(['id', 'name', 'directory', 'active']);

            if ($companies->isEmpty()) {
                $this->info('No companies found.');
            } else {
                $this->table(['ID', 'Name', 'Directory', 'Active'], $companies);
            }

        }
    }
}