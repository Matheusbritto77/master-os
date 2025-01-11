<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacebookPixelController extends Controller
{
    public function sendViewContentEvent(Request $request)
    {
        // Defina seu token de acesso e ID do pixel
        $accessToken = 'EAASu3KcMZAg0BOy64G2eAaLP9mftO6E04wKOZCYRMEC4SCa8EPdoLcSPXNZBUUt8qTRwr0reqU57ZBWD7ESATmbWr6d6PmokC7B3T4h5q9l64p8TL7TkisspAGzxTLAreo80Al9cukl0sbEHzBFshfg5g4lv1MPgew6XXDNZA9NAKXgQdwPgwKMZCWZCFHksz7AzgZDZD';
        $pixelId = '1078228973780980';

        // Pegue as informações do cliente e da página
        $userAgent = $request->header('User-Agent');  // Agente de usuário do cliente
        $eventUrl = $request->fullUrl();  // URL de origem do evento
        $eventTime = time();  // Hora do evento (em timestamp Unix)

        // Prepare os dados do evento "ViewContent"
        $data = [
            'data' => [
                [
                    'event_name' => 'ViewContent',  // Nome do evento alterado para "ViewContent"
                    'event_time' => $eventTime,  // Hora do evento
                    'action_source' => 'website',  // Fonte da ação
                    'event_source_url' => $eventUrl,  // URL de origem do evento
                    'user_data' => [
                        'client_user_agent' => $userAgent  // Agente de usuário do cliente
                    ],
                    'custom_data' => [
                        'currency' => 'USD',  // Adicionando dados personalizados
                        'value' => 100.00,
                    ]
                ]
            ]
        ];

        // URL da API de Conversões do Facebook
        $url = "https://graph.facebook.com/v11.0/$pixelId/events?access_token=$accessToken";

        // Enviar requisição usando HTTP Client
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $data);

        // Verificar se a requisição foi bem-sucedida
        if ($response->successful()) {
            return response()->json(['message' => 'Evento "ViewContent" enviado com sucesso', 'response' => $response->json()]);
        } else {
            return response()->json(['error' => 'Erro ao enviar evento', 'response' => $response->json()], 500);
        }
    }
}
