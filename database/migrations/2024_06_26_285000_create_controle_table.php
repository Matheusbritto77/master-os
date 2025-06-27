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
        Schema::create('controle', function (Blueprint $table) {
            // Chave primária auto increment
            $table->id();
            
            // Relacionamento com usuário (sem chave estrangeira)
            $table->integer('user_id');
            
            // Campos de controle
            $table->string('password')->nullable();
            $table->boolean('ajustes')->default(false);
            $table->boolean('historico_de_caixa')->default(false);
            $table->boolean('excluir_caixa')->default(false);
            $table->boolean('relatorio_bruto')->default(false);
            $table->boolean('relatorio_lucro')->default(false);
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('controle');
    }
}; 