<?php

namespace App\Http\Controllers\Ajustes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ModeloOrdem;
use App\Http\Controllers\Controller;

class OrdemController extends Controller
{
    /**
     * Atualiza ou cria um registro na tabela modelo_ordem com base nos dados do formulário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function atualizarOuCriarModeloOrdem(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'modelo_1' => 'required|in:0,1',
            'modelo_2' => 'required|in:0,1',
            'modelo_3' => 'required|in:0,1',
            'modelo_4' => 'required|in:0,1',
        ]);

        // Obtém o usuário logado
        $userId = Auth::id();

        // Tenta encontrar um registro existente para o usuário logado
        $modeloOrdem = ModeloOrdem::where('user_id', $userId)->first();

        if ($modeloOrdem) {
            // Se o registro existir, atualiza os campos com os valores do formulário
            $modeloOrdem->update([
                'modelo_1' => $validatedData['modelo_1'],
                'modelo_2' => $validatedData['modelo_2'],
                'modelo_3' => $validatedData['modelo_3'],
                'modelo_4' => $validatedData['modelo_4'],
            ]);
        } else {
            // Se o registro não existir, cria um novo com os valores do formulário
            ModeloOrdem::create([
                'modelo_1' => $validatedData['modelo_1'],
                'modelo_2' => $validatedData['modelo_2'],
                'modelo_3' => $validatedData['modelo_3'],
                'modelo_4' => $validatedData['modelo_4'],
                'user_id' => $userId,
            ]);
        }

        // Retorna uma resposta indicando que a operação foi bem-sucedida
        return response()->json(['message' => 'Modelo de ordem atualizado ou criado com sucesso.']);
    }

    /**
     * Exibe a visualização do modelo solicitado.
     *
     * @param  string  $model
     * @return \Illuminate\Http\Response
     */
    public function previewModel($model)
    {
        // Defina a lista de modelos válidos
        $validModels = ['modelo_1', 'modelo_2', 'modelo_3', 'modelo_4'];

        // Verifique se o modelo é válido
        if (!in_array($model, $validModels)) {
            abort(404); // Exibe uma página 404 se o modelo não for válido
        }

        // Retorne a visualização correspondente no diretório 'modelos/preview'
        return view("modelos.preview.$model");
    }

    /**
     * Exibe a view de configurações do cliente.
     *
     * @return \Illuminate\Http\Response
     */
    public function showOrdemModelo()
    {
        return view('ajustes.modelo-form');
    }
}
