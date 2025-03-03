<?php

namespace Tests\API\Companies;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Company;

class CompaniesIndexApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        //$this->withoutMiddleware();
        $user = User::where([
            ['email', '=', 'zoltan1_kovacs@msn.com'],
            ['password' => bcrypt('password')]
        ])->first();

        // Token generálása a felhasználónak (Sanctum példa)
        $token = $user->createToken('TestToken')->plainTextToken;
        // Az Authorization fejléc beállítása a tesztkérésekhez
        $this->withHeader('Authorization', "Bearer {$token}");
    }

    /** @test */
    public function testGetCompaniesApiReturnsListOfCompanies(): void
    {
        // Létrehozunk 3 céget a teszt adatbázisban
        $companies = Company::factory()->count(3)->create();

        // API hívás az "api/getCompanies" végpontra
        $response = $this->getJson('api/getCompanies');

        // Ellenőrizzük, hogy a válasz HTTP státusza 200 OK
        $response->assertStatus(200);

        // Ellenőrizzük, hogy a válasz JSON struktúrája a "data" kulcs alatt tartalmazza a cégeket,
        // ahol minden cég rendelkezik legalább az "id" és "name" mezőkkel.
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name']
            ]
        ]);

        // Ellenőrizzük, hogy a visszakapott cégek száma megegyezik a létrehozott cégek számával
        $this->assertCount(3, $response->json('data'));
    }
}
