<?php

namespace App\Jobs\ProcessFlow;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFlowUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   private $data;
public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {

    }

     public function getData(): array
    {
        return $this->data;
    }
}