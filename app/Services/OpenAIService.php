<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected $client;
    protected $apiKey;
    protected $orgId;
    protected $apiUrl;
    protected $projectId;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.openai.api_key');
        $this->orgId = config('services.openai.organization_id');
        $this->apiUrl = config('services.openai.api_url');
        $this->projectId = config('services.openai.project_id');
    }

    /**
     * Envia uma mensagem para a API da OpenAI e retorna a resposta.
     *
     * @param string $message
     * @return array
     */
    public function sendMessage(string $message): array
    {
        try {
            $response = $this->client->post($this->apiUrl . '/chat/completions', [
                'headers' => [
                    'Authorization' => "Bearer {$this->apiKey}",
                    'OpenAI-Organization' => $this->orgId,
                    'OpenAI-Project' => $this->projectId,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $message]
                    ],
                    'temperature' => 0.7,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar mensagem para OpenAI: ' . $e->getMessage());
            return [
                'status' => 'error',
                'response' => 'Ocorreu um erro ao enviar a mensagem.',
            ];
        }
    }

    // ... (mantenha o método fetchModels se necessário)
}
