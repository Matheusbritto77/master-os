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
        Schema::create('caixas', function (Blueprint $table) {
            // Chave primária auto increment
            $table->id();
            
            // Relacionamento com usuário (sem chave estrangeira)
            $table->integer('user_id');
            
            // Campos de movimentação financeira
            $table->decimal('valor_inicial', 10, 2)->nullable(); // Valor inicial do caixa
            $table->decimal('venda', 10, 2)->nullable(); // Valor de vendas
            $table->decimal('saida', 10, 2)->nullable(); // Valor de saídas
            $table->decimal('despesa_fixa', 10, 2)->nullable(); // Valor de despesas fixas
            $table->decimal('total_dia', 10, 2)->nullable(); // Total do dia
            
            // Campo de descrição
            $table->string('descricao')->nullable(); // Descrição da movimentação
            
            // Campos adicionais que podem ser úteis
            $table->string('tipo_movimentacao')->nullable(); // Tipo da movimentação (venda, saída, despesa)
            $table->string('forma_pagamento')->nullable(); // Forma de pagamento
            $table->text('observacoes')->nullable(); // Observações adicionais
            $table->integer('status')->default(1); // 1=Ativo, 0=Inativo
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caixas');
    }
}; 