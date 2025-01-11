<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plano extends Model
{
    use HasFactory;

    // Definindo a tabela associada ao modelo
    protected $table = 'planos';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'tipo',
        'valor',
    ];

    // Se os campos de timestamp forem gerados automaticamente
    public $timestamps = true;

   
}
