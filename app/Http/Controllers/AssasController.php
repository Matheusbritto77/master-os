<?php

namespace App\Http\Controllers;

use App\Models\Assinatura;
use App\Models\User;
use App\Models\Plano; // Adicionando o modelo Plano
use App\Services\AssasApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssasController extends Controller
{
    protected $assasApiService;

    public function __construct(AssasApiService $assasApiService)
    {
        $this->assasApiService = $assasApiService;
    }

    public function registrarClienteAssas(Request $request, $type, $type_payment)
    {
        // Obtém o ID do usuário logado
        $user_id = Auth::id();
        Log::info('User ID obtido: ' . $user_id);

        // Verifica se o usuário já possui uma assinatura
        $assinatura = Assinatura::firstOrNew(['user_id' => $user_id]);
        Log::info('Assinatura encontrada ou criada: ' . json_encode($assinatura));

        // Se já houver um `customer_id`, inicia o pagamento
        if ($assinatura->exists && $assinatura->customer_id) {
            Log::info('Cliente já registrado no Assas. customer_id: ' . $assinatura->customer_id);
            return $this->realizarPagamento($assinatura->customer_id, $type, $type_payment); // Passando o tipo de pagamento
        }

        // Se o cliente não existe, cria o cliente no Assas
        $user = User::find($user_id);
        if (!$user) {
            Log::error('Usuário não encontrado com o ID: ' . $user_id);
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        // Prepara os dados para enviar à API
        $dadosCliente = [
            'name' => $user->name,
            'cpfCnpj' => $user->cpf_cnpj,
            'email' => $user->email,
            'phone' => $user->phone ?? null,
            'mobilePhone' => $user->mobile_phone ?? null,
            'address' => $user->address ?? null,
            'addressNumber' => $user->address_number ?? null,
            'postalCode' => $user->postal_code ?? null,
            'externalReference' => $user->external_reference ?? null
        ];
        Log::info('Dados do cliente enviados para a API: ' . json_encode($dadosCliente));

        // Chama a API Assas para criar o cliente
        $response = $this->assasApiService->criarCliente(...array_values($dadosCliente));
        Log::info('Resposta da API Assas: ' . json_encode($response));

        if ($response['success']) {
            // Atualiza o `customer_id` na tabela de assinaturas
            $assinatura->customer_id = $response['customer_id'];
            $assinatura->save();

            Log::info('Customer ID salvo com sucesso: ' . $assinatura->customer_id);

            // Realiza o pagamento após o cliente ser criado
            return $this->realizarPagamento($assinatura->customer_id, $type, $type_payment); // Passando o tipo de pagamento
        }

        // Caso a criação do cliente falhe, loga o erro
        Log::error('Erro ao registrar cliente no Assas: ' . json_encode($response));
        return response()->json([
            'error' => 'Erro ao registrar cliente no Assas',
            'details' => $response
        ], 500);
    }

    /**
     * Função para realizar o pagamento de assinatura
     *
     * @param string $customerId
     * @param int $type
     * @param string $type_payment
     * @return \Illuminate\Http\JsonResponse
     */
    protected function realizarPagamento(string $customerId, int $type, string $type_payment)
    {
        // Recupera o valor do plano baseado no tipo
        $plano = Plano::where('tipo', $type)->first();

        if (!$plano) {
            Log::error("Plano não encontrado para o tipo: " . $type);
            return response()->json([
                'error' => 'Plano não encontrado para o tipo solicitado'
            ], 404);
        }

        // Verifica se o valor do plano é válido
        $valorPlano = $plano->valor;
        if (is_null($valorPlano) || !is_numeric($valorPlano)) {
            Log::error("Valor do plano inválido ou null para o tipo: " . $type);
            return response()->json([
                'error' => 'Valor do plano inválido'
            ], 500);
        }

        // Verifica se o `customerId` foi recebido corretamente
        Log::info("Iniciando pagamento para o customer_id: " . $customerId);

        // Define os detalhes do pagamento
        $dadosPagamento = [
            'customer' => $customerId,
            'billingType' => $type_payment, // Agora utilizando $type_payment para billingType
            'value' => $valorPlano, // Utiliza o valor do plano recuperado
            'dueDate' => now()->addDays(3)->format('Y-m-d'), // Data de vencimento para 3 dias após hoje
            'description' => 'Assinatura mensal'
        ];
        Log::info('Dados do pagamento enviados para a API: ' . json_encode($dadosPagamento));

        // Chama a API para criar a cobrança
        $response = $this->assasApiService->criarCobranca($dadosPagamento);
        Log::info('Resposta da API Assas para pagamento: ' . json_encode($response));

        if ($response['success']) {
            // Redireciona diretamente para o link de pagamento
            return redirect()->away($response['payment_link']);
        }

        // Caso falhe, loga o erro e retorna o problema
        Log::error('Erro ao criar cobrança no Assas: ' . json_encode($response));
        return response()->json([
            'error' => 'Erro ao criar cobrança',
            'details' => $response
        ], 500);
    }
}
