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
        Schema::create('funcionarios', function (Blueprint $table) {
            // Chave primária auto increment
            $table->id();
            
            // Relacionamento com usuário (sem chave estrangeira)
            $table->integer('user_id');
            
            // Campos dos funcionários
            $table->string('tecnico')->nullable(); // Nome do técnico
            $table->string('atendente')->nullable(); // Nome do atendente
            
            // Campos adicionais que podem ser úteis
            $table->string('email')->nullable(); // Email do funcionário
            $table->string('telefone')->nullable(); // Telefone do funcionário
            $table->string('cpf')->nullable(); // CPF do funcionário
            $table->string('cargo')->nullable(); // Cargo do funcionário
            $table->date('data_admissao')->nullable(); // Data de admissão
            $table->decimal('salario', 10, 2)->nullable(); // Salário do funcionário
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
        Schema::dropIfExists('funcionarios');
    }
}; 