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
        Schema::create('pagamentos', function (Blueprint $table) {
            // Chave primária auto increment
            $table->id();
            
            // Relacionamento com usuário (sem chave estrangeira)
            $table->integer('user_id');
            
            // Campos do pagamento
            $table->string('txid')->nullable(); // ID da transação PIX
            $table->string('charge_id')->nullable(); // ID da cobrança cartão
            $table->decimal('valor', 10, 2);
            $table->integer('status')->default(0); // 0=Pendente, 1=Pago, 2=Cancelado
            $table->integer('tipo'); // Tipo do plano (1=1 mês, 2=6 meses, 3=1 ano
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
}; 