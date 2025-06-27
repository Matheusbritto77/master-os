<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Garantia;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GarantiaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('verifica.assinatura');
    }
    /**
     * Mostra o histórico de garantias associadas ao usuário autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function historicoGarantias(Request $request)
    {
        $user_id = Auth::id(); // Obtém o ID do usuário autenticado
        $search = $request->input('search', '');

        if (!empty($search)) {
            $garantias = Garantia::where('user_id', $user_id)
                                ->where(function ($query) use ($search) {
                                    $query->where('nomeProduto', 'like', '%' . $search . '%')
                                          ->orWhere('tipoGarantia', 'like', '%' . $search . '%')
                                          ->orWhere('modeloAparelho', 'like', '%' . $search . '%');
                                })
                                ->get();
        } else {
            $garantias = Garantia::where('user_id', $user_id)->get();
        }

        return view('historicoGarantias', compact('garantias', 'search'));
    }

    /**
     * Armazena uma nova garantia no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
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
                    'message' => 'Dados da empresa não encontrados. Por favor, vá em Configurações > Empresa e cadastre os dados da sua empresa antes de criar uma garantia.',
                    'redirect' => route('config.empresa')
                ], 422);
            }

            // Validação dos dados recebidos
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone_number' => 'required|string|max:255',
                'cep' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'neighborhood' => 'required|string|max:255',
                'street' => 'required|string|max:255',
                'house_number' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'tipoGarantia' => 'required|string|max:255',
                'nomeProduto' => 'required|string|max:255',
                'tempoGarantiaProduto' => 'required|string|max:255',
                'servicoRealizado' => 'required|string|max:255',
                'modeloAparelho' => 'required|string|max:255',
                'tempoGarantiaServico' => 'required|string|max:255',
                'observacoes' => 'nullable|string|max:255',
            ], [
                'name.required' => 'O nome do cliente é obrigatório.',
                'phone_number.required' => 'O telefone é obrigatório.',
                'cep.required' => 'O CEP é obrigatório.',
                'city.required' => 'A cidade é obrigatória.',
                'neighborhood.required' => 'O bairro é obrigatório.',
                'street.required' => 'A rua é obrigatória.',
                'house_number.required' => 'O número é obrigatório.',
                'state.required' => 'O estado é obrigatório.',
                'tipoGarantia.required' => 'O tipo de garantia é obrigatório.',
                'nomeProduto.required' => 'O nome do produto é obrigatório.',
                'tempoGarantiaProduto.required' => 'O tempo de garantia do produto é obrigatório.',
                'servicoRealizado.required' => 'O serviço realizado é obrigatório.',
                'modeloAparelho.required' => 'O modelo do aparelho é obrigatório.',
                'tempoGarantiaServico.required' => 'O tempo de garantia do serviço é obrigatório.',
                'email.email' => 'O email deve ser válido.',
            ]);

            // Se a validação falhar, retorna os erros
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Adiciona o user_id aos dados antes de criar a garantia
            $data = $validator->validated();
            $data['user_id'] = Auth::id();

            // Cria uma nova garantia com os dados validados
            Garantia::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Garantia criada com sucesso!',
                'redirect' => route('gerar_pdf_ultima_garantia')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca garantias com base em um termo de pesquisa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $user_id = Auth::id(); // Obtém o ID do usuário autenticado
        $query = $request->input('search');
        $orderBy = $request->input('order_by', 'nomeProduto');
        $orderDirection = $request->input('order_direction', 'asc');

        $garantias = Garantia::where('user_id', $user_id)
                             ->where(function ($queryBuilder) use ($query) {
                                 $queryBuilder->where('nomeProduto', 'like', '%' . $query . '%')
                                              ->orWhere('tipoGarantia', 'like', '%' . $query . '%')
                                              ->orWhere('modeloAparelho', 'like', '%' . $query . '%');
                             })
                             ->orderBy($orderBy, $orderDirection)
                             ->get();

        return response()->json($garantias);
    }
}
