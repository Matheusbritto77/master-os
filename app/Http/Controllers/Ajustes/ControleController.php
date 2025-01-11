<?php

namespace App\Http\Controllers\Ajustes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\Controle;
use Illuminate\Support\Facades\Hash;
use App\Models\ModeloOrdem;
use App\Http\Controllers\Controller;


class ControleController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    

    public function registerControle(Request $request)
    {
        $user = Auth::user();
    
        // Validação dos dados recebidos
        $request->validate([
            'password' => 'nullable|string',
            'ajustes' => 'nullable|boolean',
            'historico_de_caixa' => 'nullable|boolean',
            'relatorio_lucro' => 'nullable|boolean',
            'relatorio_bruto' => 'nullable|boolean',
            'excluir_registro_caixa' => 'nullable|boolean',
        ]);
    
        // Buscar o controle existente para o usuário logado
        $controle = Controle::where('user_id', $user->id)->first();
    
        // Se o controle já existe, atualize-o
        if ($controle) {
            $controle->ajustes = $request->input('ajustes') ?? 0;
            $controle->historico_de_caixa = $request->input('historico_de_caixa') ?? 0;
            $controle->relatorio_lucro = $request->input('relatorio_lucro') ?? 0;
            $controle->relatorio_bruto = $request->input('relatorio_bruto') ?? 0;
            $controle->excluir_caixa = $request->input('excluir_registro_caixa') ?? 0;
            
            // Atualiza a senha apenas se a nova senha for fornecida
            if ($request->has('password') && !empty($request->input('password'))) {
                $controle->password = bcrypt($request->input('password')); // Criptografa a nova senha
            }
    
            $controle->save();
    
            return response()->json(['success' => 'Controle atualizado com sucesso.']);
        } else {
            // Se o controle não existe, crie um novo
            $controle = new Controle();
            $controle->user_id = $user->id;
            $controle->password = bcrypt($request->input('password')); // Criptografa a senha
            $controle->ajustes = $request->input('ajustes') ?? 0;
            $controle->historico_de_caixa = $request->input('historico_de_caixa') ?? 0;
            $controle->relatorio_lucro = $request->input('relatorio_lucro') ?? 0;
            $controle->relatorio_bruto = $request->input('relatorio_bruto') ?? 0;
            $controle->excluir_caixa = $request->input('excluir_registro_caixa') ?? 0;
            $controle->save();
    
            return response()->json(['success' => 'Controle registrado com sucesso.']);
        }
    }
    
// Método para exibir a view de configurações do cliente
public function showCocontrole()
{
    return view('ajustes.controle-form');
}


}