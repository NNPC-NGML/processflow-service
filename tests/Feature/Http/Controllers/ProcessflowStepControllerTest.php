<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\ProcessFlow;
use App\Models\ProcessFlowStep;
use App\Service\ProcessFlowService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessflowStepControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */

    // test to see if the process flow exist
    // test to see if the process flow has a defaul start step already
    //tess data validations
    // test that a new step can be created
    // test that processflow start step can be updated (note : only if it has not been previously set)
    // test to see if created steps can update its next xtep column

    public function test_to_see_if_processflow_step_can_be_created_and_update_start_step_id_with_the_first_created_step(): void
    {
        $this->actingAsAuthenticatedTestUser();
        // super user

        // create two processflow
        $data = [
            "steps" =>
            [

                [
                    "name" => "test name",
                    "step_route" => "this should be a route",
                    "assignee_user_route" => 1,
                    "next_user_designation" => 1,
                    "next_user_department" => 1,
                    "next_user_unit" => 1,
                    "process_flow_id" => 1,
                    "next_user_location" => 1,
                    "step_type" => "create",
                    "user_type" => "customer",
                    "status" => 1,
                ],
                [
                    "name" => "test name 2",
                    "step_route" => "this should be a route 2",
                    "assignee_user_route" => 1,
                    "next_user_designation" => 1,
                    "next_user_department" => 1,
                    "next_user_unit" => 1,
                    "process_flow_id" => 1,
                    "next_user_location" => 1,
                    "step_type" => "create",
                    "user_type" => "customer",
                    "status" => 1,
                ],
            ],
        ];

        $this->createProcessflow(2);
        $response = $this->postJson('api/processflowstep/create/1', $data);
        $this->assertDatabaseCount("process_flows", 2);
        $this->assertDatabaseCount("process_flow_steps", 2);
        $this->assertDatabaseHas("process_flow_steps", [
            "name" => "test name",
            "step_route" => "this should be a route",
            "assignee_user_route" => 1,
            "next_user_designation" => 1,
            "next_user_department" => 1,
            "next_user_unit" => 1,
            "process_flow_id" => 1,
            "next_user_location" => 1,
            "step_type" => "create",
            "user_type" => "customer",
            "status" => 1,
        ],);
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
        $response->assertStatus(200);
    }
    //FIXME:

    // public function tests_to_see_if_processflow_does_not_exist()
    // {
    //     $this->actingAsAuthenticatedTestUser();
    //     $data = [
    //         "steps" =>
    //         [

    //             [
    //                 "name" => "test name",
    //                 "step_route" => "this should be a route",
    //                 "assignee_user_route" => 1,
    //                 "next_user_designation" => 1,
    //                 "next_user_department" => 1,
    //                 "next_user_unit" => 1,
    //                 "process_flow_id" => 1,
    //                 "next_user_location" => 1,
    //                 "step_type" => "create",
    //                 "user_type" => "customer",
    //                 "status" => 1,
    //             ],

    //         ],
    //     ];

    //     $response = $this->postJson('api/processflowstep/create/1', $data);
    //     $response->assertStatus(404);
    // }

    public function test_to_see_if_we_can_create_a_new_process_flow_steps_for_a_process_flow_that_already_has_a_start_step_id()
    {
        $this->actingAsAuthenticatedTestUser();
        $data = [
            "steps" =>
            [

                [
                    "name" => "test name",
                    "step_route" => "this should be a route",
                    "assignee_user_route" => 1,
                    "next_user_designation" => 1,
                    "next_user_department" => 1,
                    "next_user_unit" => 1,
                    "process_flow_id" => 1,
                    "next_user_location" => 1,
                    "step_type" => "create",
                    "user_type" => "customer",
                    "status" => 1,
                ],
                [
                    "name" => "test name 2",
                    "step_route" => "this should be a route 2",
                    "assignee_user_route" => 1,
                    "next_user_designation" => 1,
                    "next_user_department" => 1,
                    "next_user_unit" => 1,
                    "process_flow_id" => 1,
                    "next_user_location" => 1,
                    "step_type" => "create",
                    "user_type" => "customer",
                    "status" => 1,
                ],
            ],
        ];

        $this->createProcessflow(1, true);

        ProcessFlowStep::factory()->create(["process_flow_id" => 1]);
        $response = $this->postJson('api/processflowstep/create/1', $data);

        $this->assertDatabaseCount("process_flows", 1);
        $this->assertDatabaseCount("process_flow_steps", 3);
        $this->assertDatabaseHas("process_flow_steps", [

            "next_step_id" => 2,
        ],);
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
        $getProcessFlow = (new ProcessFlowService())->getProcessFlow(1);
        $this->assertEquals($getProcessFlow->start_step_id, 8);
        $response->assertStatus(200);
    }

    public function test_toSee_if_we_can_create_a_new_process_flow_steps_for_a_process_flow_that_already_has_a_start_step_id_and_new_step_is_not_more_than_1()
    {
        $this->actingAsAuthenticatedTestUser();

        $data = [
            "steps" =>
            [

                [
                    "name" => "test name",
                    "step_route" => "this should be a route",
                    "assignee_user_route" => 1,
                    "next_user_designation" => 1,
                    "next_user_department" => 1,
                    "next_user_unit" => 1,
                    "process_flow_id" => 1,
                    "next_user_location" => 1,
                    "step_type" => "create",
                    "user_type" => "customer",
                    "status" => 1,
                ],

            ],
        ];

        $this->createProcessflow(1, true);

        ProcessFlowStep::factory()->create(["process_flow_id" => 1]);
        $response = $this->postJson('api/processflowstep/create/1', $data);

        $this->assertDatabaseCount("process_flows", 1);
        $this->assertDatabaseCount("process_flow_steps", 2);
        $this->assertDatabaseHas("process_flow_steps", [

            "next_step_id" => 2,
        ],);
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
        $getProcessFlow = (new ProcessFlowService())->getProcessFlow(1);
        $this->assertEquals($getProcessFlow->start_step_id, 8);
        $response->assertStatus(200);
    }

    public function test_to_see_if_a_processflow_step_can_be_deleted_and_next_step_id_also_adjusted()
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
        $deleteResponse = $this->deleteJson('/api/processflowstep/delete/' . $response["data"]["steps"][1]["id"]);
        $deleteResponse->assertStatus(204);
        $this->assertDatabaseMissing("process_flow_steps", ["next_step_id" => 2]);
        $this->assertDatabaseHas("process_flow_steps", ["next_step_id" => null]);
        $this->assertDatabaseCount("process_flow_steps", 1)->assertDatabaseMissing("process_flow_steps", ["id" => 2]);
    }

    public function test_to_see_if_processflow_start_step_can_be_updated()
    {
        $this->actingAsAuthenticatedTestUser();
        $createdProcessflowWithSteps = $this->createProcessflowWithSteps();
        $data = [
            "step" => [

                [
                    "id" => 2,
                ],

                [
                    "id" => 1,
                ],
            ],

        ];
        $response = $this->putJson('api/processflowstep/update/1', $data);
        $response->assertOk();
        $this->assertDatabaseHas("process_flows", [
            "start_step_id" => 2,
        ],);
    }

    public function test_to_see_if_processflow_first_step_first_step_details_stestart_step_can_be_updated()
    {
        $this->actingAsAuthenticatedTestUser();
        $createdProcessflowWithSteps = $this->createProcessflowWithSteps();
        $data = [
            "step" => [

                [
                    "id" => 2,
                    "name" => "test name 2 main fake",
                    "next_step_id" => 1,
                ],

                [
                    "id" => 1,
                    "name" => "test name made last step",
                    "step_route" => "this should be a route",

                    "next_step_id" => null,
                ],
            ],

        ];
        $response = $this->putJson('api/processflowstep/update/1', $data);
        $response->assertOk();
        $this->assertDatabaseHas("process_flow_steps", [
            "name" => "test name 2 main fake",
            "next_step_id" => 1,
            "id" => 2,
        ],);
    }

    public function test_to_see_if_an_error_would_be_displayed_if_there_is_no_request_data_while_updating_process_flow_steps()
    {
        $this->actingAsAuthenticatedTestUser();
        $createdProcessflowWithSteps = $this->createProcessflowWithSteps();
        $data = [];
        $response = $this->putJson('api/processflowstep/update/1', $data);

        $this->assertArrayHasKey('step', $response);
    }

    public function test_to_see_if_a_processflow_step_id_is_wrong()
    {
        $this->actingAsAuthenticatedTestUser();
        $response = $this->getJson('api/processflowstep/view/5');
        $response->assertStatus(404);
    }

    public function test_to_see_if_the_expected_data_structure_is_retuned()
    {
        $this->actingAsAuthenticatedTestUser();
        $test = $this->createProcessflowWithSteps();

        $response = $this->getJson('api/processflowstep/view/1');

        $response->assertStatus(200)->assertJsonStructure([

            'data' => [
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

        ])->assertJsonFragment([
            "name" => "test name",
            "step_route" => "this should be a route",
            "assignee_user_route" => "1",
            "next_user_designation" => 1,
            "next_user_department" => 1,
            "next_user_unit" => 1,
            "process_flow_id" => 1,
            "next_user_location" => 1,
            "step_type" => "create",
            "user_type" => "customer",
            "status" => 1,
        ]);
    }

    private function createProcessflow(int $quantity = 1, $withStartStep = false): void
    {
        if (!$withStartStep) {
            ProcessFlow::factory($quantity)->create();
        } else {

            ProcessFlow::factory($quantity)->create(["start_step_id" => 8]);
        }
    }

    private function createProcessflowWithSteps()
    {
        $this->actingAsAuthenticatedTestUser();
        $data = [
            "steps" =>
            [

                [
                    "name" => "test name",
                    "step_route" => "this should be a route",
                    "assignee_user_route" => 1,
                    "next_user_designation" => 1,
                    "next_user_department" => 1,
                    "next_user_unit" => 1,
                    "process_flow_id" => 1,
                    "next_user_location" => 1,
                    "step_type" => "create",
                    "user_type" => "customer",
                    "status" => 1,
                ],
                [
                    "name" => "test name 2",
                    "step_route" => "this should be a route 2",
                    "assignee_user_route" => 1,
                    "next_user_designation" => 1,
                    "next_user_department" => 1,
                    "next_user_unit" => 1,
                    "process_flow_id" => 1,
                    "next_user_location" => 1,
                    "step_type" => "create",
                    "user_type" => "customer",
                    "status" => 1,
                ],
            ],
        ];

        $this->createProcessflow(1);
        $response = $this->postJson('api/processflowstep/create/1', $data);
        return $response;
    }

    public function test_that_all_active_created_processflow_step_can_be_fetched()
    {
        $this->actingAsAuthenticatedTestUser();
        ProcessFlowStep::factory(6)->create(["status" => 1]);
        $result = $this->getJson("/api/processflowstep");
        $result->assertOk()->assertJsonStructure([
            "data" => [
                [
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
        ]);
        $this->assertEquals(6, count($result["data"]));
    }
    public function test_that_deactivated_processflow_step_would_not_be_fetched()
    {
        $this->actingAsAuthenticatedTestUser();
        ProcessFlowStep::factory(3)->create(["status" => 0]);
        $result = $this->getJson("/api/processflowstep");

        $this->assertEquals(0, count($result["data"]));
    }
}
