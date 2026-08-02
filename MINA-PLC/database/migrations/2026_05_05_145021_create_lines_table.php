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
        Schema::create('lines', function (Blueprint $table) {
            $table->id();
            $table->string('master_line_id')->unique(); // sync dari MS_LINE.LINE_ID | ambil dari seeder
            $table->integer('Line'); // ?GIMANA CARA narik data line dari MS_MC
            $table->string('Name'); // MS_LINE
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lines');
    }
};
