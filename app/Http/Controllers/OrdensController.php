<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Ordem;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Funcionario;
use Illuminate\Support\Facades\Validator;

class OrdensController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('verifica.assinatura');
    }

    /**
     * Método para exibir o histórico de ordens de serviço do usuário autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search', '');

        if (!empty($search)) {
            $ordens = Ordem::where('user_id', $userId)
                           ->where('cliente', 'like', '%' . $search . '%')
                           ->get();
        } else {
            $ordens = Ordem::where('user_id', $userId)->get();
        }

        return view('ordens_servico', compact('ordens'));
    }

    /**
     * Exibe o formulário para criar uma nova ordem de serviço.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('add_ordem');
    }

    /**
     * Armazena uma nova ordem de serviço para o usuário autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Verificar se o usuário tem dados de empresa cadastrados
            $empresa = Empresa::where('user_id', Auth::id())->first();
            
            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados da empresa não encontrados. Por favor, vá em Configurações > Empresa e cadastre os dados da sua empresa antes de criar uma ordem de serviço.',
                    'redirect' => route('config.empresa')
                ], 422);
            }

            // Validação dos dados
            $validator = Validator::make($request->all(), [
                'cliente' => 'required|string|max:255',
                'cidade' => 'required|string|max:255',
                'cep' => 'required|string|max:10',
                'rua' => 'required|string|max:255',
                'bairro' => 'required|string|max:255',
                'modelo' => 'required|string|max:255',
                'problema' => 'required|string',
                'observacoes' => 'nullable|string',
                'phone_number' => 'required|string|max:20',
                'state' => 'required|string|max:255',
                'numero' => 'required|string|max:10',
                'tecnico' => 'required|exists:funcionarios,id',
                'atendente' => 'required|exists:funcionarios,id',
            ], [
                'cliente.required' => 'O nome do cliente é obrigatório.',
                'cidade.required' => 'A cidade é obrigatória.',
                'cep.required' => 'O CEP é obrigatório.',
                'rua.required' => 'A rua é obrigatória.',
                'bairro.required' => 'O bairro é obrigatório.',
                'modelo.required' => 'O modelo do equipamento é obrigatório.',
                'problema.required' => 'O problema relatado é obrigatório.',
                'phone_number.required' => 'O telefone é obrigatório.',
                'state.required' => 'O estado é obrigatório.',
                'numero.required' => 'O número é obrigatório.',
                'tecnico.required' => 'O técnico é obrigatório.',
                'tecnico.exists' => 'O técnico selecionado não existe.',
                'atendente.required' => 'O atendente é obrigatório.',
                'atendente.exists' => 'O atendente selecionado não existe.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Criar uma nova ordem
            $ordem = new Ordem();
            
            // Preencher os campos com os dados do request
            $ordem->cliente = $request->cliente;
            $ordem->cidade = $request->cidade;
            $ordem->cep = $request->cep;
            $ordem->rua = $request->rua;
            $ordem->bairro = $request->bairro;
            $ordem->modelo = $request->modelo;
            $ordem->problema_relatado = $request->problema;
            $ordem->observacoes = $request->observacoes;
            $ordem->phone_number = $request->phone_number;
            $ordem->state = $request->state;
            $ordem->numero = $request->numero;
            
            // Obter os nomes do técnico e do atendente a partir dos seus IDs
            $tecnico = Funcionario::find($request->tecnico);
            $atendente = Funcionario::find($request->atendente);
            
            // Verificar se os técnicos e atendentes foram encontrados
            if (!$tecnico || !$atendente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Técnico ou atendente não encontrado. Por favor, verifique se eles estão cadastrados no sistema.'
                ], 422);
            }

            // Preencher os nomes do técnico e do atendente na ordem
            $ordem->tecnico = $tecnico->tecnico;
            $ordem->atendente = $atendente->atendente;

            // Associar a ordem ao usuário autenticado
            $ordem->user_id = Auth::id();

            // Salvar a ordem de serviço
            $ordem->save();

            return response()->json([
                'success' => true,
                'message' => 'Ordem de serviço criada com sucesso!',
                'redirect' => route('gerarPDFUltima')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca clientes com base no termo fornecido para o usuário autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarCliente(Request $request)
    {
        $query = $request->input('query', '');
        $userId = Auth::id();

        $clients = Cliente::where('user_id', $userId)
                          ->where(function ($queryBuilder) use ($query) {
                              $queryBuilder->where('name', 'like', '%' . $query . '%')
                                           ->orWhere('email', 'like', '%' . $query . '%')
                                           ->orWhere('phone_number', 'like', '%' . $query . '%');

                          })
                          ->get();

        return response()->json($clients);
    }

    /**
     * Exibe detalhes de um cliente específico para o usuário autenticado.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $userId = Auth::id();
        $client = Cliente::where('user_id', $userId)->find($id);

        if (!$client) {
            return response()->json(['error' => 'Cliente não encontrado'], 404);
        }

        return response()->json($client);
    }
}
