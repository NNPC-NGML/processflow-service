<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('process_flow_histories', function (Blueprint $table) {
            $table->id();
            $table->integer("task_id")->comment()->nullable();
            $table->integer("step_id")->comment();
            $table->integer("process_flow_id")->comment();
            $table->integer("user_id")->comment();
            $table->integer("for")->comment()->nullable();
            $table->integer("for_id")->comment()->nullable();
            $table->boolean("approval")->comment()->default(0);
            $table->boolean("status")->comment()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_flow_histories');
    }
};
