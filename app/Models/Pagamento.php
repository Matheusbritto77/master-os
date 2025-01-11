<?php

// app/Models/Pagamento.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = ['txid', 'valor', 'user_id', 'status', 'tipo']; // Adicione 'tipo' aqui
}
