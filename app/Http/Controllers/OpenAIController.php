<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\JsonResponse;

class OpenAIController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    /**
     * Testa a conexão com a API da OpenAI.
     *
     * @return JsonResponse
     */
    public function testConnection(): JsonResponse
    {
        $message = "Hello world britto"; // Mensagem de teste
        $response = $this->openAIService->sendMessage($message);

        return response()->json([
            'status' => 'success',
            'response' => $response,
        ]);
    }
}
