<?php

namespace App\Http\Controllers\Ajustes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class LogoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    
    // Método para atualizar a logo
    public function updateLogo(Request $request)
    {
        // Validar o arquivo da logo
        $request->validate([
            'logoFile' => 'required|mimes:jpeg,jpg|max:2048', // Validação para arquivos JPG
        ]);

        if ($request->hasFile('logoFile')) {
            $user = Auth::user();
            $extension = $request->file('logoFile')->getClientOriginalExtension();
            $fileName = 'logo_' . $user->id . '.' . $extension;

            // Salvar a imagem na pasta public/images
            $request->file('logoFile')->move(public_path('images'), $fileName);

            return response()->json(['success' => 'Logo do sistema atualizada com sucesso.']);
        }

        return response()->json(['error' => 'Nenhum arquivo de imagem enviado.'], 400);
    }

    // Método para exibir o formulário de edição da logo
public function showLogoForm()
{
    $userId = Auth::id();
    $logoUrl = asset("images/logo_{$userId}.jpg");

    return view('ajustes.logo', compact('logoUrl'));
}

}
