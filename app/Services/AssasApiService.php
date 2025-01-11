<?php

namespace App\Services;

use GuzzleHttp\Client;

class AssasApiService
{
    protected $client;
    protected $apiUrl = 'https://sandbox.asaas.com/api/v3'; // Base URL da API Assas
    protected $apiKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = env('ASAAS_API_KEY'); // Chave da API do Assas armazenada no .env

        // Verifica se a chave foi carregada corretamente
        if (!$this->apiKey) {
            throw new \Exception('API Key não encontrada. Verifique o arquivo .env e a configuração "ASAAS_API_KEY".');
        }
    }

    /**
     * Função para registrar um cliente no Assas
     *
     * @param string $nome Nome do cliente
     * @param string $cpfCnpj CPF ou CNPJ do cliente
     * @param string $email E-mail do cliente
     * @param string|null $phone Telefone fixo (opcional)
     * @param string|null $mobilePhone Telefone celular (opcional)
     * @param string|null $address Endereço (opcional)
     * @param string|null $addressNumber Número do endereço (opcional)
     * @param string|null $postalCode CEP do endereço (opcional)
     * @param string|null $externalReference Referência externa (opcional)
     * @return array Resultado da operação
     */
    public function criarCliente(
        string $nome,
        string $cpfCnpj,
        string $email,
        ?string $phone = null,
        ?string $mobilePhone = null,
        ?string $address = null,
        ?string $addressNumber = null,
        ?string $postalCode = null,
        ?string $externalReference = null
    ): array {
        try {
            // Monta os dados para a requisição
            $requestData = [
                'name' => $nome,
                'cpfCnpj' => $cpfCnpj,
                'email' => $email,
                'phone' => $phone,
                'mobilePhone' => $mobilePhone,
                'address' => $address,
                'addressNumber' => $addressNumber,
                'postalCode' => $postalCode,
                'externalReference' => $externalReference,
            ];

            // Faz a requisição para a API Assas
            $response = $this->client->post($this->apiUrl . '/customers', [
                'headers' => [
                    'accept' => 'application/json',
                    'access_token' => $this->apiKey,
                    'content-type' => 'application/json',
                ],
                'json' => $requestData,
            ]);

            // Decodifica a resposta da API
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Verifica se houve sucesso
            if (isset($responseData['id'])) {
                return ['success' => true, 'customer_id' => $responseData['id']];
            }

            return ['success' => false, 'error' => $responseData['errors'] ?? 'Erro desconhecido'];
        } catch (\Exception $e) {
            // Retorna o erro caso ocorra uma exceção
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Função para criar uma cobrança no Assas
     *
     * @param array $dadosPagamento
     * @return array Resultado da operação
     */
    public function criarCobranca(array $dadosPagamento): array
    {
        try {
            // Faz a requisição para a API Assas
            $response = $this->client->post($this->apiUrl . '/payments', [
                'headers' => [
                    'accept' => 'application/json',
                    'access_token' => $this->apiKey,
                    'content-type' => 'application/json',
                ],
                'json' => $dadosPagamento,
            ]);

            // Decodifica a resposta da API
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Verifica se houve sucesso
            if (isset($responseData['id'])) {
                return [
                    'success' => true,
                    'payment_link' => $responseData['invoiceUrl'],
                    'payment_id' => $responseData['id'],
                ];
            }

            return ['success' => false, 'error' => $responseData['errors'] ?? 'Erro desconhecido'];
        } catch (\Exception $e) {
            // Retorna o erro caso ocorra uma exceção
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
