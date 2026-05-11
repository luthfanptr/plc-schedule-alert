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
        Schema::create('plc_data', function (Blueprint $table) {
            $table->integer('plc_id');
            $table->integer('mem_id');
            $table->string('plant');
            $table->integer('line');
            $table->string('line_name');
            $table->string('mem_data_name');
            $table->string('counter_limit');
            $table->integer('data_value');
            $table->dateTime('plc_date');

            $table->foreignId('line_id')->constrained('lines')->onDelete('cascade');
            $table->primary(['plc_id', 'mem_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plc_data');
    }
};
