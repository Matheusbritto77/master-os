<?php

namespace App\Http\Controllers\Ajustes;

use App\Http\Controllers\Controller; // Corrija a importação
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayController extends Controller
{
    public function verificarAssinatura(Request $request)
    {
        // Obtém o usuário logado
        $user = $request->user();

        // Busca a assinatura do usuário logado
        $assinatura = DB::table('assinaturas')
            ->where('user_id', $user->id)
            ->select('tipo', 'status', 'data_inicio', 'data_fim')
            ->first();

        // Verifica se a assinatura foi encontrada
        if (!$assinatura) {
            return view('ajustes.pay')->withErrors(['mensagem' => 'Assinatura não encontrada.']);
        }

        // Mapeia os tipos de assinatura
        $tipos = [
            0 => 'Free',
            1 => '1 mês',
            2 => '6 meses',
            3 => '1 ano'
        ];

        // Mapeia o status da assinatura
        $statusAssinatura = $assinatura->status == 1 ? 'Ativa' : 'Inativa';

        // Retorna os detalhes da assinatura para a view ajustes.pay
        return view('ajustes.pay', [
            'tipo' => $tipos[$assinatura->tipo],
            'status' => $statusAssinatura,
            'data_inicio' => $assinatura->data_inicio,
            'data_fim' => $assinatura->data_fim
        ]);
    }
}
