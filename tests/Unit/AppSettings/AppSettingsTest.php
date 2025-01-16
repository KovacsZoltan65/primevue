<?php

namespace Tests\Unit\AppSettings;

use App\Http\Controllers\AppSettingController;
use App\Models\User;
use App\Repositories\AppSettingRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AppSettingsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    private $appSettingController;
    private AppSettingRepository $appSettingRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Middleware-ek kikapcsolása
        $this->withoutMiddleware();

        $this->appSettingRepository = $this->createMock(AppSettingRepository::class);

        // Mockolt repository-k átadása a controllernek
        $this->appSettingController = new AppSettingController(
            $this->appSettingRepository,
        );

        $user = User::factory()->create(); // Ha van User Factory
        $this->actingAs($user);
    }

    public function testGetAppSettingsSuccess()
    {
        $request = Request::create('/app-settings', 'GET');
        $settings = ['setting1' => 'value1', 'setting2' => 'value2'];

        $this->appSettingRepository->method('getAppSettings')->willReturn($settings);

        $response = $this->appSettingController->getAppSettings($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($settings, $response->getData(true));
    }

    public function testGetAppSettingsQueryException()
    {
        $request = Request::create('/app-settings', 'GET');

        $this->appSettingRepository->method('getAppSettings')->will($this->throwException(new QueryException('', [], new \Exception())));

        $response = $this->appSettingController->getAppSettings($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testGetAppSettingsGeneralException()
    {
        $request = Request::create('/app-settings', 'GET');

        $this->appSettingRepository->method('getAppSettings')->will($this->throwException(new \Exception()));

        $response = $this->appSettingController->getAppSettings($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }
}



