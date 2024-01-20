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
        Schema::create('process_flows', function (Blueprint $table) {
            $table->id();
            $table->string("name")->comment("The process flow name ");
            $table->integer("start_step_id")->comment("The process flow step id ")->nullable();
            $table->enum("frequency", ['daily', 'weekly', 'hourly', 'monthly', 'yearly', 'none'])->default('none');
            $table->integer("status")->comment("process status eg 1 for active status and 0 for deactivated status ")->default(1);
            $table->enum("frequency_for", ['users', 'customers', 'suppliers', 'contractors', 'none'])->default('none');
            $table->string("day")->comment("day is for selecting a particular day for the frequency")->nullable();
            $table->string("week")->comment("week is for selecting a particular week for the frequency")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_flows');
    }
};
