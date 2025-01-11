<?php

namespace App\Http\Controllers\Ajustes;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class EmpresaController extends Controller
{
    /**
     * Armazena ou atualiza uma empresa na tabela.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'cep' => 'required|string|max:10',
            'bairro' => 'required|string|max:255',
            'rua' => 'required|string|max:255',
            'estado' => 'required|string|max:2', // Exemplo para estado, como "SP", "RJ"
            'cidade' => 'required|string|max:255',
        ]);

        // Obtém o usuário logado
        $userId = Auth::id();

        // Tenta encontrar um registro existente para o usuário logado
        $empresa = Empresa::where('user_id', $userId)->first();

        if ($empresa) {
            // Se o registro existir, atualiza os campos com os valores do formulário
            $empresa->update($validatedData);

            // Resposta JSON de sucesso
            return response()->json(['success' => 'Empresa atualizada com sucesso.']);
        } else {
            // Se o registro não existir, cria um novo
            Empresa::create(array_merge($validatedData, ['user_id' => $userId]));

            // Resposta JSON de sucesso
            return response()->json(['success' => 'Empresa registrada com sucesso.']);
        }
    }

    /**
     * Exibe o formulário de empresa com os dados existentes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtém a empresa associada ao usuário logado
        $empresa = Empresa::where('user_id', Auth::id())->first();

        return view('ajustes.empresa-form', compact('empresa')); // Passa a empresa para a view
    }
}
