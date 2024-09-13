<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessFlowHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        "task_id",
        "step_id",
        "process_flow_id",
        "user_id",
        "for",
        "for_id",
        "approval",
        "status",
    ];
}
