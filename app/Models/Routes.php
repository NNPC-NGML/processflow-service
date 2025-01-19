<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Routes extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'link',
        'status',
        'dynamic_content'
    ];
}
