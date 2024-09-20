<?php

namespace Tests\Unit;

use App\Models\ProcessFlowHistory;
use App\Service\ProcessFlowHistoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProcessFlowHistoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_to_see_if_the_workflowhistory_was_created(): void
    {
        $data = new Request([
            "task_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "for" => "customer",
            "for_id" => 1,
            "approval" => 1,
            "status" => 1,
        ]);
        $createNewWorkflowHistoryService = new ProcessFlowHistoryService();
        $createNewProcessFlowHistory = $createNewWorkflowHistoryService->createProcessFlowHistory($data);
        $this->assertDatabaseHas('process_flow_histories', [
            "task_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "for" => "customer",
            "for_id" => 1,
            "approval" => 1,
            "status" => 1,
        ]);

        $this->assertInstanceOf(ProcessFlowHistory::class, $createNewProcessFlowHistory);
    }

    public function test_to_see_if_an_error_happens_when_creating_a_workflowhistory(): void
    {
        $data = new Request([
            "task_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "for" => "customer",
            "for_id" => 1,
            "approval" => 1,
            "status" => 1,
        ]);
        $createNewWorkflowHistoryService = new ProcessFlowHistoryService();
        $createNewWorkflowHistory = $createNewWorkflowHistoryService->createProcessFlowHistory($data);
        $resultArray = $createNewWorkflowHistory->toArray();
        $this->assertNotEmpty($createNewWorkflowHistory);
        $this->assertIsArray($resultArray);
        $this->assertArrayHasKey('task_id', $resultArray);
    }

    public function test_to_see_if_a_workflowhistory_can_be_fetched(): void
    {

        $data = new Request([
            "task_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "for" => "customer",
            "for_id" => 1,
            "approval" => 1,
            "status" => 1,
        ]);

        $createNewWorkflowHistoryService = new ProcessFlowHistoryService();
        $result = $createNewWorkflowHistoryService->createProcessFlowHistory($data);
        $fetchService = $createNewWorkflowHistoryService->getProcessFlowHistory($result->id);
        $this->assertEquals($fetchService->id, $result->id);
        $this->assertInstanceOf(ProcessFlowHistory::class, $fetchService);
    }

    public function test_to_see_if_workflowhistory_returns_a_content(): void
    {

        $this->expectException(ModelNotFoundException::class);
        $createNewWorkflowHistoryService = new ProcessFlowHistoryService();
        $fetchService = $createNewWorkflowHistoryService->getProcessFlowHistory(1);
        // $this->assertNull($fetchService);

    }

    public function test_to_update_a_workflowhistory_successfully(): void
    {

        $create  = ProcessFlowHistory::factory()->create();
        $service = new ProcessFlowHistoryService();
        $update  = $service->updateProcessFlowHistory(new Request(["status" => 1,]), $create->id);


        $this->assertDatabaseHas('process_flow_histories', [
            "status" => 1,
        ]);
        $this->assertInstanceOf(ProcessFlowHistory::class, $update);
    }

    public function test_to_update_throws_exception_workflowhistory_for_error(): void
    {
        $this->expectException(\Exception::class);
        $request = new Request([
            'status' => 1,
        ]);
        $id      = 0;
        $service = new ProcessFlowHistoryService();

        $service->updateProcessFlowHistory($request, $id);
        $this->expectException(ModelNotFoundException::class);
    }

    public function test_to_if_a_workflowhistory_can_be_deleted()
    {
        $data = new Request([
            "task_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "for" => "customer",
            "for_id" => 1,
            "approval" => 1,
            "status" => 1,
        ]);

        $createNewWorkflowHistoryService = new ProcessFlowHistoryService();
        $data = $createNewWorkflowHistoryService->createProcessFlowHistory($data);
        $this->assertDatabaseCount("process_flow_histories", 1);
        $delete = $createNewWorkflowHistoryService->deleteProcessFlowHistory($data->id);
        $this->assertDatabaseMissing("process_flow_histories", ["task_id" => 1]);
        $this->assertTrue($delete);
    }

    public function test_to_see_if_there_is_no_record_with_the_provided_id()
    {
        $createNewWorkflowHistoryService = new ProcessFlowHistoryService();
        $delete = $createNewWorkflowHistoryService->deleteProcessFlowHistory(1);
        $this->assertFalse($delete);
    }

    public function test_fetch_all_process_flow_histories(): void
    {
        ProcessFlowHistory::factory(3)->create(["status" => 1]);
        $workflowHistoryService = new ProcessFlowHistoryService();
        $workflowHistories = $workflowHistoryService->getProcessFlowHistories();
        $this->assertInstanceOf(Collection::class, $workflowHistories);
        foreach ($workflowHistories as $workflowHistory) {
            $this->assertInstanceOf(ProcessFlowHistory::class, $workflowHistory);
        }

        $this->assertEquals(3, count($workflowHistories->toArray()));
    }
}
