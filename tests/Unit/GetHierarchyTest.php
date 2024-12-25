<?php

use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\Http\Controllers\HierarchyController;
use App\Models\Entity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class GetHierarchyTest extends TestCase
{
    //use RefreshDatabase;

    public function testGetHierarchySuccessful()
    {
        // Create an entity with parents and children
        $parent = Entity::factory()->create();
        $child1 = Entity::factory()->create();
        $child2 = Entity::factory()->create();
        $parent->parents()->attach(Entity::factory()->create());
        $parent->children()->attach([$child1->id, $child2->id]);

        // Call the getHierarchy method
        $response = (new HierarchyController)->getHierarchy($parent->id);

        // Assert that the response is a JSON response with a 200 status code
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        // Assert that the entity hierarchy is returned in the response
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($parent->id, $responseData['id']);
        $this->assertNotEmpty($responseData['parents']);
        $this->assertNotEmpty($responseData['children']);
    }

    public function testGetHierarchyModelNotFoundException()
    {
        // Call the getHierarchy method with a non-existent entity ID
        $response = (new HierarchyController)->getHierarchy(999);

        // Assert that the response is a JSON response with a 404 status code
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertJson($response->getContent());

        // Assert that the error message is returned in the response
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(APP_FALSE, $responseData['success']);
        $this->assertEquals('getHierarchy model not found error', $responseData['error']);
    }

    public function testGetHierarchyQueryException()
    {
        // Mock the Entity model to throw a QueryException
        $this->mock(Entity::class, function ($mock) {
            $mock->shouldReceive('with')->andThrow(new QueryException());
        });

        // Call the getHierarchy method
        $response = (new HierarchyController)->getHierarchy(1);

        // Assert that the response is a JSON response with a 500 status code
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertJson($response->getContent());

        // Assert that the error message is returned in the response
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(APP_FALSE, $responseData['success']);
        $this->assertEquals('getHierarchy query error', $responseData['error']);
    }

    public function testGetHierarchyGeneralException()
    {
        // Mock the Entity model to throw a general Exception
        $this->mock(Entity::class, function ($mock) {
            $mock->shouldReceive('with')->andThrow(new Exception());
        });

        // Call the getHierarchy method
        $response = (new HierarchyController)->getHierarchy(1);

        // Assert that the response is a JSON response with a 500 status code
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertJson($response->getContent());

        // Assert that the error message is returned in the response
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(APP_FALSE, $responseData['success']);
        $this->assertEquals('getHierarchy general error', $responseData['error']);
    }
}
