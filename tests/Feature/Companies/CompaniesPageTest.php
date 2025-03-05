<?php

namespace Tests\Feature\Companies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Company;

class CompaniesPageTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Feltételezzük, hogy az adatbázisban már létezik egy ilyen felhasználó
        $credentials = [
            'email'    => 'zoltan1_kovacs@msn.com',
            'password' => 'password',
        ];

        // Bejelentkezés a /login útvonalon keresztül
        $response = $this->post('/login', $credentials);

        // Ellenőrizheted, hogy a bejelentkezés sikeres volt (például átirányít-e)
        $response->assertRedirect();
        $this->assertAuthenticated();
    }

    public function testCompaniesPageDisplaysCompaniesList(): void
    {
        // HTTP GET kérés a /companies útvonalra
        $response = $this->get('/companies');

        // Ellenőrizzük, hogy a válasz sikeres (HTTP 200)
        $response->assertStatus(200);

        // Ellenőrizzük, hogy a helyes view-t jeleníti meg (pl. companies.blade.php)
        $response->assertViewIs('Index');

        // Ellenőrizzük, hogy a view-ban létezik a "companies" változó, és nem üres
        //$response->assertViewHas('companies', function ($companies) {
        //    return count($companies) > 0;
        //});

        // További ellenőrzés: az oldal tartalmazza az elvárt szöveget (például egy címet)
        $response->assertSee('COMPANIES');
    }
}
