<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ApiRegisterController extends Controller
{
    /**
     * Handle the user registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Definir as regras de validação
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'codigo_unico' => 'nullable|string|max:255|unique:users,codigo_unico',
            'revendedor_id' => 'nullable|integer|exists:revendedores,id',
        ]);

        // Verificar se a validação falhou
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }

        // Criar o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'codigo_unico' => $request->codigo_unico ?? Str::uuid()->toString(),
            'revendedor_id' => $request->revendedor_id,
        ]);

        // (Opcional) Gerar um token de API para o usuário
        // $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Usuário registrado com sucesso.',
            'data' => [
                'user' => $user,
                // 'token' => $token, // Inclua se estiver usando tokens de API
            ],
        ], 201)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

    /**
     * Handle OPTIONS requests for CORS preflight.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        return response()->json([], 204)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}
