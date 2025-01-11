<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Assinatura;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AssinaturaController extends Controller
{
    public function verificarAssinatura(User $user)
    {
        // Define o fuso horário UTC ou ajusta conforme necessário
        $hoje = Carbon::now('UTC');
        $dataCriacao = Carbon::parse($user->created_at)->timezone('UTC');

        // Garante que a diferença de dias seja sempre positiva
        $diferencaDias = abs($hoje->diffInDays($dataCriacao));

        Log::info('Verificando assinatura para o usuário: ' . $user->id);
        Log::info('Data de Criação: ' . $dataCriacao->toDateString());
        Log::info('Data de Hoje: ' . $hoje->toDateString());
        Log::info('Diferença em dias: ' . $diferencaDias);

        // Verifica se o usuário tem mais de 7 dias de criação
        if ($diferencaDias > 7) {
            Log::info('Usuário com mais de 7 dias de criação: ' . $user->id);

            // Busca a assinatura existente para o usuário
            $assinatura = Assinatura::where('user_id', $user->id)->first();

            if (!$assinatura) {
                Log::info('Criando nova assinatura para o usuário: ' . $user->id);
                // Cria uma nova assinatura com status 0 (não paga) e tipo 0 (Free)
                try {
                    Assinatura::create([
                        'user_id' => $user->id,
                        'tipo' => 0, // Tipo Free
                        'status' => 0, // Não paga
                        // Campos 'data_inicio' e 'data_fim' são opcionais e podem ser NULL
                    ]);

                    Log::info('Assinatura criada com sucesso para o usuário: ' . $user->id);
                } catch (\Exception $e) {
                    Log::error('Erro ao salvar a assinatura: ' . $e->getMessage());
                }
            } else {
                Log::info('Assinatura já existente para o usuário: ' . $user->id);
            }
        } else {
            Log::info('Usuário com menos de 7 dias de criação: ' . $user->id);
        }
    }




public function verificarAssinaturasExpiradas()
{
    // Busca todas as assinaturas com status 1 (ativas)
    $assinaturas = Assinatura::where('status', 1)->get();

    foreach ($assinaturas as $assinatura) {
        // Compara a data_fim com a data atual
        if (Carbon::now()->gt(Carbon::parse($assinatura->data_fim))) {
            // Se a assinatura expirou, atualiza status e tipo para 0
            $assinatura->status = 0;
            $assinatura->tipo = 0;
            $assinatura->save();
        }
    }

    return response()->json([
        'message' => 'Verificação de assinaturas concluída.',
    ]);
}}
