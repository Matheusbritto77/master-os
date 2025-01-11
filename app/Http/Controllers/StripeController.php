<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Pagamento;
use App\Models\Plano;
use App\Models\Assinatura;
use Carbon\Carbon;


class StripeController extends Controller
{
    public function createCheckoutSession(Request $request)
{
    // Obtém o modo de operação do Stripe do arquivo de configuração
    $mode = config('stripe.mode');
    
    // Define a chave secreta do Stripe com base no modo
    $secretKey = $mode === 'production'
        ? config('stripe.keys.secret')
        : config('stripe.test.secret_key');

    // Configura a chave secreta do Stripe
    Stripe::setApiKey($secretKey);

    $YOUR_DOMAIN = env('APP_URL');

    // Valida os dados recebidos do frontend
    $validatedData = $request->validate([
        'tipo' => 'required|integer|in:1,2,3', // 1 para 1 mês, 2 para 6 meses, 3 para 1 ano
    ]);

    try {
        // Obtém o plano baseado no tipo
        $plano = Plano::where('tipo', $validatedData['tipo'])->firstOrFail();

        // O valor deve ser em centavos, então multiplica por 100
        $unitAmount = $plano->valor * 100; // Ajuste o valor se necessário

        // Cria uma nova sessão de Checkout
        $checkout_session = StripeSession::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [
                        'name' => 'Plano ' . $validatedData['tipo'], // Nome do produto
                    ],
                    'unit_amount' => $unitAmount, // Valor em centavos
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . 'public/plano-contratado',
            'cancel_url' => $YOUR_DOMAIN . 'public/home',
        ]);

        // Registra o pagamento na tabela 'pagamentos'
        $payment = new Pagamento();
        $payment->user_id = Auth::id(); // Obtém o ID do usuário logado
        
        $payment->status = 0; // Status inicial do pagamento (0 por padrão)
        $payment->tipo = $validatedData['tipo']; // Tipo do plano (1, 2 ou 3)
        $payment->valor = $unitAmount; // Valor em centavos
        $payment->session_id = $checkout_session->id; // ID da sessão do Stripe
        $payment->save();

        // Redireciona o usuário para a URL do Checkout
        return response()->json(['url' => $checkout_session->url]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}






    public function updateSubscriptions()
    {
        // Configura a chave secreta do Stripe
        $secretKey = config('stripe.mode') === 'production'
            ? config('stripe.keys.secret')
            : config('stripe.test.secret_key');
        Stripe::setApiKey($secretKey);

        // Define o intervalo de tempo (últimos 30 minutos)
        $thirtyMinutesAgo = now()->subMinutes(30);

        // Obtém os pagamentos com status 0 e session_id não nulo nos últimos 30 minutos
        $pagamentos = Pagamento::where('status', 0)
            ->whereNotNull('session_id')
            ->where('updated_at', '>=', $thirtyMinutesAgo)
            ->get();

        foreach ($pagamentos as $pagamento) {
            try {
                // Obtém os detalhes da sessão de checkout do Stripe
                $checkoutSession = StripeSession::retrieve($pagamento->session_id);

                // Verifica se o pagamento foi concluído
                if ($checkoutSession->payment_status === 'paid') {
                    // Atualiza o status do pagamento para 1 (pago)
                    $pagamento->status = 1;
                    $pagamento->save();

                    // Verifica se já existe uma assinatura ativa para o usuário
                    $assinatura = Assinatura::where('user_id', $pagamento->user_id)
                        ->first();

                    if ($assinatura) {
                        // Se já existe uma assinatura, atualiza os dados
                        $assinatura->tipo = $pagamento->tipo;
                        $assinatura->status = 1;
                        $assinatura->data_inicio = now();
                        $assinatura->data_fim = $this->calculateEndDate($pagamento->tipo);
                        $assinatura->save();
                    } else {
                        // Se não existe uma assinatura, cria uma nova
                        Assinatura::create([
                            'user_id' => $pagamento->user_id,
                            'tipo' => $pagamento->tipo,
                            'status' => 1,
                            'data_inicio' => now(),
                            'data_fim' => $this->calculateEndDate($pagamento->tipo),
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Lida com erros na recuperação da sessão de checkout
                // Isso pode incluir o log do erro ou notificação de falha
                \Log::error("Erro ao processar pagamento: {$e->getMessage()}");
            }
        }
    }

    private function calculateEndDate($tipo)
    {
        // Calcula a data de término com base no tipo de assinatura
        $now = now();
        switch ($tipo) {
            case 1: // 1 mês
                return $now->addMonth();
            case 2: // 6 meses
                return $now->addMonths(6);
            case 3: // 1 ano
                return $now->addYear();
            default:
                return $now;
        }
    }
}


