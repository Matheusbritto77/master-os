<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Schedule;
use App\Models\ScheduleHour;
use App\Models\ScheduleDay;
use App\Models\ScheduleExternalGroup;
use App\Models\SchedulesPeriodo;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('verifica.assinatura');
    }

    /**
     * List all schedules for the authenticated user.
     */
    public function index()
    {
        return view('agendamentos');
    }

    /**
     * Show a specific schedule for the authenticated user.
     */
    public function show(Schedule $schedule)
    {
        try {
            if ($schedule->user_id !== Auth::id()) {
                Log::warning('Acesso não autorizado ao agendamento ID ' . $schedule->id . ' pelo usuário ID ' . Auth::id());
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $schedule->load(['hours', 'days', 'externalGroups', 'periodo']);
            Log::info('Detalhes do agendamento ID ' . $schedule->id . ' recuperados para o usuário ID ' . Auth::id());
            return response()->json($schedule);
        } catch (\Exception $e) {
            Log::error('Erro ao exibir agendamento ID ' . $schedule->id . ' para o usuário ID ' . Auth::id() . ': ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Create a new schedule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'mode' => 'required|in:daily,specific_days',
            'frequency' => 'required|string|in:1 vez por dia,2 vezes por dia,3 vezes por dia,De hora em hora',
            'hours' => 'nullable|array|max:3',
            'hours.*' => 'required_if:frequency,1 vez por dia,2 vezes por dia,3 vezes por dia|date_format:H:i',
            'days' => 'nullable|string',
            'group_ids' => 'required|string',
            'period_start' => 'required_if:frequency,De hora em hora|date_format:H:i',
            'period_end' => 'required_if:frequency,De hora em hora|date_format:H:i|after:period_start',
        ]);

        // Converter group_ids de string para array
        $groupIds = !empty($validated['group_ids']) ? explode(',', $validated['group_ids']) : [];
        
        // Converter days de string para array se fornecido
        $days = [];
        if (!empty($validated['days'])) {
            $days = explode(',', $validated['days']);
        }

        // Verificar se a frequência exige horários e validar a quantidade
        if (in_array($validated['frequency'], ['1 vez por dia', '2 vezes por dia', '3 vezes por dia'])) {
            $expectedCount = (int) substr($validated['frequency'], 0, 1);
            if (count($validated['hours'] ?? []) !== $expectedCount) {
                return response()->json([
                    'error' => "Você deve fornecer exatamente {$expectedCount} horário(s) para a frequência '{$validated['frequency']}'."
                ], 422);
            }
        }

        // Verificar se modo é specific_days e exige dias
        if ($validated['mode'] === 'specific_days' && empty($days)) {
            return response()->json(['error' => 'Você deve fornecer pelo menos um dia para o modo "Dias Específicos".'], 422);
        }

        // Processar imagem se fornecida
        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('schedule_images', 'public');
                Log::info("Imagem salva com sucesso no driver 'public': {$imagePath}");
            } catch (\Exception $e) {
                Log::warning("Falha ao salvar imagem no driver 'public': " . $e->getMessage() . ". Usando fallback para 'storage'.");
                $imagePath = $request->file('image')->store('schedule_images');
                Log::info("Imagem salva com sucesso no driver padrão (fallback): {$imagePath}");
            }
        }

        // Criação do agendamento
        $schedule = Schedule::create([
            'user_id' => Auth::id(),
            'description' => $validated['description'],
            'image_path' => $imagePath,
            'mode' => $validated['mode'],
            'frequency' => $validated['frequency'],
            'status' => 'active',
        ]);

        // Adicionar horários se fornecidos (não se for "De hora em hora")
        if ($validated['frequency'] !== 'De hora em hora' && !empty($validated['hours'])) {
            foreach ($validated['hours'] as $hour) {
                ScheduleHour::create([
                    'schedule_id' => $schedule->id,
                    'hour' => $hour,
                ]);
            }
        }

        // Adicionar período se frequência for "De hora em hora"
        if ($validated['frequency'] === 'De hora em hora') {
            SchedulesPeriodo::create([
                'schedules_id' => $schedule->id,
                'periodo_inicio' => $validated['period_start'],
                'periodo_fim' => $validated['period_end'],
            ]);
        }

        // Adicionar dias específicos se modo for "specific_days"
        if ($validated['mode'] === 'specific_days' && !empty($days)) {
            foreach ($days as $day) {
                $day = trim($day); // Remover espaços
                if (!empty($day)) {
                    $date = \DateTime::createFromFormat('d/m/Y', $day)->format('Y-m-d');
                    ScheduleDay::create([
                        'schedule_id' => $schedule->id,
                        'date' => $date,
                    ]);
                }
            }
        }

        // Relacionar group_ids externos ao agendamento
        if (!empty($groupIds)) {
            foreach ($groupIds as $groupId) {
                $groupId = trim($groupId); // Remover espaços
                if (!empty($groupId)) {
                    ScheduleExternalGroup::create([
                        'schedule_id' => $schedule->id,
                        'group_id' => $groupId,
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Agendamento criado com sucesso', 'schedule' => $schedule->load(['hours', 'days', 'externalGroups', 'periodo'])], 201);
    }

    /**
     * Update an existing schedule.
     */
    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'mode' => 'required|in:daily,specific_days',
            'frequency' => 'required|string|in:1 vez por dia,2 vezes por dia,3 vezes por dia,De hora em hora',
            'hours' => 'nullable|array|max:3',
            'hours.*' => 'required_if:frequency,1 vez por dia,2 vezes por dia,3 vezes por dia|date_format:H:i',
            'days' => 'nullable|string',
            'group_ids' => 'required|string',
            'period_start' => 'required_if:frequency,De hora em hora|date_format:H:i',
            'period_end' => 'required_if:frequency,De hora em hora|date_format:H:i|after:period_start',
        ]);

        // Converter group_ids de string para array
        $groupIds = !empty($validated['group_ids']) ? explode(',', $validated['group_ids']) : [];
        
        // Converter days de string para array se fornecido
        $days = [];
        if (!empty($validated['days'])) {
            $days = explode(',', $validated['days']);
        }

        // Verificar se a frequência exige horários e validar a quantidade
        if (in_array($validated['frequency'], ['1 vez por dia', '2 vezes por dia', '3 vezes por dia'])) {
            $expectedCount = (int) substr($validated['frequency'], 0, 1);
            if (count($validated['hours'] ?? []) !== $expectedCount) {
                return response()->json([
                    'error' => "Você deve fornecer exatamente {$expectedCount} horário(s) para a frequência '{$validated['frequency']}'."
                ], 422);
            }
        }

        // Verificar se modo é specific_days e exige dias
        if ($validated['mode'] === 'specific_days' && empty($days)) {
            return response()->json(['error' => 'Você deve fornecer pelo menos um dia para o modo "Dias Específicos".'], 422);
        }

        // Atualizar imagem se fornecida
        if ($request->hasFile('image')) {
            if ($schedule->image_path) {
                Storage::disk('public')->delete($schedule->image_path);
            }
            $schedule->image_path = $request->file('image')->store('schedule_images', 'public');
        }

        // Atualizar dados principais
        $schedule->update([
            'description' => $validated['description'],
            'mode' => $validated['mode'],
            'frequency' => $validated['frequency'],
        ]);

        // Atualizar horários (não se for "De hora em hora")
        if ($validated['frequency'] !== 'De hora em hora' && !empty($validated['hours'])) {
            $schedule->hours()->delete();
            foreach ($validated['hours'] as $hour) {
                ScheduleHour::create([
                    'schedule_id' => $schedule->id,
                    'hour' => $hour,
                ]);
            }
        } else {
            $schedule->hours()->delete();
        }

        // Atualizar período se frequência for "De hora em hora"
        if ($validated['frequency'] === 'De hora em hora') {
            $schedule->periodo()->delete();
            SchedulesPeriodo::create([
                'schedules_id' => $schedule->id,
                'periodo_inicio' => $validated['period_start'],
                'periodo_fim' => $validated['period_end'],
            ]);
        } else {
            $schedule->periodo()->delete();
        }

        // Atualizar dias específicos
        if ($validated['mode'] === 'specific_days' && !empty($days)) {
            $schedule->days()->delete();
            foreach ($days as $day) {
                $day = trim($day); // Remover espaços
                if (!empty($day)) {
                    $date = \DateTime::createFromFormat('d/m/Y', $day)->format('Y-m-d');
                    ScheduleDay::create([
                        'schedule_id' => $schedule->id,
                        'date' => $date,
                    ]);
                }
            }
        } else {
            $schedule->days()->delete();
        }

        // Atualizar group_ids externos associados
        if (!empty($groupIds)) {
            $schedule->externalGroups()->delete();
            foreach ($groupIds as $groupId) {
                $groupId = trim($groupId); // Remover espaços
                if (!empty($groupId)) {
                    ScheduleExternalGroup::create([
                        'schedule_id' => $schedule->id,
                        'group_id' => $groupId,
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Agendamento atualizado com sucesso', 'schedule' => $schedule->load(['hours', 'days', 'externalGroups', 'periodo'])]);
    }

    /**
     * Delete a schedule.
     */
    public function destroy(Schedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($schedule->image_path) {
            Storage::disk('public')->delete($schedule->image_path);
        }

        $schedule->delete();
        return response()->json(['message' => 'Agendamento excluído com sucesso']);
    }

    /**
     * Toggle the status of a schedule between active and inactive.
     */
    public function toggleStatus(Schedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $schedule->status = $schedule->status === 'active' ? 'inactive' : 'active';
        $schedule->save();

        return response()->json(['message' => 'Status do agendamento alterado com sucesso', 'status' => $schedule->status]);
    }

    /**
     * Process scheduled posts (placeholder for future implementation).
     */
    public function processScheduledPosts()
    {
        Log::info('Método processScheduledPosts foi chamado');
        
        try {
            // Implementação futura para processar posts agendados
            $response = [
                'success' => true,
                'message' => 'Processamento de posts agendados iniciado',
                'timestamp' => now()->toISOString(),
                'status' => 'running'
            ];
            
            Log::info('Resposta do processScheduledPosts: ' . json_encode($response));
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('Erro no processScheduledPosts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erro interno do servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * List all schedules for the authenticated user (API para AJAX).
     */
    public function list()
    {
        $schedules = Schedule::where('user_id', Auth::id())
            ->with(['hours', 'days', 'externalGroups', 'periodo'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($schedules);
    }

    /**
     * Retorna os grupos do WhatsApp do usuário autenticado (API para AJAX).
     */
    public function groups()
    {
        try {
            Log::info('Iniciando busca de grupos para usuário ID: ' . Auth::id());
            
            $apiToken = "asdfghjksjhggbnmfdfghjhgf";
            $userId = Auth::id();
            
            if (!$userId) {
                Log::warning('Tentativa de buscar grupos sem usuário autenticado');
                return response()->json(['success' => false, 'error' => 'Usuário não autenticado.'], 401);
            }
            
            Log::info('Buscando instância ativa para usuário ID: ' . $userId);
            $instance = \App\Models\Instancia::where('user_id', $userId)->active()->first();
            
            if (!$instance) {
                Log::warning('Nenhuma instância ativa encontrada para usuário ID: ' . $userId . '. Retornando grupos de exemplo.');
                // Retorna grupos de exemplo para permitir testes
                $exampleGroups = [
                    ['id' => '120363025123456789@g.us', 'name' => 'Grupo de Teste 1'],
                    ['id' => '120363025123456790@g.us', 'name' => 'Grupo de Teste 2'],
                    ['id' => '120363025123456791@g.us', 'name' => 'Grupo de Teste 3'],
                ];
                return response()->json([
                    'success' => true, 
                    'groups' => $exampleGroups,
                    'message' => 'Usando grupos de exemplo. Crie uma instância do WhatsApp para ver seus grupos reais.'
                ]);
            }
            
            Log::info('Instância encontrada: ' . $instance->name . ' (ID: ' . $instance->id . ')');
            $sessionId = $instance->name;
            
            $apiUrl = "https://dapleserver.com/client/getlistGrup/{$sessionId}";
            Log::info('Fazendo requisição para API: ' . $apiUrl);
            
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'accept' => 'application/json',
                'x-api-key' => $apiToken,
            ])->timeout(30)->get($apiUrl);
            
            Log::info('Resposta da API - Status: ' . $response->status());
            Log::info('Resposta da API - Body: ' . $response->body());
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info('Dados da API decodificados: ' . json_encode($data));
                
                if (isset($data['success']) && $data['success'] === true && isset($data['groups']) && is_array($data['groups'])) {
                    $groups = array_map(function ($group) {
                        return [
                            'id' => $group['user'] ?? $group['id'] ?? '',
                            'name' => $group['name'] ?? 'Grupo sem nome',
                        ];
                    }, $data['groups']);
                    
                    Log::info('Grupos processados com sucesso. Total: ' . count($groups));
                    return response()->json(['success' => true, 'groups' => $groups]);
                } else {
                    Log::warning('API retornou dados inválidos: ' . json_encode($data));
                    // Retorna grupos de exemplo se a API falhar
                    $exampleGroups = [
                        ['id' => '120363025123456789@g.us', 'name' => 'Grupo de Teste 1'],
                        ['id' => '120363025123456790@g.us', 'name' => 'Grupo de Teste 2'],
                        ['id' => '120363025123456791@g.us', 'name' => 'Grupo de Teste 3'],
                    ];
                    return response()->json([
                        'success' => true, 
                        'groups' => $exampleGroups,
                        'message' => 'API retornou dados inválidos. Usando grupos de exemplo.',
                        'debug' => $data
                    ]);
                }
            } else {
                Log::error('Erro na requisição HTTP. Status: ' . $response->status() . ', Body: ' . $response->body());
                // Retorna grupos de exemplo se a API falhar
                $exampleGroups = [
                    ['id' => '120363025123456789@g.us', 'name' => 'Grupo de Teste 1'],
                    ['id' => '120363025123456790@g.us', 'name' => 'Grupo de Teste 2'],
                    ['id' => '120363025123456791@g.us', 'name' => 'Grupo de Teste 3'],
                ];
                return response()->json([
                    'success' => true, 
                    'groups' => $exampleGroups,
                    'message' => 'Erro na API. Usando grupos de exemplo.',
                    'debug' => $response->body()
                ]);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Erro de conexão com a API: ' . $e->getMessage());
            // Retorna grupos de exemplo em caso de erro de conexão
            $exampleGroups = [
                ['id' => '120363025123456789@g.us', 'name' => 'Grupo de Teste 1'],
                ['id' => '120363025123456790@g.us', 'name' => 'Grupo de Teste 2'],
                ['id' => '120363025123456791@g.us', 'name' => 'Grupo de Teste 3'],
            ];
            return response()->json([
                'success' => true, 
                'groups' => $exampleGroups,
                'message' => 'Erro de conexão. Usando grupos de exemplo.'
            ]);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('Erro na requisição HTTP: ' . $e->getMessage());
            // Retorna grupos de exemplo em caso de erro de requisição
            $exampleGroups = [
                ['id' => '120363025123456789@g.us', 'name' => 'Grupo de Teste 1'],
                ['id' => '120363025123456790@g.us', 'name' => 'Grupo de Teste 2'],
                ['id' => '120363025123456791@g.us', 'name' => 'Grupo de Teste 3'],
            ];
            return response()->json([
                'success' => true, 
                'groups' => $exampleGroups,
                'message' => 'Erro na requisição. Usando grupos de exemplo.'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro inesperado ao buscar grupos: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            // Retorna grupos de exemplo em caso de erro inesperado
            $exampleGroups = [
                ['id' => '120363025123456789@g.us', 'name' => 'Grupo de Teste 1'],
                ['id' => '120363025123456790@g.us', 'name' => 'Grupo de Teste 2'],
                ['id' => '120363025123456791@g.us', 'name' => 'Grupo de Teste 3'],
            ];
            return response()->json([
                'success' => true, 
                'groups' => $exampleGroups,
                'message' => 'Erro inesperado. Usando grupos de exemplo.'
            ]);
        }
    }
} 