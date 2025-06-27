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
        Schema::create('ordems', function (Blueprint $table) {
            // Chave primária auto increment
            $table->id();
            
            // Relacionamento com usuário (sem chave estrangeira)
            $table->integer('user_id');
            
            // Dados do cliente
            $table->string('cliente');
            $table->string('phone_number');
            $table->string('cidade');
            $table->string('cep');
            $table->string('rua');
            $table->string('numero');
            $table->string('bairro');
            $table->string('state');
            
            // Dados do equipamento
            $table->string('modelo');
            $table->text('problema_relatado');
            $table->text('observacoes')->nullable();
            
            // Dados do técnico e atendente
            $table->string('tecnico');
            $table->string('atendente');
            
            // Campos adicionais que podem ser úteis
            $table->string('marca')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->integer('status')->default(0); // 0=Pendente, 1=Em andamento, 2=Concluído, 3=Cancelado
            $table->date('data_entrega')->nullable();
            $table->date('data_conclusao')->nullable();
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordems');
    }
}; 