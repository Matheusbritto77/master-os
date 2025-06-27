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
        Schema::create('planos', function (Blueprint $table) {
            // Chave primária auto increment
            $table->id();
            
            // Campos do plano
            $table->integer('tipo'); // 0=Free, 1=1 mês, 2=6 meses, 3=1 ano
            $table->decimal('valor', 10, 2);
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos');
    }
}; 