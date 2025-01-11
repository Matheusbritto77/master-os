<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gerencianet\Gerencianet;
use Gerencianet\Exception\GerencianetException;
use Exception;
use App\Models\Pagamento;
use App\Models\Assinatura;
use App\Models\User;
use App\Models\Plano; // Importando o modelo Plano
use Illuminate\Support\Facades\Auth;


class GerencianetPixController extends Controller
{
    public function generateQRCode($amount, $tipo)
{
    
    // Verifica se o plano existe
    $plano = Plano::where('tipo', $tipo)->first();

    if (!$plano) {
        return response()->json(['error' => 'Plano não encontrado'], 404);
    }
    
    // Configurações e criação do pagamento permanecem inalteradas
    $mode = config('gerencianet.mode');
    $certificate = config("gerencianet.{$mode}.certificate_name");

    $options = [
        'client_id' => config("gerencianet.{$mode}.client_id"),
        'client_secret' => config("gerencianet.{$mode}.client_secret"),
        'certificate' => base_path("certs/{$certificate}"),
        'sandbox' => $mode === 'sandbox',
        'debug' => config('gerencianet.debug'),
        'timeout' => 30,
    ];

    $body = [
        'calendario' => [
            'expiracao' => 3600,
        ],
        'valor' => [
            'original' =>  $amount = $plano->valor,
        ],
        'chave' => config('gerencianet.default_key_pix'),
        'solicitacaoPagador' => 'Pagamento Plataforma Master Os',
        'infoAdicionais' => [
            [
                'nome' => 'Observacoes',
                'valor' => 'Compra direta sem cupom de desconto',
            ],
        ],
    ];

    try {
        $api = Gerencianet::getInstance($options);
        $pix = $api->pixCreateImmediateCharge([], $body);

        if (!isset($pix['txid'])) {
            throw new Exception('Erro ao realizar pagamento, tente novamente');
        }

        $pagamento = new Pagamento();
        $pagamento->txid = $pix['txid'];
        $pagamento->valor = $amount;
        $pagamento->user_id = auth()->user()->id;
        $pagamento->status = 0;
        $pagamento->tipo = $tipo; // Adicione o tipo aqui
        $pagamento->save();

        $params = [
            'id' => $pix['loc']['id'],
        ];

        $qrcode = $api->pixGenerateQRCode($params);

        $url = $qrcode['linkVisualizacao'] ?? null;

        if ($url) {
            return redirect()->away($url);
        } else {
            return view('qrcode', ['qrcode' => $qrcode['imagemQrcode']]);
        }

    } catch (GerencianetException $gerencianetException) {
        return response()->json([
            'error' => $gerencianetException->getMessage()
        ], 500);
    } catch (Exception $exception) {
        return response()->json([
            'error' => $exception->getMessage()
        ], 500);
    }
}


public function consultarPixRecebidosUltimos30Minutos()
{
    try {
        $mode = config('gerencianet.mode');
        $certificate = config("gerencianet.{$mode}.certificate_name");

        $options = [
            'client_id' => config("gerencianet.{$mode}.client_id"),
            'client_secret' => config("gerencianet.{$mode}.client_secret"),
            'certificate' => base_path("certs/{$certificate}"),
            'sandbox' => $mode === 'sandbox',
            'debug' => config('gerencianet.debug'),
            'timeout' => 30,
        ];

        $inicio = now()->subMinutes(30)->toIso8601ZuluString();
        $fim = now()->toIso8601ZuluString();

        $params = [
            'inicio' => $inicio,
            'fim' => $fim,
        ];

        $api = Gerencianet::getInstance($options);

        $pixRecebidos = $api->pixReceivedList($params);

        if (isset($pixRecebidos['pix'])) {
            foreach ($pixRecebidos['pix'] as $pix) {
                // Procurar o pagamento com o txid recebido
                $pagamento = Pagamento::where('txid', $pix['txid'])->where('status', 0)->first();

                if ($pagamento) {
                    // Atualiza o status do pagamento para 1 (pago)
                    $pagamento->status = 1;
                    $pagamento->save();

                    // Atualiza a tabela 'assinaturas' se o pagamento foi confirmado
                    $assinatura = \App\Models\Assinatura::where('user_id', $pagamento->user_id)->first();
                    if ($assinatura) {
                        $data_inicio = now();

                        // Atualiza o tipo de assinatura baseado no tipo do pagamento
                        switch ($pagamento->tipo) {
                            case 1: // 1 mês
                                $data_fim = $data_inicio->copy()->addDays(30);
                                break;
                            case 2: // 6 meses
                                $data_fim = $data_inicio->copy()->addDays(180);
                                break;
                            case 3: // 1 ano
                                $data_fim = $data_inicio->copy()->addYear();
                                break;
                            default:
                                $data_fim = $data_inicio;
                                break;
                        }

                        // Atualiza os dados na tabela 'assinaturas'
                        $assinatura->tipo = $pagamento->tipo; // Atualiza o tipo na assinatura
                        $assinatura->status = 1;
                        $assinatura->data_inicio = $data_inicio;
                        $assinatura->data_fim = $data_fim;
                        $assinatura->save();
                        
                        return view('pixel.pixel');
                    
                    }
                }
            }
        }

    } catch (GerencianetException $gerencianetException) {
        return response()->json([
            'error' => $gerencianetException->getMessage()
        ], 500);
    } catch (Exception $exception) {
        return response()->json([
            'error' => $exception->getMessage()
        ], 500);
    }
}







