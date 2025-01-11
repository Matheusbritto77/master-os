<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Assinatura extends Model
{
    protected $table = 'assinaturas';

    protected $fillable = [
        'user_id', 'tipo', 'status', 'data_inicio', 'data_fim'
    ];

    protected $dates = ['data_inicio', 'data_fim'];
}
