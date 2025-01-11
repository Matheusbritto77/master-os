<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revendedor extends Model
{
    use HasFactory;

    // Defina a tabela associada a este modelo
    protected $table = 'revendedores'; // Certifique-se de que a tabela se chama 'revendedores'

    // Defina os atributos que podem ser preenchidos em massa
    protected $fillable = [
        'nome',
        'estado',
        'cidade',
        'bairro',
        'rua',
        'numero',
        'complemento',
        'cpf_cnpj',
        'chave_pix',
        'codigo_unico',
        'telefone',
        'comissao',
        'credito',
        'tipo',
        'email',        // Adicionado
        'senha',        // Adicionado
        'created_at',   // Adicionado (não é necessário, mas se quiser explicitar)
        'updated_at',   // Adicionado (não é necessário, mas se quiser explicitar)
    ];

    // Caso precise de uma definição adicional
    protected $casts = [
        'comissao' => 'decimal:2',
        'credito' => 'decimal:2',
        'tipo' => 'integer',
        'created_at' => 'datetime', // Opcional, para garantir que seja tratado como data
        'updated_at' => 'datetime', // Opcional, para garantir que seja tratado como data
    ];

    // Adicione funções adicionais, se necessário
}
