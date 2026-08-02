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
        Schema::create('plc_status_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('plc_id');
            $table->string('plant');
            $table->string('line')->nullable();
            $table->string('line_name')->nullable();
            $table->string('component_name');
            $table->integer('counter')->nullable();
            $table->integer('limit')->nullable();
            $table->string('status');
            $table->string('spk_number')->nullable();
            $table->string('spk_status')->nullable();
            $table->datetime('spk_start_date')->nullable();
            $table->datetime('spk_finish_date')->nullable();
            $table->integer('escalated')->nullable();
            $table->datetime('plc_date')->nullable();
            $table->datetime('resolved_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plc_status_logs');
    }
};
