<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ProcessFlow;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_create_new_process_flow_with_steps_controller(): void
    {
        $this->actingAsAuthenticatedTestUser();
        $processFlowData = [
            'name' => 'Test Process Flow',
            'frequency' => 'weekly',
            'status' => true,
            'frequency_for' => 'users',
            'day' => null,
            'week' => 'monday',
            'steps' => [
                [

                    'name' => 'test name single test',
                    'step_route' => 'this should be a route',
                    'assignee_user_route' => 1,
                    'next_user_designation' => 1,
                    'next_user_department' => 1,
                    'next_user_unit' => 1,
                    'next_user_location' => 1,
                    'step_type' => 'create',
                    'user_type' => 'customer',
                    'status' => 1,
                ],
                [

                    'name' => 'test name single two test',
                    'step_route' => 'this should be a route',
                    'assignee_user_route' => 1,
                    'next_user_designation' => 1,
                    'next_user_department' => 1,
                    'next_user_unit' => 1,
                    'next_user_location' => 1,
                    'step_type' => 'create',
                    'user_type' => 'customer',
                    'status' => 1,
                ],
            ],
        ];

        $response = $this->postJson('/api/processflows', $processFlowData);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'frequency',
                'status',
                'frequency_for',
                'day',
                'week',
                'steps' => [
                    '*' => [
                        'id',
                        'name',
                        'step_route',
                        'assignee_user_route',
                        'next_user_designation',
                        'next_user_department',
                        'next_user_unit',
                        'next_user_location',
                        'step_type',
                        'user_type',
                        'status',
                    ],
                ],
            ],
        ]);

        $this->assertDatabaseHas('process_flow_steps', [
            'name' => 'test name single two test',
        ]);
        $this->assertDatabaseHas('process_flows', ['name' => 'Test Process Flow'],);
        $response->assertStatus(201);
    }
    public function test_to_create_new_process_flow_without_steps_controller(): void
    {

        $this->actingAsAuthenticatedTestUser();
        $processFlowData = [
            'name' => 'Test Process Flow',
            'start_step_id' => 3,
            'frequency' => 'weekly',
            'status' => true,
            'frequency_for' => 'users',
            'day' => null,
            'week' => 'monday',
        ];

        $response = $this->postJson('/api/processflows', $processFlowData);

        $this->assertDatabaseHas('process_flows', $processFlowData);
        $response->assertStatus(201);
    }
    public function test_to_failed_when_unautheticated_try_to_access_process_flow_route(): void
    {
        $this->actingAsUnAuthenticatedTestUser();
        $processFlowData = [
            'name' => 'Test Process Flow',
            'start_step_id' => 3,
            'frequency' => 'weekly',
            'status' => true,
            'frequency_for' => 'users',
            'day' => null,
            'week' => 'monday',
        ];

        $response = $this->postJson('/api/processflows', $processFlowData);
        $response->assertStatus(401);
    }

    //FIXME:

    // public function test_to_create_process_flow_controller_returns_validation_errors_for_invalid_data(): void
    // {
    //     $this->actingAsAuthenticatedTestUser();

    //     $invalidData = [
    //         'name' => '',
    //         'frequency' => 'invalid',
    //     ];
    //     $response = $this->postJson('/api/processflows', $invalidData);

    //     $response->assertJsonValidationErrors(['name', 'frequency']);
    //     $response->assertStatus(422);
    // }

    public function test_to_view_process_flow_with_valid_id_successfully(): void
    {
        $this->actingAsAuthenticatedTestUser();

        $processFlowData = [
            'name' => 'Test Process Flow',
            'frequency' => 'weekly',
            'status' => true,
            'frequency_for' => 'users',
            'day' => null,
            'week' => 'monday',
            'steps' => [
                [

                    'name' => 'test name single test',
                    'step_route' => 'this should be a route',
                    'assignee_user_route' => 1,
                    'next_user_designation' => 1,
                    'next_user_department' => 1,
                    'next_user_unit' => 1,
                    'next_user_location' => 1,
                    'step_type' => 'create',
                    'user_type' => 'customer',
                    'status' => 1,
                ],
                [

                    'name' => 'test name single two test',
                    'step_route' => 'this should be a route',
                    'assignee_user_route' => 1,
                    'next_user_designation' => 1,
                    'next_user_department' => 1,
                    'next_user_unit' => 1,
                    'next_user_location' => 1,
                    'step_type' => 'create',
                    'user_type' => 'customer',
                    'status' => 1,
                ],
            ],
        ];
        $response = $this->postJson('/api/processflows', $processFlowData);
        $response->assertStatus(201);

        $processFlowId = $response->json('data.id');
        $this->getJson('/api/processflows/' . $processFlowId)->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'frequency',
                'status',
                'frequency_for',
                'day',
                'week',
                'steps' => [
                    '*' => [
                        'id',
                        'name',
                        'step_route',
                        'assignee_user_route',
                        'next_user_designation',
                        'next_user_department',
                        'next_user_unit',
                        'next_user_location',
                        'step_type',
                        'user_type',
                        'status',
                    ],
                ],
            ],
        ]);
    }

    //FIXME:
    // public function test_to_return_error_when_trying_to_view_nonexistent_process_flow(): void
    // {
    //     $this->actingAsAuthenticatedTestUser();
    //     $id = 9999;

    //     $response = $this->getJson('/api/processflows/' . $id);
    //     $response->assertStatus(404);
    // }

    public function test_to_verify_only_logged_in_users_can_view_a_process_flow(): void
    {

        $this->actingAsAuthenticatedTestUser();
        $processFlowData = [
            'name' => 'Test Process Flow',
            'start_step_id' => 3,
            'frequency' => 'weekly',
            'status' => true,
            'frequency_for' => 'users',
            'day' => null,
            'week' => 'monday',
        ];

        $response = $this->postJson('/api/processflows', $processFlowData);
        $response->assertStatus(201);
        $processFlowId = $response->json('data.id');

        $this->getJson('/api/processflows/' . $processFlowId)->assertStatus(200);
    }

    public function test_to_verify_unauthenticated_users_cannot_view_a_process_flow(): void
    {
        $this->actingAsUnAuthenticatedTestUser();
        $processFlowData = [
            'name' => 'Test Process Flow',
            'start_step_id' => 3,
            'frequency' => 'weekly',
            'status' => true,
            'frequency_for' => 'users',
            'day' => null,
            'week' => 'monday',
        ];

        $response = $this->postJson('/api/processflows', $processFlowData);
        $response->assertStatus(401);

        $this->getJson('/api/processflows/1')->assertStatus(401);
    }
    /***
     * UPDATE TESTS
     */
    public function test_to_update_process_flow_with_valid_data_successfully(): void
    {

        $this->actingAsAuthenticatedTestUser();
        $processFlowData = [
            'name' => 'Test Process Flow',
            'frequency' => 'weekly',
            'status' => true,
            'frequency_for' => 'users',
            'day' => null,
            'week' => 'monday',
            'steps' => [
                [

                    'name' => 'test name single test',
                    'step_route' => 'this should be a route',
                    'assignee_user_route' => 1,
                    'next_user_designation' => 1,
                    'next_user_department' => 1,
                    'next_user_unit' => 1,
                    'next_user_location' => 1,
                    'step_type' => 'create',
                    'user_type' => 'customer',
                    'status' => 1,
                ],
                [

                    'name' => 'test name single two test',
                    'step_route' => 'this should be a route',
                    'assignee_user_route' => 1,
                    'next_user_designation' => 1,
                    'next_user_department' => 1,
                    'next_user_unit' => 1,
                    'next_user_location' => 1,
                    'step_type' => 'create',
                    'user_type' => 'customer',
                    'status' => 1,
                ],
            ],
        ];

        $response = $this->postJson('/api/processflows', $processFlowData);
        $response->assertStatus(201);
        $processFlowId = $response->json('data.id');

        $this->putJson('/api/processflows/' . $processFlowId, [
            'name' => 'Test Process Flow Updated',
        ])
            ->assertStatus(200)->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'frequency',
                    'status',
                    'frequency_for',
                    'day',
                    'week',
                    'steps' => [
                        '*' => [
                            'id',
                            'name',
                            'step_route',
                            'assignee_user_route',
                            'next_user_designation',
                            'next_user_department',
                            'next_user_unit',
                            'next_user_location',
                            'step_type',
                            'user_type',
                            'status',
                        ],
                    ],
                ],
            ]);
    }
    public function test_to_update_process_flow_without_steps_successfully(): void
    {
        $this->actingAsAuthenticatedTestUser();
        $processFlowData = ProcessFlow::factory()->create();
        $processFlowId = $processFlowData->id;
        $data = [
            'name' => 'Updated Process Flow Name',
        ];

        $this->putJson('/api/processflows/' . $processFlowId, $data)->assertStatus(200);
        $this->assertDatabaseHas('process_flows', $data);
    }
    public function test_to_unauthorized_cannot_update_process_flow_(): void
    {
        $this->actingAsUnAuthenticatedTestUser();
        $processFlowData = ProcessFlow::factory()->create();
        $processFlowId = $processFlowData->id;
        $data = [
            'name' => 'Updated Process Flow Name',
        ];

        $this->putJson('/api/processflows/' . $processFlowId, $data)->assertStatus(401);
    }

    //FIXME:

    // public function test_to_return_error_when_trying_to_update_nonexistent_process_flow(): void
    // {
    //     $this->actingAsAuthenticatedTestUser();

    //     $id = 9999;
    //     $data = [
    //         'name' => 'Updated Process Flow Name',
    //     ];

    //     $response = $this->putJson('/api/processflows/' . $id, $data);
    //     $response->assertStatus(404);
    // }

    //DELETE

    public function test_to_delete_a_processflow_successfully(): void
    {
        $this->actingAsAuthenticatedTestUser();
        $processFlowData = ProcessFlow::factory()->create();
        $processFlowId = $processFlowData->id;
        $response = $this->deleteJson('/api/processflows/' . $processFlowId);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('process_flows', $processFlowData->toArray());
        $this->assertDatabaseCount('process_flows', 0);
    }
    public function test_to_delete_a_processflow_and_associative_steps_successfully(): void
    {

        $this->actingAsAuthenticatedTestUser();

        $processFlowData = [
            'name' => 'Test Process Flow',
            'frequency' => 'weekly',
            'status' => true,
            'frequency_for' => 'users',
            'day' => null,
            'week' => 'monday',
            'steps' => [
                [

                    'name' => 'test name single test',
                    'step_route' => 'this should be a route',
                    'assignee_user_route' => 1,
                    'next_user_designation' => 1,
                    'next_user_department' => 1,
                    'next_user_unit' => 1,
                    'next_user_location' => 1,
                    'step_type' => 'create',
                    'user_type' => 'customer',
                    'status' => 1,
                ],
                [

                    'name' => 'test name single two test',
                    'step_route' => 'this should be a route',
                    'assignee_user_route' => 1,
                    'next_user_designation' => 1,
                    'next_user_department' => 1,
                    'next_user_unit' => 1,
                    'next_user_location' => 1,
                    'step_type' => 'create',
                    'user_type' => 'customer',
                    'status' => 1,
                ],
            ],
        ];

        $createdProcessFlow = $this->postJson('/api/processflows', $processFlowData);
        $processFlowId = $createdProcessFlow->json('data.id');
        $this->assertDatabaseHas('process_flow_steps', ['name' => 'test name single two test']);

        $response = $this->deleteJson('/api/processflows/' . $processFlowId);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('process_flow_steps', ['name' => 'test name single two test']);
        $this->assertDatabaseCount('process_flows', 0);
    }

    public function test_to_unauthorized_users_cannot_delete_a_processflow(): void
    {
        $this->actingAsUnAuthenticatedTestUser();
        $processFlowData = ProcessFlow::factory()->create();
        $processFlowId = $processFlowData->id;
        $response = $this->deleteJson('/api/processflows/' . $processFlowId);
        $response->assertStatus(401);
    }
    //FIXME:
    // public function test_to_invalid_processflow_id_throws_error(): void
    // {
    //     $this->actingAsAuthenticatedTestUser();
    //     $processFlowId = 99999;
    //     $response = $this->deleteJson('/api/processflows/' . $processFlowId);
    //     $response->assertStatus(404);
    // }

    public function test_that_all_active_created_processflow_can_be_fetched()
    {
        $this->actingAsAuthenticatedTestUser();
        ProcessFlow::factory(6)->create(["status" => 1]);
        $result = $this->getJson("/api/processflows");
        $result->assertOk()->assertJsonStructure([
            "data" => [
                [
                    'id',
                    'name',
                    'start_step_id',
                    'frequency',
                    'status',
                    'frequency_for',
                    'day',
                    'week',
                    'steps',
                ],

            ],
        ]);
        $this->assertEquals(6, count($result["data"]));
    }
    public function test_that_deactivated_processflow_would_not_be_fetched()
    {
        $this->actingAsAuthenticatedTestUser();
        ProcessFlow::factory(3)->create(["status" => 0]);
        $result = $this->getJson("/api/processflows");

        $this->assertEquals(0, count($result["data"]));
    }
}
