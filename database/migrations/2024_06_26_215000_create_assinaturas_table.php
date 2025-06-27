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
        Schema::create('assinaturas', function (Blueprint $table) {
            // Chave primária auto increment
            $table->id();
            
            // Relacionamento com usuário (sem chave estrangeira)
            $table->integer('user_id');
            
            // Tipo de assinatura (0=Free, 1=1 mês, 2=6 meses, 3=1 ano)
            $table->integer('tipo')->default(0);
            
            // Status da assinatura (0=Inativa, 1=Ativa)
            $table->integer('status')->default(0);
            
            // Datas da assinatura
            $table->timestamp('data_inicio')->nullable();
            $table->timestamp('data_fim')->nullable();
            
            // ID do cliente no sistema de pagamento (Assas)
            $table->string('customer_id')->nullable();
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
}; 