<?php

namespace Tests\Unit\Queue;

use Tests\TestCase;
use App\Models\ProcessFlow;
use App\Models\ProcessFlowHistory;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\ProcessFlowHistory\ProcessFlowHistoryCreated;
use App\Jobs\ProcessFlowHistory\ProcessFlowHistoryDeleted;
use App\Jobs\ProcessFlowHistory\ProcessFlowHistoryUpdated;

class ProcessFlowHistoryQueueTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_dispatches_the_workflow_history_created_job()
    {
        Queue::fake();

        $workflowHistory = ProcessFlowHistory::factory()->create();
        ProcessFlowHistoryCreated::dispatch($workflowHistory->toArray());
        Queue::assertPushed(ProcessFlowHistoryCreated::class, function ($job) use ($workflowHistory) {
            return $job->getData() == $workflowHistory->toArray();
        });
    }

    public function test_it_dispatches_the_workflow_history_updated_job()
    {
        Queue::fake();
        $workflowHistory = ProcessFlowHistory::factory()->create();

        ProcessFlowHistoryUpdated::dispatch($workflowHistory->toArray());
        Queue::assertPushed(ProcessFlowHistoryUpdated::class, function ($job) use ($workflowHistory) {
            return $job->getData() == $workflowHistory->toArray();
        });
    }

    public function test_it_handles_workflow_history_deleted_job(): void
    {

        Queue::fake();
        $workflowHistory = ProcessFlowHistory::factory()->create();
        ProcessFlowHistoryDeleted::dispatch($workflowHistory->id);
        Queue::assertPushed(ProcessFlowHistoryDeleted::class, function ($job) use ($workflowHistory) {
            return $job->getId() == $workflowHistory->id;
        });
    }
}
