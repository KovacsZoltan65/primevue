<?php

use App\Http\Controllers\CompanyController;
use App\Http\Requests\StoreCompanyRequest;

use App\Models\User;
use App\Repositories\CityRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;

use App\Services\CacheService;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $companyController;
    private CompanyRepository $companyRepository;
    private CityRepository $cityRepository;
    private CountryRepository $countryRepository;
    private CacheService $cacheService;

    protected function setUp(): void
    {
        parent::setUp();

        // Middleware-ek kikapcsolása
        $this->withoutMiddleware();

        $cacheService = new CacheService();

        $this->cityRepository = new CityRepository($cacheService);
        $this->countryRepository = new CountryRepository($cacheService);
        $this->companyRepository = new CompanyRepository($cacheService);

        $this->companyController = new CompanyController(
            $this->cityRepository,
            $this->countryRepository,
            $this->companyRepository
        );

        $user = User::factory()->create(); // Ha van User Factory
        $this->actingAs($user);
    }

    public function testSuccessfulCompanyCreation()
    {
        $companyData  = [
            'name' => 'Company 01',
            'directory' => 'Company_01_Directory',
            'registration_number' => '1111',
            'tax_id' => '2222',
            'country_id' => 1,
            'city_id' => 2,
            'address' => 'Address 01',
        ];

        // HTTP-kérés szimulálása
        $response = $this->post(route('api.post.companies'), $companyData);

        // Ellenőrzések
        $response->assertStatus(201); // Ellenőrizd a válasz státusz kódját

        /*
         * Ez egy assertion (állítás) a Laravel tesztelési keretrendszerében, amely arra szolgál, hogy ellenőrizze,
         * egy adott adat létezik-e az adatbázisban egy konkrét táblában.
         * 1. Első paraméter: companies
         *    Ez az adatbázistábla neve, amelyben az adatot keresni kell. Ebben az esetben a companies tábla.
         * 2. Második paraméter: ['name' => $companyData['name'], ...]
         *    Ez egy asszociatív tömb, amely azokat az oszlopokat és értékeket tartalmazza,
         *    amelyek alapján a keresést végzi.
         */
        $this->assertDatabaseHas('companies', [
            'name' => $companyData['name'],
            'city_id' => $companyData['city_id'],
            'country_id' => $companyData['country_id'],
        ]);
    }
}
