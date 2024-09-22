<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProcessFlow;
use Illuminate\Http\Request;
use App\Models\ProcessFlowHistory;
use Illuminate\Support\Collection;
use App\Service\ProcessFlowHistoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Resources\ProcessFlowHistoryCollection;
use App\Http\Controllers\ProcessFlowHistoryController;

class ProcessFlowHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_create_new_processflowhistory_controller(): void
    {
        $this->actingAsAuthenticatedTestUser();
        $user = User::factory()->create();

        $processFlowHistoryData = [
            "task_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "for" => "customer",
            "for_id" => 1,
            "approval" => 1,
            "status" => 1,
        ];

        $response = $this->postJson('/api/processflowhistory/create', $processFlowHistoryData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('process_flow_histories', $processFlowHistoryData);
    }
    public function test_to_failed_when_unautheticated_try_to_access_processflowhistory_route(): void
    {

        $this->actingAsUnAuthenticatedTestUser();
        $processFlowHistoryData = [
            "task_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "for" => "customer",
            "for_id" => 1,
            "form_builder_id" => 1,
            "approval" => 1,
            "status" => 1,
        ];

        $response = $this->postJson('/api/processflowhistory/create', $processFlowHistoryData);
        $response->assertStatus(401);
    }

    //FIXME:

    // public function test_create_processflowhistory_controller_returns_validation_errors_for_invalid_data(): void
    // {

    //     $this->actingAsAuthenticatedTestUser();
    //     $user = User::factory()->create();
    //     $invalidData = [];
    //     $response = $this->postJson('/api/processflowhistory/create', $invalidData);
    //     $response->assertStatus(422);
    //     $response->assertJsonValidationErrors(['user_id', 'task_id']);
    //     $response->assertJsonStructure([
    //         'message',
    //         'errors' => [
    //             'user_id',
    //             'task_id',
    //             'step_id',
    //             'process_flow_id',
    //             'status',
    //         ],
    //     ]);
    // }
    public function test_if_all_workflow_can_be_fetched()
    {
        $this->actingAsAuthenticatedTestUser();
        ProcessFlowHistory::factory(3)->create(["status" => 1]);
        $response = $this->getJson("/api/processflowhistory");
        $response->assertOk()->assertJsonStructure(
            [
                "data" => [
                    [

                        "task_id",
                        "step_id",
                        "process_flow_id",
                        "user_id",
                        "status",
                        "for",
                        "for_id",
                        "approval",

                    ]

                ]
            ]
        );
    }


    public function test_it_can_get_a_single_processflowhistory(): void
    {
        $this->actingAsAuthenticatedTestUser();
        $processFlowHistory = ProcessFlowHistory::factory()->create();

        $response = $this->getJson('/api/processflowhistory/' . $processFlowHistory->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'task_id' => $processFlowHistory->task_id,
                'step_id' => $processFlowHistory->step_id,
                'process_flow_id' => $processFlowHistory->process_flow_id,
                'user_id' => $processFlowHistory->user_id,
                'status' => $processFlowHistory->status
            ],
        ]);
    }

    //FIXME:

    // public function test_it_returns_404_when_getting_a_non_existent_processflowhistory(): void
    // {
    //     $this->actingAsAuthenticatedTestUser();
    //     $response = $this->getJson('/api/processflowhistory/9999');
    //     $response->assertNotFound();
    // }

    public function test_it_returns_401_unauthenticated_for_non_logged_users(): void
    {
        $this->actingAsUnAuthenticatedTestUser();
        $processFlowHistory = ProcessFlowHistory::factory()->create();
        $response = $this->getJson('/api/processflowhistory/' . $processFlowHistory->id)->assertStatus(401);
    }



    public function test_to_delete_a_processflowhistory(): void
    {
        $this->actingAsAuthenticatedTestUser();
        $processFlowHistory = ProcessFlowHistory::factory()->create();

        $response = $this->deleteJson('/api/processflowhistory/' . $processFlowHistory->id);
        $response->assertStatus(204);
    }


    public function test_to_unauthorized_cannot_delete_a_processflowhistory(): void
    {
        $this->actingAsUnAuthenticatedTestUser();
        $processFlowHistory = ProcessFlowHistory::factory()->create();
        $response = $this->deleteJson('/api/processflowhistory/' . $processFlowHistory->id);
        $response->assertStatus(401);
    }




    public function test_it_can_get_all_processflowhistory(): void
    {
        $this->actingAsAuthenticatedTestUser();
        ProcessFlowHistory::factory()->count(5)->create();

        $response = $this->getJson('/api/processflowhistory');

        $response->assertStatus(200);


        $response->assertOk()->assertJsonStructure(
            [
                "data" => [
                    [

                        "task_id",
                        "step_id",
                        "process_flow_id",
                        "user_id",
                        "status",
                    ]

                ]
            ]
        );
    }

    public function test_it_returns_401_unauthenticated_to_get_all_units(): void
    {
        $this->actingAsUnAuthenticatedTestUser();
        ProcessFlowHistory::factory()->count(3)->create();
        $this->getJson('/api/processflowhistory/')->assertStatus(401);
    }
}
