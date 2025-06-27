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
        Schema::create('clientes', function (Blueprint $table) {
            // Chave primária auto increment
            $table->id();
            
            // Relacionamento com usuário (sem chave estrangeira)
            $table->integer('user_id');
            
            // Dados básicos do cliente
            $table->string('name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('cpf_cnpj');
            
            // Dados de endereço
            $table->string('cep');
            $table->string('state');
            $table->string('city');
            $table->string('neighborhood');
            $table->string('street');
            $table->string('house_number');
            
            // Campos adicionais que podem ser úteis
            $table->string('complemento')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->text('observacoes')->nullable();
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
        Schema::dropIfExists('clientes');
    }
}; 