<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = \App\Models\User::where('email', '=','zoltan1_kovacs@msn.com')
            ->where('password', '=', bcrypt('password'))
            ->first();

        // Token generálása a felhasználónak (Sanctum példa)
        $token = $user->createToken('TestToken')->plainTextToken;
        // Az Authorization fejléc beállítása a tesztkérésekhez
        $this->withHeader('Authorization', 'Bearer ' . $token);
    }
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
