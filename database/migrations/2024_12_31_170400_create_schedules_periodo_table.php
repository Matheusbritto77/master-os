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
        Schema::create('schedules_periodo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedules_id')->constrained('schedules')->onDelete('cascade');
            $table->time('periodo_inicio');
            $table->time('periodo_fim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules_periodo');
    }
}; 