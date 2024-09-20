<?php

namespace App\Jobs\AutomatorTask;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Service\ProcessFlowHistoryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\ProcessFlowHistory\ProcessFlowHistoryCreated;

class AutomatorTaskCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data for creating the designation.
     *
     * @var array
     */
    private $data;

    /**
     * Create a new job instance.
     *
     * @param array $data The data for creating the designation
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $data = [
            "task_id" => $this->data["id"],
            "step_id" => $this->data["processflow_step_id"],
            "process_flow_id" => $this->data["processflow_id"],
            "user_id" => $this->data["user_id"],
            "for" => $this->data["entity"],
            "for_id" => $this->data["entity_id"],
            "approval" => $this->data["task_status"],
            "status" => $this->data["task_status"],
        ];

        if ($this->data["processflow_history_id"] == null) {
            $request = new Request($data);
            $createNewHistory = (new ProcessFlowHistoryService())->createProcessFlowHistory($request);
            if ($createNewHistory) {
                $createNewHistory = $createNewHistory->toArray();
                $createNewHistory["processflow_step_id"] = $createNewHistory['step_id'];
                ProcessFlowHistoryCreated::dispatch($createNewHistory);
            }
        } else {
            //get process flow history 
            $history = (new ProcessFlowHistoryService())->getProcessFlowHistory((int) $this->data["processflow_history_id"]);
            if ($history) {
                // update history 
                $request = new Request($data);
                $updateHistory = (new ProcessFlowHistoryService())->updateProcessFlowHistory($request, (int) $this->data["processflow_history_id"]);
                // log the data below to be sure it is working 
            }
        }
    }
}
