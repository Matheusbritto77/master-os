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

class ConfigController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    
   



    // Método para carregar a página de ajustes
    public function ajustes(Request $request)
    {
        $user = Auth::user();

        // Verifica se o usuário está autenticado
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        // Obter controle do usuário usando o modelo
        $controle = Controle::where('user_id', $user->id)->first();

        // Verificar se o controle exige senha para ajustes
        if ($controle && $controle->ajustes == 1) {
            if ($request->isMethod('post') && $request->has('senha')) {
                // Verifica a senha fornecida
                $senha = $request->input('senha');
                // Comparar a senha fornecida com a armazenada na tabela controle
                if (Hash::check($senha, $controle->password)) {
                    // Senha correta, continua o carregamento da página de ajustes
                    return $this->carregarAjustes($user);
                } else {
                    // Senha incorreta
                    return response()->json(['error' => 'Senha incorreta.'], 403);
                }
            }

            // Se a senha não for fornecida, exibe o modal
            return view('modal.ajustes-senha-modal');
        }

        // Se não houver controle ou não é necessário senha, continua o carregamento da página de ajustes
        return $this->carregarAjustes($user);
    }

    /**
     * Carrega a página de ajustes com os dados do usuário e o logo codificado em base64.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    protected function carregarAjustes($user)
    {
        

        // Retorna a view ajustes.blade.php com os dados do usuário e logo_base64
        return view('ajustes');
    }
    
    
    
    
    
    
    
    
    
    
    
   
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    
    
    
    
    
    
}


