<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Entity;
use App\Models\Person;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PersonCompanyEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tiltsa le az idegenkulcs-ellenőrzéseket a kényszerek megsértésének elkerülése érdekében
        // Az ismétlődő bejegyzések elkerülése érdekében vágja le a táblákat
        Schema::disableForeignKeyConstraints();

        // Ürítse ki a táblákat
        Company::truncate();
        Person::truncate();
        Entity::truncate();

        // Engedélyezze újra az idegen kulcs ellenőrzését
        Schema::enableForeignKeyConstraints();

        // Faker példányosítása
        // Az adatokhoz szükséges véletlenszámokat generálja
        $faker = Factory::create();

        // Tájékoztassa a felhasználót a cégek létrehozásáról
        $this->command->warn(PHP_EOL . 'Companies creating ...');

        /**
         * ================================================
         * 1. cég létrehozása
         * ================================================
         * A cégazonosító adatokat (név, regisztrációs szám, adóazonosító szám)
         * a factory segítségével generáljuk, de a várost és az országot
         * explicit módon beállítjuk, hogy azok ismertek legyenek
         */
        $company_01 = Company::create([
            'id' =>  1,
            'name' => fake()->company(),
            'directory' => fake()->word(),
            'registration_number' => fake()->phoneNumber(),
            'tax_id' => fake()->phoneNumber(),
            'country_id' => 92, 'city_id' =>  1,
            'address' => fake()->address()
        ]);

        /**
         * ===============================================
         * 2. cég létrehozása
         * ===============================================
         * A második cég adatait a faker segítségével generáljuk, de a várost és az országot
         * explicit módon beállítjuk, hogy azok ismertek legyenek
         */
        $company_02 = Company::create([
            'id' =>  2,
            'name' => fake()->company(),
            'directory' => fake()->word(),
            'registration_number' => fake()->phoneNumber(),
            'tax_id' => fake()->phoneNumber(),
            'country_id' => 92, 'city_id' =>  2,
            'address' => fake()->address()
        ]);

        /**
         * ===============================================
         * 3. cég létrehozása
         * ===============================================
         * A második cég adatait a faker segítségével generáljuk, de a várost és az országot
         * explicit módon beállítjuk, hogy azok ismertek legyenek
         */
        $company_03 = Company::create([
            'id' =>  3,
            'name' => fake()->company(),
            'directory' => fake()->word(),
            'registration_number' => fake()->phoneNumber(),
            'tax_id' => fake()->phoneNumber(),
            'country_id' => 92, 'city_id' =>  3,
            'address' => fake()->address()
        ]);

        // Tájékoztassa a felhasználót a cégek létrehozásáról
        $this->command->warn(PHP_EOL . 'Companies created');

        // Tájékoztassa a felhasználót a személyek létrehozásáról
        $this->command->warn(PHP_EOL . 'Persons creating ...');

        // A személy osztály példányosítása
        // Az első személy adatait a faker segítségével generáljuk
        $person_01 = Person::create([
            'id' => 1,
            // A személy neve
            'name' => $faker->name( $faker->randomElement([0,1]) == 0 ? 'male' : 'female' ),
            // Az email cím generálása a fakerrel
            'email' => $faker->email(),
            // A jelszó generálása a fakerrel
            'password' => $faker->password(8),
            // A nyelv beállítása magyarra
            'language' => 'hu',
            // A születési dátum generálása a fakerrel
            'birthdate' => $faker->date('Y-m-d', '-25 year'),
            // A személy aktív státuszának beállítása
            'active' => 1
        ]);
        // A második személy adatait a faker segítségével generáljuk
        $person_02 = Person::create([
            'id' => 2,
            'name' => $faker->name( $faker->randomElement([0,1]) == 0 ? 'male' : 'female' ),
            // Az email cím generálása a fakerrel
            'email' => $faker->email(),
            // A jelszó generálása a fakerrel
            'password' => $faker->password(8),
            // A nyelv beállítása magyarra
            'language' => 'hu',
            // A születési dátum generálása a fakerrel
            'birthdate' => $faker->date('Y-m-d', '-25 year'),
            // A személy aktív státuszának beállítása
            'active' => 1
        ]);
        // A harmadik személy adatait a faker segítségével generáljuk
        $person_03 = Person::create([
            'id' => 3,
            // A személy neve a fakerrel generált
            'name' => $faker->name( $faker->randomElement([0,1]) == 0 ? 'male' : 'female' ),
            // Az email cím generálása a fakerrel
            'email' => $faker->email(),
            // A jelszó generálása a fakerrel
            'password' => $faker->password(8),
            // A nyelv beállítása magyarra
            'language' => 'hu',
            // A születési dátum generálása a fakerrel
            'birthdate' => $faker->date('Y-m-d', '-25 year'),
            // A személy aktív státuszának beállítása
            'active' => 1
        ]);

        // Tájékoztassa a felhasználót a cégek létrehozásáról
        $this->command->warn(PHP_EOL . 'Companies creating ...');

        // Tájékoztassa a felhasználót a személyek hozzárendeléséről
        $this->command->warn(PHP_EOL . 'Persons attaching Company ...');

        /**
         * Hozzárendeljük a személyeket a cégekhez
         * A személyekhez tartozó cégek táblázatában a személy ID-ja
         * és a hozzárendelt cég ID-ja lesz elmentve
         */
        $person_01->companies()->attach([1, 2]);
        $person_02->companies()->attach([2]);
        $person_03->companies()->attach([3]);

        // Tájékoztassa a felhasználót a személyek hozzárendeléséről
        $this->command->warn(PHP_EOL . 'Persons attached Companies');

        // Tájékoztassa a felhasználót, hogy az Entityk létrehozása a cégekben folyamatban van
        $this->command->warn(PHP_EOL . 'Entity creating in Company ...');

        /**
         * Az első céghez tartozó első Entity létrehozása
         * Az Entity tulajdonságai a fakerrel generáltak
         * A kezdési dátum -10 évvel ezelőtt, a záró dátum pedig 10 évvel ezután lesz
         *
         * @var Entity $entity
         */
        $company_01->entities()->create([
            'id' => 1,
            'name' => $faker->name( $faker->randomElement([0,1]) == 0 ? 'male' : 'female' ),
            'email' => $faker->email(),
            'start_date' => $faker->dateTimeBetween('-10 year', 'now'),
            'end_date' => $faker->dateTimeBetween('now', '+10 year'),
            'last_export' => NULL,
            'active' => 1
        ]);

        /**
         * Az első céghez tartozó második Entity létrehozása
         * Az Entity tulajdonságai a fakerrel generáltak
         * A kezdési dátum minimum -10 évvel ezelőtt, a záró dátum pedig maximum 10 évvel ezután lesz
         *
         * @var Entity $entity
         */
        $entity_01 = $company_01->entities()->create([
            'id' => 2,
            'name' => $faker->name( $faker->randomElement([0,1]) == 0 ? 'male' : 'female' ),
            'email' => $faker->email(),
            'start_date' => $faker->dateTimeBetween('-10 year', 'now'),
            'end_date' => $faker->dateTimeBetween('now', '+10 year'),
            'last_export' => NULL,
            'active' => 1
        ]);

        /**
         * Az második céghez tartozó első Entity létrehozása
         * Az Entity tulajdonságai a fakerrel generáltak
         * A kezdési dátum minimum -10 évvel ezelőtt, a záró dátum pedig maximum 10 évvel ezután lesz
         *
         * @var Entity $entity
         */
        $entity_02 = $company_02->entities()->create([
            'id' => 3,
            'name' => $faker->name( $faker->randomElement([0,1]) == 0 ? 'male' : 'female' ),
            'email' => $faker->email(),
            'start_date' => $faker->dateTimeBetween('-10 year', 'now'),
            'end_date' => $faker->dateTimeBetween('now', '+10 year'),
            'last_export' => NULL,
            'active' => 1
        ]);

        /**
         * A második céghez tartozó harmadik Entity létrehozása
         * Az Entity tulajdonságai a fakerrel generáltak
         * A kezdési dátum minimum -10 évvel ezelőtt, a záró dátum pedig maximum 10 évvel ezután lesz
         *
         * @var Entity $entity
         */
        $entity_03 = $company_02->entities()->create([
            'id' => 4,
            'name' => $faker->name( $faker->randomElement([0,1]) == 0 ? 'male' : 'female' ),
            'email' => $faker->email(),
            'start_date' => $faker->dateTimeBetween('-10 year', 'now'),
            'end_date' => $faker->dateTimeBetween('now', '+10 year'),
            'last_export' => NULL,
            'active' => 1
        ]);

        /**
         * Az harmadik céghez tartozó első Entity létrehozása
         * Az Entity tulajdonságai a fakerrel generáltak
         * A kezdési dátum minimum -10 évvel ezelőtt, a záró dátum pedig maximum 10 évvel ezután lesz
         *
         * @var Entity $entity
         */
        $entity_04 = $company_03->entities()->create([
            'id' => 5,
            'name' => $faker->name( $faker->randomElement([0,1]) == 0 ? 'male' : 'female' ),
            'email' => $faker->email(),
            'start_date' => $faker->dateTimeBetween('-10 year', 'now'),
            'end_date' => $faker->dateTimeBetween('now', '+10 year'),
            'last_export' => NULL,
            'active' => 1
        ]);

        /**
         * Az harmadik céghez tartozó második Entity létrehozása
         * Az Entity tulajdonságai a fakerrel generáltak
         * A kezdési dátum minimum -10 évvel ezelőtt, a záró dátum pedig maximum 10 évvel ezután lesz
         *
         * @var Entity $entity
         */
        $entity_05 = $company_03->entities()->create([
            'id' => 6,
            'name' => $faker->name( $faker->randomElement([0,1]) == 0 ? 'male' : 'female' ),
            'email' => $faker->email(),
            'start_date' => $faker->dateTimeBetween('-10 year', 'now'),
            'end_date' => $faker->dateTimeBetween('now', '+10 year'),
            'last_export' => NULL,
            'active' => 1
        ]);

        // Tájékoztassa a felhasználót, hogy az Entityk létrehozása a cégekben megtörtént
        $this->command->warn(PHP_EOL . 'Entity created in Comapny');    }
}
