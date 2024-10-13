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

class CompanyCommand extends Command
{
    protected $description = 'Create, update, or delete a company with provided data or random values.';

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
        // A művelet, amit végrehajtunk: create, update, vagy delete
        $action = $this->argument('action');

        // A JSON formátumú adatok, amiket a vállalat számára használunk
        $data = $this->argument('data');

        // Használja a Fakert véletlenszerű adatok generálására
        $random = $this->option('random');

        // A cég azonosítója frissítéshez vagy törléshez
        $companyId = $this->option('id');

        // Dekódolja a JSON-adatokat, ha vannak
        try {
            $companyData = $data ? json_decode($data, true) : [];
        } catch( \Exception $e ) {
            $this->error('Invalid JSON format for the data parameter.');
            return;
        }

        if ($action === 'create') {

            $count = max((int) $this->option('count'), 1);

            if( $random ) {
                // Faker példányosítása a véletlenszerű adatok generálásához
                $faker = Faker::create();

                $companyData = array_merge($companyData, [
                    'name' => $companyData['name'] ?? $faker->company,
                    'email' => $companyData['email'] ?? $faker->unique()->safeEmail,
                    'address' => $companyData['address'] ?? $faker->address,
                    // Szükség szerint adjon hozzá további mezőket
                ]);

                $companies = Company::factory()->count($count)->create();
                $this->info($count . ' company/companies created with random data successfully.');
            } else {
                // Create a single company with specified data
                $company = Company::create($companyData);

                $this->info('Company created successfully with provided data: ID ' . $company->id);
            }

        } elseif ($action === 'update') {

            // Győződjön meg arról, hogy az azonosítót megadta a frissítéshez
            if (!$companyId) {
                $this->error('You must provide a company ID to update.');
                return;
            }

            // Keresse meg a céget a megadott azonosító alapján
            // Ha az azonosító nem található, hibaüzenettel lépjen ki
            $company = Company::find($companyId);

            // Ellenőrzi, hogy létezik-e a cég a megadott azonosítóval
            // Ha nem található, hibaüzenettel lépjen ki
            if (!$company) {
                $this->error('Company not found with the provided ID.');
                return;
            }

            // Frissítse a céget a megadott adatokkal
            $company->update($companyData);
            $this->info("Company updated successfully with ID: {$company->id}");

        } elseif($action === 'delete') {

            // Győződjön meg arról, hogy az azonosítót megadták a törléshez
            if (!$companyId) {
                $this->error('You must provide a company ID to delete.');
                return;
            }

            // Az azonosítókat szét kell bontani, mert a felhasználó több cég azonosítóját is megadhatja
            // vesszővel elválasztva
            $ids = explode(',', $companyId);

            // Azokat a cégeket kell megtalálni, amelyeknek az azonosítóját a megadták
            $companies = Company::whereIn('id', $ids)->get();

            // Ellenőrzi, hogy léteznek-e a cégek a megadott azonosítóval
            // Ha nem található, hibaüzenettel lépjen ki
            if ($companies->isEmpty()) {
                $this->error('No companies found with the provided ID(s).');
                return;
            }

            // Ellenőrzi, hogy meg lett-e adva a --force jelző
            // Ha igen, akkor a cégek véglegesen lesznek törölve
            // Ha nem, akkor a cégek csak szoftveresen lesznek törölve
            if( $this->option('force') ) {
                // Véglegesen törli a cégeket
                $companies->each->forceDelete();
                $this->info('Companies permanently deleted: ' . implode(', ', $ids));
            } else {
                // Szoftveresen törli a cégeket
                $companies->each->delete();
                $this->info('Companies soft deleted: ' . implode(', ', $ids));
            }


        } elseif( $action == 'list' ) {
            $filter = $this->option('filter');
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
        } elseif( $action === 'restore' ) {
            if (!$companyId) {
                $this->error('You must provide a company ID or a comma-separated list of IDs to restore.');
                return;
            }

            $ids = explode(',', $companyId);
            $companies = Company::onlyTrashed()->whereIn('id', $ids)->get();

            if ($companies->isEmpty()) {
                $this->error('No deleted companies found with the provided ID(s).');
                return;
            }

            $companies->each->restore();
            $this->info('Companies restored successfully: ' . implode(', ', $ids));

        } else {
            $this->error('Invalid action. Use "create" or "update".');
        }
    }
}
