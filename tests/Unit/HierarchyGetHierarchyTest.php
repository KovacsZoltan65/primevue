<?php

use Illuminate\Database\QueryException;
use Tests\TestCase;
use App\Http\Controllers\HierarchyController;
use App\Models\Entity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HierarchyGetHierarchyTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    protected $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->controller = new HierarchyController();
    }

    public function testGetHierarchySuccess()
    {
\Log::info('testGetHierarchySuccess');
        // Create an entity with parents and children
        //$entity = Entity::factory()->create();
        //$entity->parents()->attach(Entity::factory()->create());
        //$entity->children()->attach(Entity::factory()->create());
        $entity = Entity::find(1);
\Log::info('$entity: ' . print_r($entity, true));
        // Call the getHierarchy method
        $response = $this->controller->getHierarchy($entity->id);
\Log::info('$response: ' . print_r($response, true));
        // Assert that the response is a JSON response with the entity data
        //$this->assertInstanceOf(Response::class, $response);
        //$this->assertEquals(\Symfony\Component\HttpFoundation\Response::HTTP_OK, $response->getStatusCode());
        //$this->assertJson($response->getContent());
        //$this->assertEquals($entity->toArray(), json_decode($response->getContent(), true));
    }

    public function testGetHierarchyModelNotFoundException()
    {
        // Call the getHierarchy method with a non-existent entity ID
        $response = $this->controller->getHierarchy(999);

        // Assert that the response is a JSON response with a 404 status code and an error message
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals([
            'success' => APP_FALSE,
            'error' => 'getHierarchy model not found error',
        ], json_decode($response->getContent(), true));
    }

    public function testGetHierarchyQueryException()
    {
        // Mock the Entity model to throw a QueryException
        $this->mock(Entity::class, function ($mock) {
            $mock->shouldReceive('with')->andThrow(new QueryException());
        });

        // Call the getHierarchy method
        $response = $this->controller->getHierarchy(1);

        // Assert that the response is a JSON response with a 500 status code and an error message
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(\Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals([
            'success' => APP_FALSE,
            'error' => 'getHierarchy query error',
        ], json_decode($response->getContent(), true));
    }

    public function testGetHierarchyGeneralException()
    {
        // Mock the Entity model to throw a general Exception
        $this->mock(Entity::class, function ($mock) {
            $mock->shouldReceive('with')->andThrow(new Exception());
        });

        // Call the getHierarchy method
        $response = $this->controller->getHierarchy(1);

        // Assert that the response is a JSON response with a 500 status code and an error message
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(\Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals([
            'success' => APP_FALSE,
            'error' => 'getHierarchy general error',
        ], json_decode($response->getContent(), true));
    }
}