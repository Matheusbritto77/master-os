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
        Schema::create('garantias', function (Blueprint $table) {
            // Chave primária auto increment
            $table->id();
            
            // Relacionamento com usuário (sem chave estrangeira)
            $table->integer('user_id');
            
            // Dados do cliente
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('cep')->nullable();
            $table->string('city')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->string('state')->nullable();
            
            // Dados da garantia
            $table->string('tipoGarantia')->nullable();
            $table->string('nomeProduto')->nullable();
            $table->string('tempoGarantiaProduto')->nullable();
            $table->text('servicoRealizado')->nullable();
            $table->string('modeloAparelho')->nullable();
            $table->string('tempoGarantiaServico')->nullable();
            $table->text('observacoes')->nullable();
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garantias');
    }
}; 