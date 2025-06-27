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
        // Verifica se a tabela já existe antes de criar
        if (!Schema::hasTable('revendedores')) {
            Schema::create('revendedores', function (Blueprint $table) {
                // Chave primária auto increment
                $table->id();
                
                // Campos do revendedor
                $table->string('nome');
                $table->string('email')->unique();
                $table->string('codigo_unico')->unique();
                $table->string('telefone')->nullable();
                $table->string('cpf_cnpj')->nullable();
                
                // Timestamps
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revendedores');
    }
}; 