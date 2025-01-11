<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ClienteController;

// Rota de login (não exige autenticação)
Route::post('login', function (Request $request) {
    // Validação do formulário de login
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        // Recupera o usuário autenticado
        $user = Auth::user();

        // Revoga todos os tokens antigos do usuário
        $user->tokens->each(function ($token) {
            $token->delete();
        });

        // Cria o novo token de acesso
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        // Login bem-sucedido com token gerado
        return response()->json([
            'message' => 'Login bem-sucedido',
            'token' => $token
        ], 200);
    } else {
        // Falha no login
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }
});


Route::middleware('auth:sanctum')->group(function () {
    Route::any('/clientes', [ClienteController::class, 'store']);
    Route::any('/clientes/index', [ClienteController::class, 'index']);
    Route::get('/clientes/search', [ClienteController::class, 'search']);
    
    return response()->json(['message' => 'Credenciais inválidas'], 401);
});

// A resposta personalizada para o erro de autenticação pode ser tratada diretamente pelo Sanctum
// Caso queira personalizar, você pode fazer isso no handler do Sanctum.






// Customizando resposta para autenticação inválida
Route::middleware('auth:api')->get('/unauthorized', function () {
    return response()->json(['message' => 'Credenciais inválidas'], 401);
});
