<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Instancia;
use Illuminate\Support\Str;

class InstanciaController extends Controller
{
    // URL e token da API do WhatsApp
    private $whatsappApiUrl = "https://dapleserver.com";
    private $whatsappApiToken = "asdfghjksjhggbnmfdfghjhgf";

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('verifica.assinatura');
    }

    /**
     * Exibe a lista de instâncias do usuário.
     */
    public function index(Request $request)
    {
        try {
            $instancias = Instancia::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

            // Se a requisição for AJAX, retorna JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'instancias' => $instancias
                ]);
            }

            // Se não for AJAX, retorna a view
            return view('instancias.index', compact('instancias'));
        } catch (\Exception $e) {
            Log::error('Erro ao listar instâncias: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao carregar instâncias.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erro ao carregar instâncias.');
        }
    }

    /**
     * Exibe o formulário para criar uma nova instância.
     */
    public function create()
    {
        return view('instancias.create');
    }

    /**
     * Armazena uma nova instância criada pelo formulário.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:instancias,name',
            'api_endpoint' => 'required|url',
            'description' => 'nullable|string',
        ]);

        try {
            // Verifica se o usuário já tem uma instância
            $existingInstance = Instancia::where('user_id', Auth::id())->first();
            
            if ($existingInstance) {
                return redirect()->back()->with('error', 'Você já possui uma instância. Cada usuário pode ter apenas uma instância.');
            }

            $instancia = Instancia::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'status' => 'inactive',
                'session_id' => $request->name,
                'api_endpoint' => $request->api_endpoint,
                'description' => $request->description,
            ]);

            Log::info('Instância criada via formulário: ' . $instancia->id);
            return redirect()->route('instancias.index')->with('success', 'Instância criada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar instância via formulário: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao criar instância. Tente novamente.');
        }
    }

    /**
     * Cria uma nova instância automaticamente com nome aleatório.
     * Limita a 1 instância por usuário.
     */
    public function createInstance()
    {
        try {
            // Verifica se o usuário já tem uma instância
            $existingInstance = Instancia::where('user_id', Auth::id())->first();
            
            if ($existingInstance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você já possui uma instância. Cada usuário pode ter apenas uma instância.'
                ], 400);
            }

            // Gera um nome único para a instância
            $instanceName = $this->generateUniqueInstanceName();
            
            // Chama a API do WhatsApp para iniciar a sessão
            $response = Http::withHeaders([
                'x-api-key' => $this->whatsappApiToken,
            ])->get("{$this->whatsappApiUrl}/session/start/{$instanceName}");

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Verifica se a sessão foi iniciada com sucesso
                if (isset($responseData['success']) && $responseData['success'] === true) {
                    // Cria a instância no banco de dados
                    $instancia = Instancia::create([
                        'user_id' => Auth::id(),
                        'name' => $instanceName,
                        'status' => 'active',
                        'session_id' => $instanceName,
                        'api_endpoint' => $this->whatsappApiUrl,
                        'description' => 'Instância criada automaticamente'
                    ]);

                    Log::info('Instância criada com sucesso: ' . $instancia->id);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Instância criada com sucesso!',
                        'data' => $instancia
                    ]);
                } else {
                    Log::error('Erro na resposta da API WhatsApp: ' . json_encode($responseData));
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao criar sessão no WhatsApp. Tente novamente.'
                    ], 400);
                }
            } else {
                Log::error('Erro ao chamar API WhatsApp. Status: ' . $response->status());
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao comunicar com o servidor do WhatsApp. Tente novamente.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar instância: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Gera um nome único para a instância.
     */
    private function generateUniqueInstanceName()
    {
        $prefix = 'instance_';
        $attempt = 0;
        
        do {
            $name = $prefix . time() . ($attempt > 0 ? "_$attempt" : '');
            $attempt++;
        } while (Instancia::where('name', $name)->exists());

        return $name;
    }

    /**
     * Exibe uma instância específica.
     */
    public function show(Instancia $instancia)
    {
        if ($instancia->user_id !== Auth::id()) {
            return redirect()->route('instancias.index')->with('error', 'Acesso negado.');
        }

        return view('instancias.show', compact('instancia'));
    }

    /**
     * Exibe o formulário para editar uma instância.
     */
    public function edit(Instancia $instancia)
    {
        if ($instancia->user_id !== Auth::id()) {
            return redirect()->route('instancias.index')->with('error', 'Acesso negado.');
        }

        return view('instancias.edit', compact('instancia'));
    }

    /**
     * Atualiza uma instância.
     */
    public function update(Request $request, Instancia $instancia)
    {
        if ($instancia->user_id !== Auth::id()) {
            return redirect()->route('instancias.index')->with('error', 'Acesso negado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'api_endpoint' => 'required|url',
            'description' => 'nullable|string',
        ]);

        try {
            $instancia->update([
                'name' => $request->name,
                'api_endpoint' => $request->api_endpoint,
                'description' => $request->description,
            ]);

            Log::info('Instância atualizada com sucesso: ' . $instancia->id);
            return redirect()->route('instancias.index')->with('success', 'Instância atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar instância: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao atualizar instância.');
        }
    }

    /**
     * Remove uma instância e encerra a sessão no WhatsApp.
     */
    public function destroy($id)
    {
        try {
            $instancia = Instancia::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Encerra a sessão no WhatsApp usando o endpoint correto
            $response = Http::withHeaders([
                'x-api-key' => $this->whatsappApiToken,
            ])->get("{$this->whatsappApiUrl}/session/terminate/{$instancia->name}");

            // Remove a instância do banco de dados
            $instancia->delete();
            
            Log::info('Instância removida com sucesso: ' . $id);
            
            return response()->json([
                'success' => true,
                'message' => 'Instância removida com sucesso!'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao remover instância: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover instância. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Gera o QR Code para conectar o WhatsApp.
     * Pode demorar até 30 segundos para retornar o base64.
     */
    public function generateQRCode($id)
    {
        try {
            $instancia = Instancia::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Chama a API para gerar o QR Code usando o endpoint correto
            $response = Http::withHeaders([
                'x-api-key' => $this->whatsappApiToken,
            ])->get("{$this->whatsappApiUrl}/session/qr/{$instancia->name}");

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Verifica se o QR Code foi gerado com sucesso
                if (isset($responseData['success']) && $responseData['success'] === true) {
                    // Retorna o QR code no formato correto da API
                    return response()->json([
                        'success' => true,
                        'qr_code' => $responseData['qr'] ?? null,
                        'message' => 'QR Code gerado com sucesso!'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao gerar QR Code. Tente novamente.'
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao comunicar com o servidor do WhatsApp.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao gerar QR Code: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Verifica o status da instância.
     */
    public function checkStatus($id)
    {
        try {
            $instancia = Instancia::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Chama a API para verificar o status usando o endpoint correto
            $response = Http::withHeaders([
                'x-api-key' => $this->whatsappApiToken,
            ])->get("{$this->whatsappApiUrl}/session/status/{$instancia->name}");

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Verifica se o status foi obtido com sucesso
                if (isset($responseData['success']) && $responseData['success'] === true) {
                    $status = $responseData['status'] ?? $responseData['state'] ?? 'unknown';
                    
                    // Mapeia os status da API para os status do nosso sistema
                    $mappedStatus = $this->mapApiStatusToLocalStatus($status);
                    
                    // Atualiza o status no banco de dados
                    $instancia->update(['status' => $mappedStatus]);
                    
                    return response()->json([
                        'success' => true,
                        'status' => $mappedStatus,
                        'api_status' => $status,
                        'message' => 'Status verificado com sucesso!'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao verificar status. Tente novamente.'
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao comunicar com o servidor do WhatsApp.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao verificar status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Mapeia os status da API para os status locais.
     */
    private function mapApiStatusToLocalStatus($apiStatus)
    {
        $statusMap = [
            'CONNECTED' => 'active',
            'CONNECTING' => 'active',
            'AUTHENTICATED' => 'active',
            'READY' => 'active',
            'DISCONNECTED' => 'inactive',
            'LOGGED_OUT' => 'inactive',
            'UNLAUNCHED' => 'inactive',
            'OPENING' => 'active',
            'CLOSING' => 'inactive',
            'QR_READY' => 'active',
            'QR_READY_RETRY' => 'active',
            'PAIRING' => 'active',
            'TIMEOUT' => 'inactive',
            'INJECTED' => 'active',
            'BUSY' => 'active',
            'UNPAIRED' => 'inactive',
            'UNPAIRED_IDLE' => 'inactive'
        ];

        return $statusMap[$apiStatus] ?? 'unknown';
    }

    /**
     * Encerra a sessão da instância.
     */
    public function stopSession($id)
    {
        try {
            $instancia = Instancia::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Chama a API para encerrar a sessão usando o endpoint correto
            $response = Http::withHeaders([
                'x-api-key' => $this->whatsappApiToken,
            ])->get("{$this->whatsappApiUrl}/session/terminate/{$instancia->name}");

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Verifica se a sessão foi encerrada com sucesso
                if (isset($responseData['success']) && $responseData['success'] === true) {
                    // Atualiza o status para inativo
                    $instancia->update(['status' => 'inactive']);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Sessão encerrada com sucesso!'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao encerrar sessão. Tente novamente.'
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao comunicar com o servidor do WhatsApp.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao encerrar sessão: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Inicia a sessão da instância.
     */
    public function startSession($id)
    {
        try {
            $instancia = Instancia::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Chama a API para iniciar a sessão usando o endpoint correto
            $response = Http::withHeaders([
                'x-api-key' => $this->whatsappApiToken,
            ])->get("{$this->whatsappApiUrl}/session/start/{$instancia->name}");

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Verifica se a sessão foi iniciada com sucesso
                if (isset($responseData['success']) && $responseData['success'] === true) {
                    // Atualiza o status para ativo
                    $instancia->update(['status' => 'active']);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Sessão iniciada com sucesso!'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao iniciar sessão. Tente novamente.'
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao comunicar com o servidor do WhatsApp.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao iniciar sessão: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Reinicia a sessão da instância.
     */
    public function restartSession($id)
    {
        try {
            $instancia = Instancia::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Chama a API para reiniciar a sessão usando o endpoint correto
            $response = Http::withHeaders([
                'x-api-key' => $this->whatsappApiToken,
            ])->get("{$this->whatsappApiUrl}/session/restart/{$instancia->name}");

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Verifica se a sessão foi reiniciada com sucesso
                if (isset($responseData['success']) && $responseData['success'] === true) {
                    // Atualiza o status para ativo
                    $instancia->update(['status' => 'active']);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Sessão reiniciada com sucesso!'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao reiniciar sessão. Tente novamente.'
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao comunicar com o servidor do WhatsApp.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao reiniciar sessão: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Lista todas as sessões ativas no servidor.
     */
    public function listSessions()
    {
        try {
            // Chama a API para listar todas as sessões
            $response = Http::withHeaders([
                'x-api-key' => $this->whatsappApiToken,
            ])->get("{$this->whatsappApiUrl}/session/list");

            if ($response->successful()) {
                $responseData = $response->json();
                
                return response()->json([
                    'success' => true,
                    'sessions' => $responseData['sessions'] ?? [],
                    'message' => 'Sessões listadas com sucesso!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao listar sessões.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao listar sessões: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Encerra todas as sessões inativas.
     */
    public function terminateInactiveSessions()
    {
        try {
            // Chama a API para encerrar sessões inativas
            $response = Http::withHeaders([
                'x-api-key' => $this->whatsappApiToken,
            ])->get("{$this->whatsappApiUrl}/session/terminateInactive");

            if ($response->successful()) {
                $responseData = $response->json();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sessões inativas encerradas com sucesso!',
                    'data' => $responseData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao encerrar sessões inativas.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao encerrar sessões inativas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }
} 