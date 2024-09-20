<?php

namespace Tests\Unit\Queue;

use Tests\TestCase;
use App\Models\ProcessFlowHistory;
use Illuminate\Support\Facades\Queue;
use App\Jobs\AutomatorTask\AutomatorTaskCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;


class AutomatorTaskCreatedQueueTest extends TestCase
{

    use RefreshDatabase;

    public function test_process_flow_history_can_be_created_from_automator()
    {
        $data = [
            "id" => 1,
            "processflow_history_id" => null,
            "formbuilder_data_id" => null,
            "entity" => "customer",
            "entity_id" => 1,
            "entity_site_id" => 1,
            "user_id" => 1,
            "processflow_id" => 1,
            "processflow_step_id" => 1,
            "task_status" => 0,
        ];
        $this->assertDatabaseCount("Process_flow_histories", 0);
        $job = new AutomatorTaskCreated($data);
        $job->handle();
        $this->assertDatabaseCount("Process_flow_histories", 1);
        $createdHistory = [
            "task_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "for" => "customer",
            "for_id" => 1,
            "approval" => 0,
            "status" => 0,
        ];
        $this->assertDatabaseHas("Process_flow_histories", $createdHistory);
    }
    public function test_process_flow_history_can_be_updated_from_automator()
    {
        $processFlowHistory = ProcessFlowHistory::factory()->create([
            "task_id" => null,

        ]);
        $data = [
            "id" => 1,
            "processflow_history_id" => $processFlowHistory->id,
            "formbuilder_data_id" => 1,
            "entity" => "customer",
            "entity_id" => 1,
            "entity_site_id" => 1,
            "user_id" => 1,
            "processflow_id" => $processFlowHistory->process_flow_id,
            "processflow_step_id" => $processFlowHistory->step_id,
            "task_status" => 0,
        ];
        $this->assertDatabaseCount("Process_flow_histories", 1);
        $job = new AutomatorTaskCreated($data);
        $job->handle();
        $this->assertDatabaseCount("Process_flow_histories", 1);
        $createdHistory = [
            "task_id" => 1,
        ];
        $this->assertDatabaseHas("Process_flow_histories", $createdHistory);
    }
}
