<?php

namespace Tests\Unit;

use App\Models\ProcessFlow;
use App\Models\ProcessFlowStep;
use App\Service\ProcessflowStepService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use PHPUnit\Framework\TestCase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProcessFlowStepTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */

    public function test_to_see_if_the_processfolow_was_created(): void
    {
        $data = new Request([
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
            "next_step_id" => 2,
            "status" => 1,
        ]);
        $createNewProcessFlowStepService = new ProcessflowStepService();
        $createNewProcessFlowStep = $createNewProcessFlowStepService->createProcessFlowStep($data);
        $this->assertDatabaseHas('process_flow_steps', [
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
            "next_step_id" => 2,
            "status" => 1,
        ]);

        $this->assertInstanceOf(ProcessFlowStep::class, $createNewProcessFlowStep);

        $this->assertNotNull($createNewProcessFlowStep->id);
        $this->assertSame('test name', $createNewProcessFlowStep->name);
        $this->assertSame('this should be a route', $createNewProcessFlowStep->step_route);

        //$this->assertTrue($createNewProcessFlowStep);

    }

    public function test_to_see_if_an_error_happens_when_creating_a_processfolow(): void
    {
        $data = new Request([
            "name" => "test name 2",
        ]);
        $createNewProcessFlowStepService = new ProcessflowStepService();
        $createNewProcessFlowStep = $createNewProcessFlowStepService->createProcessFlowStep($data);
        $resultArray = $createNewProcessFlowStep->toArray();
        $this->assertNotEmpty($createNewProcessFlowStep);
        $this->assertIsArray($resultArray);
        $this->assertArrayHasKey('step_route', $resultArray);
        $this->assertArrayHasKey('step_type', $resultArray);
    }

    public function test_to_get_a_processflow_step_with_id(): void
    {
        $data = new Request([
            "name" => "test name single",
            "step_route" => "this should be a route",
            "assignee_user_route" => 1,
            "next_user_designation" => 1,
            "next_user_department" => 1,
            "next_user_unit" => 1,
            "process_flow_id" => 1,
            "next_user_location" => 1,
            "step_type" => "create",
            "user_type" => "customer",
            "next_step_id" => 2,
            "status" => 1,
        ]);
        $service = new ProcessflowStepService();

        $result = $service->createProcessFlowStep($data);

        $getStep = $service->getProcessFlowStep($result->id);
        $this->assertDatabaseCount('process_flow_steps', 1);
        $this->assertInstanceOf(ProcessFlowStep::class, $result);
        $this->assertEquals($getStep->id, $result->id);
    }

    public function test_to_returns_null_if_not_found_or_invalid_id_for_processflow_step(): void
    {
        $this->expectException(\Exception::class);

        $service = new ProcessFlowStepService();
        $foundStep = $service->getProcessFlowStep(999);
    }

    public function test_to_update_a_processflow_step_successfully(): void
    {

        $create = ProcessFlowStep::factory()->create();
        $service = new ProcessflowStepService();
        $update = $service->updateProcessFlowStep(new Request(["name" => "test name updated"]), $create->id);

        $this->assertDatabaseHas('process_flow_steps', [
            "name" => "test name updated",
        ]);
        $this->assertInstanceOf(ProcessFlowStep::class, $update);
    }

    public function test_to_update_throws_exception__process_flow_step_for_error(): void
    {
        $this->expectException(\Exception::class);
        $request = new Request([
            'name' => 'Test Step',
        ]);
        $id = 0;
        $service = new ProcessflowStepService();

        $service->updateProcessFlowStep($request, $id);
        $this->expectException(ModelNotFoundException::class);
    }

    public function test_to_delete_a_processflow_step_successfully(): void
    {
        $processflowModel = ProcessFlow::factory()->create(["start_step_id" => 1]);
        $create = ProcessFlowStep::factory()->create(["process_flow_id" => $processflowModel->id]);
        $service = new ProcessflowStepService();
        $delete = $service->deleteProcessFlowStep($create->id);

        $this->assertDatabaseCount('process_flow_steps', 0);
        $this->assertTrue($delete);
    }

    public function test_to_throw_exception_or_error_if_processflow_step_not_found_or_invalid_id(): void
    {
        $this->expectException(\Exception::class);
        $service = new ProcessflowStepService();
        $delete = $service->deleteProcessFlowStep(0);
    }

    public function test_to_see_if_all_process_flow_steps_can_be_fetched()
    {
        ProcessFlowStep::factory(5)->create(["status" => 1]);
        $processFlowStepService = (new ProcessFlowStepService())->getAllProcessFlowStep();
        $this->assertInstanceOf(ProcessFlowStep::class, $processFlowStepService[0]);
        $this->assertEquals(5, count($processFlowStepService->toArray()));
    }

    public function test_only_routes_with_status_true_can_be_fetched()
    {
        ProcessFlowStep::factory(5)->create(["status" => 0]);
        $processFlowStepService = (new ProcessFlowStepService())->getAllProcessFlowStep();
        $this->assertEquals(0, count($processFlowStepService->toArray()));
    }
}
