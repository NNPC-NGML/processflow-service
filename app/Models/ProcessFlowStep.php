<?php

namespace App\Models;

use App\Models\ProcessFlow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessFlowStep extends Model
{
    use HasFactory;
    public const ACTIVE = 1;
    protected $fillable = [
        'name',
        'step_route',
        'assignee_user_route',
        'next_user_designation',
        'next_user_department',
        'next_user_unit',
        'process_flow_id',
        'next_user_location',
        'step_type',
        'user_type',
        'next_step_id',
        'status',
    ];

    public function process(): BelongsTo
    {
        return $this->belongsTo(ProcessFlow::class);
    }
}
