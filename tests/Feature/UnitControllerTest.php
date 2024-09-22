<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Unit;
use Skillz\UserService;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;






class UnitControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_a_single_unit(): void
    {
        $this->actingAsAuthenticatedTestUser();

        $unit = Unit::factory()->create();
        $response = $this->getJson('/api/units/' . $unit->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $unit->id,
                'name' => $unit->name,
                'created_at' => $unit->created_at->toISOString(),
                'updated_at' => $unit->updated_at->toISOString(),
            ],
        ]);
    }

    //FIXME:

    // public function test_it_returns_404_when_getting_a_non_existent_unit(): void
    // {
    //     $this->actingAsAuthenticatedTestUser();
    //     $response = $this->getJson('/api/units/9999');
    //     $response->assertNotFound();
    // }

    public function test_it_returns_401_unauthenticated_for_non_logged_users(): void
    {
        $this->actingAsUnAuthenticatedTestUser();
        $unit = Unit::factory()->create();
        $response = $this->getJson('/api/units/' . $unit->id)->assertStatus(401);
    }


    public function test_it_can_get_all_units(): void
    {
        $this->actingAsAuthenticatedTestUser();
        Unit::factory()->count(5)->create();

        $response = $this->getJson('/api/units');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function test_it_returns_401_unauthenticated_to_get_all_units(): void
    {
        $this->actingAsUnAuthenticatedTestUser();
        Unit::factory()->count(3)->create();
        $this->getJson('/api/units/')->assertStatus(401);
    }
}
