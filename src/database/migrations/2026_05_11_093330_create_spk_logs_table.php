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
        // ! ini adalah log history untuk SPK yang statusnya DONE saja
        Schema::create('spk_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('plc_id');
            $table->string('plant');
            $table->integer('line');
            $table->string('line_name');
            $table->string('component_name');
            $table->integer('counter');
            $table->integer('limit');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_update');
            $table->string('status'); // status mesin
            $table->enum('spk_status', ['progress', 'done'])->default('null');
            $table->dateTime('done_at', precision:0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_logs');
    }
};
