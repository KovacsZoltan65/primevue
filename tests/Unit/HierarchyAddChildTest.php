<?php

namespace Tests\Unit;

use App\Models\Hierarchy;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\HierarchyController;
use App\Models\Entity;
use Illuminate\Support\Facades\DB;

class HierarchyAddChildTest extends TestCase
{
    // Automatikusan törli a tesztadatokat
    //use RefreshDatabase;

    public function testAddChildSuccess()
    {
        $parent = Entity::where('name', 'Parent')->first();
        if( !$parent ) {
            $parent = Entity::create(['name' => 'Parent', 'email' => 'parent@company_01.com', 'start_date' => DB::raw('NOW()'), 'end_date' => null, 'company_id' => 1,]);
        }

        $child = Entity::where('name', 'Child')->first();
        if( !$child ) {
            $child = Entity::create(['name' => 'Child', 'email' => 'child@company_01.com', 'start_date' => DB::raw('NOW()'), 'end_date' => null, 'company_id' => 1,]);
        }

        $hierarchy = Hierarchy::where('parent_id', $parent->id)
            ->where('child_id', $child->id)->first();

        if( $hierarchy ) {
            $hierarchy->delete();
        }

        $request = new Request(['child_id' => $child->id]);
        $controller = new HierarchyController();

        $response = $controller->addChild($request, $parent->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = $response->getData(); // Objektumként adja vissza a JSON-t
        $this->assertEquals('Child added successfully.', $responseData->message);
    }

    public function testAddChildParentNotFound()
    {
        // Győződj meg róla, hogy nincs parent entitás az adatbázisban
        $parentId = 9999; // Egy nem létező ID
        $request = new Request(['child_id' => 1]);
        $controller = new HierarchyController();

        $response = $controller->addChild($request, $parentId);

        // Ellenőrzések
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('addChild model not found error', $response->getData()->error);
    }

    public function testAddChildChildNotFound()
    {
        $parent = Entity::where('name', 'Parent')->first();
        if( !$parent ) {
            $parent = Entity::create(['name' => 'Parent', 'email' => 'parent@company_01.com', 'start_date' => DB::raw('NOW()'), 'end_date' => null, 'company_id' => 1,]);
        }

        // Olyan gyerek ID-t használunk, ami nem létezik az adatbázisban
        $nonExistentChildId = 9999;

        $request = new Request(['child_id' => $nonExistentChildId]);
        $controller = new HierarchyController();

        $response = $controller->addChild($request, $parent->id);

        // Ellenőrzések
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());

        $responseData = $response->getData(); // JSON válasz objektumként
        $this->assertEquals('addChild model not found error', $responseData->error);
    }



    public function testAddChildQueryError()
    {
        $parent = Entity::where('name', 'Parent')->first();
        if( !$parent ) {
            $parent = Entity::create(['name' => 'Parent', 'email' => 'parent@company_01.com', 'start_date' => DB::raw('NOW()'), 'end_date' => null, 'company_id' => 1,]);
        }

        $child = Entity::where('name', 'Child')->first();
        if( !$child ) {
            $child = Entity::create(['name' => 'Child', 'email' => 'child@company_01.com', 'start_date' => DB::raw('NOW()'), 'end_date' => null, 'company_id' => 1,]);
        }

        // Hibahelyzet előidézése: például duplikált kulcs
        // Az `Hierarchy` táblában biztosítsuk, hogy már létezik a kapcsolat
        $hierarchy = Hierarchy::where('parent_id', $parent->id)->where('child_id', $child->id)->first();
        if( !$hierarchy ) {
            DB::table('hierarchies')
                ->insert(
                    ['parent_id' => $parent->id, 'child_id' => $child->id, 'created_at' => now(), 'updated_at' => now(),]
                );
        }

        $request = new Request(['child_id' => $child->id]);
        $controller = new HierarchyController();

        // Ez a hívás kiváltja a `QueryException`-t, mivel duplikált rekordot próbál hozzáadni
        $response = $controller->addChild($request, $parent->id);

        // Ellenőrzések
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode()); // Unprocessable Entity (a kivételtől függően)

        $responseData = $response->getData(); // JSON válasz objektumként
        $this->assertEquals('addChild query error', $responseData->error);
    }
}
