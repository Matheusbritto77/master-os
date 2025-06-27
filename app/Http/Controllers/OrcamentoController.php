<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orcamento;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PdfController;
use App\Models\Funcionario;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class OrcamentoController extends Controller

{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('verifica.assinatura');
    }
    /**
     * Exibe o formulário para adicionar um novo orçamento.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function orcamento()
    {
        // Aqui você pode buscar os técnicos e atendentes para preencher o select no formulário
        $tecnicos = Funcionario::where('tecnico', true)->get();
        $atendentes = Funcionario::where('atendente', true)->get();

        return view('add_orcamento', compact('tecnicos', 'atendentes'));
    }

    /**
     * Armazena uma nova orcamento de serviço para o usuário autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createorcamento(Request $request)
    {
        try {
            // Verificar se o usuário tem dados de empresa cadastrados
            $empresa = Empresa::where('user_id', Auth::id())->first();
            
            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados da empresa não encontrados. Por favor, vá em Configurações > Empresa e cadastre os dados da sua empresa antes de criar um orçamento.',
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

            // Criar uma nova orcamento
            $orcamento = new Orcamento();
            
            // Preencher os campos com os dados do request
            $orcamento->cliente = $request->cliente;
            $orcamento->cidade = $request->cidade;
            $orcamento->cep = $request->cep;
            $orcamento->rua = $request->rua;
            $orcamento->bairro = $request->bairro;
            $orcamento->modelo = $request->modelo;
            $orcamento->problema_relatado = $request->problema;
            $orcamento->observacoes = $request->observacoes;
            $orcamento->phone_number = $request->phone_number;
            $orcamento->state = $request->state;
            $orcamento->numero = $request->numero;
            
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

            // Preencher os nomes do técnico e do atendente na orcamento
            $orcamento->tecnico = $tecnico->tecnico;
            $orcamento->atendente = $atendente->atendente;

            // Associar a orcamento ao usuário autenticado
            $orcamento->user_id = Auth::id();

            // Salvar a orcamento de serviço
            $orcamento->save();

            return response()->json([
                'success' => true,
                'message' => 'Orçamento criado com sucesso!',
                'redirect' => route('gerarPDFUltimoOrcamento')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibe o histórico de orçamentos do usuário autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // Obtém o ID do usuário autenticado
        $userId = Auth::id();

        $search = $request->input('search', '');

        // Filtra os orçamentos do usuário autenticado pelo cliente, se houver busca
        $ordens = Orcamento::where('user_id', $userId)
                           ->when($search, function ($query) use ($search) {
                               return $query->where('cliente', 'like', '%' . $search . '%');
                           })
                           ->get();

        return view('orcamentos', compact('ordens', 'search'));
    }

    /**
     * Armazena um novo orçamento no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Obtém o ID do usuário autenticado
        $userId = Auth::id();

        // Captura os dados do formulário
        $data = $request->all();

        // Adiciona o user_id aos dados do orçamento
        $data['user_id'] = $userId;

        // Cria um novo orçamento
        Orcamento::create($data);

        // Redireciona de volta à página de listagem de orçamentos com uma mensagem de sucesso
        return redirect()->route('orcamentos.index')->with('success', 'Orçamento criado com sucesso!');
    }

    /**
     * Pesquisa orçamentos do usuário autenticado com base no cliente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Obtém o ID do usuário autenticado
        $userId = Auth::id();

        $query = $request->input('search');
        $orderBy = $request->input('order_by', 'cliente');
        $orderDirection = $request->input('order_direction', 'asc');

        // Pesquisa orçamentos do usuário autenticado pelo cliente
        $ordens = Orcamento::where('user_id', $userId)
                           ->where('cliente', 'like', '%' . $query . '%')
                           ->orderBy($orderBy, $orderDirection)
                           ->get();

        return response()->json($ordens);
    }
}
