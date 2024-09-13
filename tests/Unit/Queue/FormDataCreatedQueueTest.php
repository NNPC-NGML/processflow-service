<?php

namespace Tests\Unit\Queue;

use Tests\TestCase;
use App\Models\ProcessFlow;

use App\Models\ProcessFlowStep;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\ProcessflowStep\ProcessflowStepCreated;
use App\Jobs\ProcessflowStep\ProcessflowStepDeleted;
use App\Jobs\ProcessflowStep\ProcessflowStepUpdated;

class FormDataCreatedQueueTest extends TestCase
{

    use RefreshDatabase;

    public function test_process_flow_history_can_be_created(){
        
    }

    

   
}
