<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_a_single_department(): void
    {
        $this->actingAsAuthenticatedTestUser();

        $department = Department::factory()->create();

        $response = $this->getJson('/api/departments/' . $department->id);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $department->id,
                'name' => $department->name,
                'created_at' => $department->created_at->toISOString(), // Convert Carbon instance to ISO string
                'updated_at' => $department->updated_at->toISOString(), // Convert Carbon instance to ISO string
            ],
        ]);
    }

    //FIXME:
    // public function test_it_returns_404_when_getting_a_non_existent_unit(): void
    // {
    //     $this->actingAsAuthenticatedTestUser();
    //     $response = $this->getJson('/api/departments/9999');
    //     $response->assertNotFound();
    // }

    public function test_it_returns_401_unauthenticated_for_non_logged_users(): void
    {
        $this->actingAsUnAuthenticatedTestUser();
        $department = Department::factory()->create();
        $response = $this->getJson('/api/departments/' . $department->id)->assertStatus(401);
    }

    public function test_it_can_get_all_departments(): void
    {

        $this->actingAsAuthenticatedTestUser();
        Department::factory()->count(5)->create();

        $response = $this->getJson('/api/departments');

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
        Department::factory()->count(3)->create();
        $this->getJson('/api/departments/')->assertStatus(401);
    }

    public function test_to_get_a_department_with_units()
    {
        $this->actingAsAuthenticatedTestUser();
        $department = Department::factory()->create();
        $unit = \App\Models\Unit::factory()->create(['department_id' => $department->id]);

        $response = $this->getJson('/api/department_units/' . $department->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'units' => [
                    '*' => [
                        'id',
                        'name',
                        'department_id',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);
    }
}
