<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    // Define o nome da tabela associada ao modelo
    protected $table = 'empresa';

    // Define quais campos podem ser preenchidos em massa
    protected $fillable = [
        'user_id',
        'nome',
        'telefone',
        'cep',
        'bairro',
        'rua',
        'estado',
        'cidade',
    ];

    // Define as colunas de data
    public $timestamps = true;

    // Relacionamento com o modelo User (muitos para um)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
