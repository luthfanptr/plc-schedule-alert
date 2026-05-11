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
        //! ini tabel untk dashboard teknisi, update status spk
        Schema::create('plc_notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('plc_id');
            $table->string('plant');
            $table->integer('line');
            $table->string('line_name');
            $table->string('component_name');
            $table->integer('counter');
            $table->integer('limit');
            $table->string('status'); // status mesin
            $table->enum('spk_status', ['progress', 'done'])->default('null'); // status spk
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plc_notifications');
    }
};