    public function checkPaymentStatus()
    {
        $txid = '9c29f942f01244f182746722f9d5e59a'; // Substitua pelo txid desejado

        try {
            // Configurações da API Gerencianet
            $mode = config('gerencianet.mode');
            $certificate = config("gerencianet.{$mode}.certificate_name");

            $options = [
                'client_id' => config("gerencianet.{$mode}.client_id"),
                'client_secret' => config("gerencianet.{$mode}.client_secret"),
                'certificate' => base_path("certs/{$certificate}"),
                'sandbox' => $mode === 'sandbox',
                'debug' => config('gerencianet.debug'),
                'timeout' => 30,
            ];

            // Instancia a API Gerencianet
            $api = Gerencianet::getInstance($options);

            // Parâmetros para consultar o status do PIX
            $params = [
                'txid' => $txid,
            ];

            // Consulta o status do PIX
            $pixInfo = $api->pixDetail($params);

            return response()->json([
                'pixInfo' => $pixInfo,
            ]);

        } catch (GerencianetException $gerencianetException) {
            return response()->json([
                'error' => $gerencianetException->getMessage()
            ], 500);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 500);
        }
    }






    public function processarPagamentoCartao($amount, $tipo, $dadosCartao)
{
    // Configurações de credenciais da Gerencianet para modo de produção ou sandbox
    $mode = config('gerencianet.mode');
    $certificate = config("gerencianet.{$mode}.certificate_name");

    $options = [
        'client_id' => config("gerencianet.{$mode}.client_id"),
        'client_secret' => config("gerencianet.{$mode}.client_secret"),
        'certificate' => base_path("certs/{$certificate}"),
        'sandbox' => $mode === 'sandbox',
        'debug' => config('gerencianet.debug'),
        'timeout' => 30,
    ];

    // Dados do pagamento com cartão de crédito
    $body = [
        'payment' => [
            'credit_card' => [
                'installments' => 1, // Número de parcelas (aqui está como pagamento à vista)
                'payment_token' => $dadosCartao['token'], // Token gerado para o cartão de crédito
                'billing_address' => [
                    'street' => $dadosCartao['endereco'],
                    'number' => $dadosCartao['numero'],
                    'neighborhood' => $dadosCartao['bairro'],
                    'zipcode' => $dadosCartao['cep'],
                    'city' => $dadosCartao['cidade'],
                    'state' => $dadosCartao['estado'],
                ],
                'customer' => [
                    'name' => $dadosCartao['nome'],
                    'cpf' => $dadosCartao['cpf'],
                    'phone_number' => $dadosCartao['telefone'],
                ],
            ],
        ],
        'items' => [
            [
                'name' => 'Assinatura Plataforma XPTO',
                'amount' => 1, // Quantidade de itens
                'value' => intval($amount * 100), // Valor total em centavos
            ],
        ],
    ];

    try {
        $api = Gerencianet::getInstance($options);
        $paymentResponse = $api->oneStep([], $body);

        if (!isset($paymentResponse['data']['charge_id'])) {
            throw new Exception('Erro ao processar pagamento com cartão, tente novamente');
        }

        // Registrar pagamento no banco de dados
        $pagamento = new Pagamento();
        $pagamento->charge_id = $paymentResponse['data']['charge_id']; // ID da cobrança retornado pela API
        $pagamento->valor = $amount;
        $pagamento->user_id = auth()->user()->id;
        $pagamento->status = 1; // Pagamento aprovado
        $pagamento->tipo = $tipo; // Tipo do plano
        $pagamento->save();

        return redirect()->route('pagamento.sucesso');

    } catch (GerencianetException $gerencianetException) {
        return response()->json([
            'error' => $gerencianetException->getMessage(),
        ], 500);
    } catch (Exception $exception) {
        return response()->json([
            'error' => $exception->getMessage(),
        ], 500);
    }
}

}

