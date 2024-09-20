<?php

namespace Tests\Unit\Queue;

use Tests\TestCase;
use App\Models\ProcessFlow;
use Illuminate\Support\Facades\Queue;
use App\Jobs\FormData\FormDataCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;


class FormDataCreatedQueueTest extends TestCase
{

    use RefreshDatabase;

    public function test_process_flow_history_can_be_created()
    {
        //create process flow
        $processFlow = ProcessFlow::factory()->create(["start_step_id" => 1]);

        $data = [

            "form_builder_id" => 1,
            "id" => 1,
            "user_id" => 1,
            "form_builder" => [
                "id" => 1,
                "process_flow_id" => $processFlow->id,
                "process_flow_step_id" => 1
            ],
            "form_field_answers" => json_encode([
                ["id" => 1, "elementType" => "text", "name" => "company_name", "placeholder" => "Enter company name", "key" => "test_q", "value" => $processFlow->id],
                ["id" => 1, "elementType" => "text", "name" => "company_name", "placeholder" => "Enter company name", "key" => "test_e", "value" => 1]
            ])

        ];
        $this->assertDatabaseEmpty("process_flow_histories");
        $job = new FormDataCreated($data);
        $job->handle();
        //$this->assertDatabaseEmpty("process_flow_histories");
        $this->assertDatabaseCount("process_flow_histories", 1);
    }
}
