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
        Schema::create('notificacoes', function (Blueprint $table) {
            // Chave primária auto increment
            $table->id();
            
            // Relacionamento com usuário (sem chave estrangeira)
            $table->integer('usuario_id');
            
            // Campos da notificação
            $table->string('tipo'); // geral, diaria, travamento, usuario_especifico
            $table->string('titulo');
            $table->text('mensagem');
            $table->timestamp('exibida_em')->nullable(); // Quando foi exibida
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
}; 