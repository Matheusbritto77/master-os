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
        // Primeiro, vamos dropar a tabela users se ela existir
        Schema::dropIfExists('users');
        
        Schema::create('users', function (Blueprint $table) {
            // Chave primária auto increment
            $table->id();
            
            // Campos básicos do usuário
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            
            // Campos específicos do sistema
            $table->string('cpf_cnpj')->nullable()->unique();
            $table->string('codigo_unico')->nullable()->unique();
            $table->integer('revendedor_id')->nullable(); // Sem chave estrangeira
            
            // Campos de contato
            $table->string('phone')->nullable();
            $table->string('mobile_phone')->nullable();
            
            // Campos de endereço
            $table->string('address')->nullable();
            $table->string('address_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('external_reference')->nullable();
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}; 