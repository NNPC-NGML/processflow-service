<?php

namespace App\Jobs\FormData;

use Illuminate\Http\Request;
use App\Services\FormService;
use Illuminate\Bus\Queueable;
use App\Service\ProcessFlowService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Service\ProcessFlowHistoryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\ProcessFlowHistory\ProcessFlowHistoryCreated;

class FormDataCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data for creating the unit.
     *
     * @var array
     */
    private array $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param FormService $service
     * @return void
     */
    public function handle(): void
    {
        //!empty($this->data["form_builder_id"])
        //decode json data to php array 
        $data = [
            "process_flow_id" => $this->data["data"]["form_builder"]["process_flow_id"],
            "user_id" => $this->data["data"]["user_id"],
        ];
        // if (!empty($this->data["data"]["form_field_answers"])) {
        //     $jsonStringConverted = json_decode($this->data["data"]["form_field_answers"], true);

        //     foreach ($jsonStringConverted as $value) {
        //         $data[$value["key"]] = $value["value"];
        //     }
        // }

        if (!empty($this->data["data"]["form_builder_id"]) && isset($this->data["data"]["form_builder"]["process_flow_id"])) {
            // get process flow and use its first step 
            $getProcessFlow = (new ProcessFlowService())->getProcessFlow((int) $this->data["data"]["form_builder"]["process_flow_id"]);
            if ($getProcessFlow) {
                // create process flow history from fetched process flow 
                $data["step_id"] = $getProcessFlow->start_step_id;
                $request = new Request($data);
                $createNewHistory = (new ProcessFlowHistoryService())->createProcessFlowHistory($request);
                if ($createNewHistory) {
                    $createNewHistory = $createNewHistory->toArray();
                    $createNewHistory["formbuilder_data_id"] = $this->data["data"]["id"];
                    //$createNewHistory["process_flow_id"] = $this->data["data"]["form_builder"]["process_flow_id"];
                    $createNewHistory["processflow_step_id"] = $getProcessFlow->start_step_id; //$this->data["data"]["form_builder"]["process_flow_step_id"];
                    $createNewHistory["user_id"] = $this->data["data"]["user_id"];
                    $createNewHistory["form_builder_id"] = $this->data["data"]["form_builder"]["id"];
                    // dispatch process flow history created
                    ProcessFlowHistoryCreated::dispatch($createNewHistory);
                }
            }
        }
    }
}


            
            // "entity" => $this->data["for"],
            // "entity_id" => $this->data["for_id"],
            // "entity_site_id" => $this->data["for_site_id"],
            // "user_id" => $this->data["user_id"],
            // "processflow_id" => $this->data["process_flow_id"],
            // "processflow_step_id" => $this->data["processflow_step_id"],
            // "form_builder_id" => $this->data["form_builder_id"],
            // "task_id" => $this->data["task_id"],