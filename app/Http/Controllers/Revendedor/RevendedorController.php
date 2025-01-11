<?php

namespace App\Http\Controllers\Revendedor;

use App\Models\Revendedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Importar a classe Str

class RevendedorController extends Controller
{
    /**
     * Exibe o formulário de cadastro do revendedor.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Retorna a view para cadastrar um revendedor
        return view('Revendedores.cadastrar_revendedor');
    }

    /**
     * Gera um código único aleatório.
     *
     * @return string
     */
    private function gerarCodigoUnico()
    {
        do {
            $codigo = strtoupper(Str::random(10)); // Gera um código aleatório de 10 caracteres
        } while (Revendedor::where('codigo_unico', $codigo)->exists()); // Verifica se já existe

        return $codigo;
    }

    /**
     * Registra um novo revendedor na tabela revendedores.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registrar(Request $request)
    {
        try {
            // Validação dos dados do revendedor
            $request->validate([
                'nome' => 'required|string|max:255',
                'estado' => 'required|string|max:50',
                'cidade' => 'required|string|max:100',
                'bairro' => 'nullable|string|max:100',
                'rua' => 'nullable|string|max:255',
                'numero' => 'nullable|string|max:10',
                'complemento' => 'nullable|string|max:100',
                'cpf_cnpj' => 'required|string|max:20|unique:revendedores', // CPF ou CNPJ deve ser único
                'chave_pix' => 'nullable|string|max:50',
                'telefone' => 'nullable|string|max:15',
                'email' => 'required|string|email|max:255|unique:revendedores', // Email deve ser único
                'senha' => 'required|string|min:8', // Senha obrigatória com mínimo de 8 caracteres
            ]);

            // Gera um código único para o revendedor
            $codigo_unico = $this->gerarCodigoUnico();

            // Criação do novo revendedor
            $revendedor = Revendedor::create([
                'nome' => $request->input('nome'),
                'estado' => $request->input('estado'),
                'cidade' => $request->input('cidade'),
                'bairro' => $request->input('bairro'),
                'rua' => $request->input('rua'),
                'numero' => $request->input('numero'),
                'complemento' => $request->input('complemento'),
                'cpf_cnpj' => $request->input('cpf_cnpj'),
                'chave_pix' => $request->input('chave_pix'),
                'codigo_unico' => $codigo_unico, // Código único gerado automaticamente
                'telefone' => $request->input('telefone'),
                'email' => $request->input('email'), // Novo campo de email
                'senha' => Hash::make($request->input('senha')), // Hash da senha
                'comissao' => 40.00, // Comissão padrão de 40%
                'credito' => 0.00, // Crédito inicial padrão de 0
                'tipo' => 1, // Tipo padrão 1 (Bronze)
            ]);

            // Retorna uma resposta bem-sucedida
            return response()->json([
                'message' => 'Revendedor registrado com sucesso.',
                'revendedor' => $revendedor,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura erros de validação
            return response()->json([
                'message' => 'Erro de validação.',
                'errors' => $e->errors(), // Retorna os erros de validação
            ], 422);
        } catch (QueryException $e) {
            // Captura erros de consulta, como problemas de unicidade
            return response()->json([
                'message' => 'Erro ao registrar o revendedor.',
                'error' => 'Erro de consulta no banco de dados.',
                'details' => $e->getMessage(), // Retorna detalhes do erro
            ], 500);
        } catch (\Exception $e) {
            // Captura outros erros
            return response()->json([
                'message' => 'Erro inesperado.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
