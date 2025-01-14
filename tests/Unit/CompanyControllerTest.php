<?php

use App\Http\Controllers\CompanyController;
use App\Http\Requests\GetCompanyRequest;
use App\Http\Requests\StoreCompanyRequest;

use App\Models\User;
use App\Repositories\CityRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;

use App\Services\CacheService;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CompanyControllerTest extends TestCase
{
    //use RefreshDatabase;
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

        // Mockolt repository-k inicializálása
        $this->cityRepository = $this->createMock(CityRepository::class);
        $this->countryRepository = $this->createMock(CountryRepository::class);
        $this->companyRepository = $this->createMock(CompanyRepository::class);

        // Mockolt repository-k átadása a controllernek
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

    public function testGetCompaniesSuccessful()
    {
        // Mock the company repository to return a collection of companies
        $companies = collect([
            ['id' => 1, 'name' => 'Company 01'],
            ['id' => 2, 'name' => 'Company 02'],
        ]);

        $this->companyRepository->expects($this->once())
            ->method('getCompanies')
            ->with($this->isInstanceOf(Request::class))
            ->willReturn($companies);

        // HTTP-kérés szimulálása a getCompanies metódusra
        // Ebben a tesztben a getCompanies metódust hívjuk meg egy új Request példánnyal,
        // amelyben nincsenek paraméterek
        $response = $this->companyController->getCompanies(new Request());

        // Ellenőrizzük, hogy a válasz egy JsonResponse példány-e
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Ellenőrizzük, hogy a válasz státusz kódja 200-e
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Visszaadja a válasz tartalmát, de már asszociatív tömbben
        $responseData = json_decode($response->getContent(), true);

        // Az adatoknak meg kell egyezni azzal, amit a repository módszer visszaad
        // (aminek a visszatérési értékét a $companies változóban tároltuk).
        // Az assertEquals metódus arra szolgál, hogy ellenőrizze, hogy két változó értéke egyenlő-e.
        $this->assertEquals($companies->toArray(), $responseData);
    }

    public function testGetCompaniesQueryException()
    {
        $previousException = new \Exception('Mocked previous exception');
        $this->companyRepository->expects($this->once())
            ->method('getCompanies')
            ->with($this->isInstanceOf(Request::class))
            ->willThrowException(new QueryException(
                '',
                'SELECT * FROM companies',
                [], $previousException
            ));

        $response = $this->companyController->getCompanies(new Request());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);

        // Ellenőrizd a pontos üzenetet
        $this->assertEquals('getCompanies query error', $responseData['error']);
    }

    public function testGetCompaniesGeneralException()
    {
        // Mock the company repository to throw a general Exception
        $this->companyRepository->expects($this->once())
            ->method('getCompanies')
            ->with($this->isInstanceOf(Request::class))
            ->willThrowException(new Exception());

        // Call the getCompanies method
        $response = $this->companyController->getCompanies(new Request());

        // Assert the response is a JSON response with a 500 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        // Assert the response contains an error message
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
    }


    public function testGetCompanySuccessful()
    {
        // Mock the company repository to return a company
        $company = ['id' => 1, 'name' => 'Company 01'];

        $this->companyRepository->expects($this->once())
            ->method('getCompany')
            ->with($this->equalTo(1))
            ->willReturn($company);

        $response = $this->companyController->getCompany(new GetCompanyRequest( ['id' => 1] ));

        // Assert the response is a JSON response with a 200 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Assert the response contains the company data
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($company, $responseData);
    }

    public function testGetCompanyModelNotFoundException()
    {
        // Mock the company repository to throw a ModelNotFoundException
        $this->companyRepository->expects($this->once())
            ->method('getCompany')
            ->with($this->equalTo(1))
            ->willThrowException(new ModelNotFoundException());

        // Create a request with the company ID
        $request = new GetCompanyRequest(['id' => 1]);

        // Call the getCompany method
        $response = $this->companyController->getCompany($request);

        // Assert the response is a JSON response with a 404 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

        // Assert the response contains an error message
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('getCompany model not found error', $responseData['error']);
    }

    public function testGetCompanyQueryException()
    {
        $previousException = new \Exception('Mocked previous exception');
        $this->companyRepository->expects($this->once())
            ->method('getCompany')
            ->with($this->equalTo(1))
            ->willThrowException(new QueryException(
                '',
                'SELECT * FROM companies WHERE id = ?',
                [], $previousException
            ));

        // Create a request with the company ID
        $request = new GetCompanyRequest(['id' => 1]);

        // Call the getCompany method
        $response = $this->companyController->getCompany($request);

        // Assert the response is a JSON response with a 422 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        // Assert the response contains an error message
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('getCompany query error', $responseData['error']);
    }

    public function testGetCompanyGeneralException()
    {
        // Mock the company repository to throw a general Exception
        $this->companyRepository->expects($this->once())
            ->method('getCompany')
            ->with($this->equalTo(1))
            ->willThrowException(new Exception());

        // Create a request with the company ID
        $request = new GetCompanyRequest(['id' => 1]);

        // Call the getCompany method
        $response = $this->companyController->getCompany($request);

        // Assert the response is a JSON response with a 500 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        // Assert the response contains an error message
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('getCompany general error', $responseData['error']);
    }


    public function testGetCompanyByNameSuccessful()
    {
        // Mock the company repository to return a company
        $company = ['id' => 1, 'name' => 'Company 01'];

        $this->companyRepository->expects($this->once())
            ->method('getCompanyByName')
            ->with($this->equalTo('Company 01'))
            ->willReturn($company);

        $response = $this->companyController->getCompanyByName('Company 01');

        // Assert the response is a JSON response with a 200 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Assert the response contains the company data
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($company, $responseData);
    }

    public function testGetCompanyByNameModelNotFoundException()
    {
        // Mock the company repository to throw a ModelNotFoundException
        $this->companyRepository->expects($this->once())
            ->method('getCompanyByName')
            ->with($this->equalTo('Company 01'))
            ->willThrowException(new ModelNotFoundException());

        $response = $this->companyController->getCompanyByName('Company 01');

        // Assert the response is a JSON response with a 404 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

        // Assert the response contains an error message
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('getCompanyByName model not found error', $responseData['error']);
    }

    public function testGetCompanyByNameQueryException()
    {
        $previousException = new \Exception('Mocked previous exception');
        $this->companyRepository->expects($this->once())
            ->method('getCompanyByName')
            ->with($this->equalTo('Company 01'))
            ->willThrowException(new QueryException(
                '',
                'SELECT * FROM companies WHERE name = ?',
                [], $previousException
            ));

        $response = $this->companyController->getCompanyByName('Company 01');

        // Assert the response is a JSON response with a 422 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        // Assert the response contains an error message
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('getCompanyByName query error', $responseData['error']);
    }

    public function testGetCompanyByNameGeneralException()
    {
        // Mock the company repository to throw a general Exception
        $this->companyRepository->expects($this->once())
            ->method('getCompanyByName')
            ->with($this->equalTo('Company 01'))
            ->willThrowException(new Exception());

        $response = $this->companyController->getCompanyByName('Company 01');

        // Assert the response is a JSON response with a 500 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        // Assert the response contains an error message
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('getCompanyByName general error', $responseData['error']);
    }

    public function testCreateCompanySuccessful()
    {
        // Mock the company repository to return a created company
        $company = ['id' => 1, 'name' => 'Company 01'];

        $this->companyRepository->expects($this->once())
            ->method('createCompany')
            ->with($this->isInstanceOf(StoreCompanyRequest::class))
            ->willReturn($company);

        $request = new StoreCompanyRequest();
        $request->merge([
            'name' => 'Company 01',
            'directory' => 'Company_01_Directory',
            'registration_number' => '1111',
            'tax_id' => '2222',
            'country_id' => 1,
            'city_id' => 2,
            'address' => 'Address 01',
        ]);

        // Call the createCompany method
        $response = $this->companyController->createCompany($request);

        // Assert the response is a JSON response with a 201 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        // Assert the response contains the company data
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($company, $responseData);
    }

    public function testCreateCompanyQueryException()
    {
        $previousException = new \Exception('Mocked previous exception');
        $this->companyRepository->expects($this->once())
            ->method('createCompany')
            ->with($this->isInstanceOf(StoreCompanyRequest::class))
            ->willThrowException(new QueryException(
                '',
                'INSERT INTO companies',
                [],
                $previousException
            ));

        $request = $this->createMock(StoreCompanyRequest::class);
        $request->method('all')->willReturn([
            'name' => 'Company 01',
            'directory' => 'Company_01_Directory',
            'registration_number' => '1111',
            'tax_id' => '2222',
            'country_id' => 1,
            'city_id' => 2,
            'address' => 'Address 01',
        ]);

        // Call the createCompany method
        $response = $this->companyController->createCompany($request);

        // Assert the response is a JSON response with a 422 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        // Assert the response contains an error message
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('createCompany query error', $responseData['error']);
    }

    public function testCreateCompanyGeneralException()
    {
        // Mock the company repository to throw a general Exception
        $this->companyRepository->expects($this->once())
            ->method('createCompany')
            ->with($this->isInstanceOf(StoreCompanyRequest::class))
            ->willThrowException(new Exception());

        $request = new StoreCompanyRequest();
        $request->merge([
            'name' => 'Company 01',
            'directory' => 'Company_01_Directory',
            'registration_number' => '1111',
            'tax_id' => '2222',
            'country_id' => 1,
            'city_id' => 2,
            'address' => 'Address 01',
        ]);

        // Call the createCompany method
        $response = $this->companyController->createCompany($request);

        // Assert the response is a JSON response with a 500 status code
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        // Assert the response contains an error message
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('createCompany general error', $responseData['error']);
    }


}







