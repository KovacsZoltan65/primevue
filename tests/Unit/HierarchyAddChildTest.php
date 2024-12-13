<?php

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\HierarchyController;
use App\Models\Entity;

class HierarchyAddChildTest extends TestCase
{
    public function testAddChildSuccess()
    {
        // Mock the Entity model to return a valid parent and child entity
        $parent = Entity::create(['name' => 'Parent']);
        $child = Entity::create(['name' => 'Child']);

        $request = new Request(['child_id' => $child->id]);
        $controller = new HierarchyController();

        $response = $controller->addChild($request, $parent->id);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Child added successfully.', $response->json()['message']);
    }

    public function testAddChildParentNotFound()
    {
        // Mock the Entity model to throw a ModelNotFoundException for parent entity
        $this->mock(Entity::class, function ($mock) {
            $mock->shouldReceive('findOrFail')->with(1)->andThrow(new \Illuminate\Database\Eloquent\ModelNotFoundException());
        });

        $request = new Request(['child_id' => 1]);
        $controller = new HierarchyController();

        $response = $controller->addChild($request, 1);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('addChild model not found error', $response->json()['error']);
    }

    public function testAddChildChildNotFound()
    {
        // Mock the Entity model to throw a ModelNotFoundException for child entity
        $this->mock(Entity::class, function ($mock) {
            $mock->shouldReceive('findOrFail')->with(1)->andThrow(new \Illuminate\Database\Eloquent\ModelNotFoundException());
        });

        $parent = Entity::create(['name' => 'Parent']);
        $request = new Request(['child_id' => 1]);
        $controller = new HierarchyController();

        $response = $controller->addChild($request, $parent->id);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('addChild model not found error', $response->json()['error']);
    }

    public function testAddChildQueryError()
    {
        // Mock the Entity model to throw a QueryException
        $this->mock(Entity::class, function ($mock) {
            $mock->shouldReceive('children')->andThrow(new \Illuminate\Database\QueryException());
        });

        $parent = Entity::create(['name' => 'Parent']);
        $child = Entity::create(['name' => 'Child']);
        $request = new Request(['child_id' => $child->id]);
        $controller = new HierarchyController();

        $response = $controller->addChild($request, $parent->id);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('addChild query error', $response->json()['error']);
    }

    public function testAddChildGeneralError()
    {
        // Mock the Entity model to throw a general Exception
        $this->mock(Entity::class, function ($mock) {
            $mock->shouldReceive('children')->andThrow(new \Exception());
        });

        $parent = Entity::create(['name' => 'Parent']);
        $child = Entity::create(['name' => 'Child']);
        $request = new Request(['child_id' => $child->id]);
        $controller = new HierarchyController();

        $response = $controller->addChild($request, $parent->id);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('addChild general error', $response->json()['error']);
    }
}