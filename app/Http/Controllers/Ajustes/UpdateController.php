<?php

namespace App\Http\Controllers\Ajustes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;  // Altere para o modelo User, que é onde o cpf_cnpj está armazenado
use Illuminate\Support\Facades\Hash;
use App\Models\ModeloOrdem;

class UpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    // Método para atualizar as configurações, incluindo nome, email e cpf_cnpj
    public function update(Request $request, $id)
    {
        // Validação dos dados recebidos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'cpf_cnpj' => ['nullable', 'string', 'unique:users,cpf_cnpj,' . $id, function ($attribute, $value, $fail) {
                // Verifica se o CPF ou CNPJ já existe em outro usuário
                if ($value && User::where('cpf_cnpj', $value)->where('id', '!=', Auth::id())->exists()) {
                    $fail('O CPF ou CNPJ informado já está em uso por outro usuário.');
                }
                
                // Validação de CPF ou CNPJ
                if ($value) {
                    $this->validateCpfCnpj($value, $fail);
                }
            }],
        ]);

        // Lógica para atualizar os dados do usuário, como nome e email
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Verifica se o CPF ou CNPJ foi alterado e se o campo foi preenchido
        if ($request->has('cpf_cnpj')) {
            $user->cpf_cnpj = $request->input('cpf_cnpj');
        }

        $user->save();

        // Verifica se o e-mail foi alterado
        if ($user->wasChanged('email')) {
            // Marca o e-mail como não verificado novamente
            $user->email_verified_at = null;
            $user->save();

            // Envie novamente o e-mail de verificação
            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => 'Configurações atualizadas com sucesso. Um novo e-mail de verificação foi enviado para o seu novo endereço de e-mail.'
            ]);
        }

        return response()->json(['success' => 'Configurações atualizadas com sucesso.']);
    }

    // Método para validar CPF ou CNPJ
    protected function validateCpfCnpj($cpf_cnpj, $fail)
    {
        // Remover caracteres não numéricos do CPF/CNPJ
        $cpf_cnpj = preg_replace('/\D/', '', $cpf_cnpj);

        if (strlen($cpf_cnpj) == 11) {
            // Validação de CPF
            if (!$this->validateCpf($cpf_cnpj)) {
                $fail('O CPF informado é inválido.');
            }
        } elseif (strlen($cpf_cnpj) == 14) {
            // Validação de CNPJ
            if (!$this->validateCnpj($cpf_cnpj)) {
                $fail('O CNPJ informado é inválido.');
            }
        } else {
            $fail('O CPF ou CNPJ informado é inválido.');
        }
    }

    // Método para validar CPF
    protected function validateCpf($cpf)
    {
        // Verificação de CPF repetido (ex: 111.111.111-11)
        if (preg_match('/^(\d)\1{10}$/', $cpf)) return false;

        // Cálculo do primeiro dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }
        $digit1 = ($sum * 10) % 11;
        if ($digit1 == 10) $digit1 = 0;
        if ($cpf[9] != $digit1) return false;

        // Cálculo do segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }
        $digit2 = ($sum * 10) % 11;
        if ($digit2 == 10) $digit2 = 0;
        if ($cpf[10] != $digit2) return false;

        return true;
    }

    // Método para validar CNPJ
    protected function validateCnpj($cnpj)
    {
        // Verificação de CNPJ repetido (ex: 11111111000195)
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) return false;

        // Cálculo do primeiro dígito verificador
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * (($i % 8) + 2);
        }
        $digit1 = $sum % 11;
        if ($digit1 < 2) $digit1 = 0;
        else $digit1 = 11 - $digit1;
        if ($cnpj[12] != $digit1) return false;

        // Cálculo do segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * (($i % 8) + 2);
        }
        $digit2 = $sum % 11;
        if ($digit2 < 2) $digit2 = 0;
        else $digit2 = 11 - $digit2;
        if ($cnpj[13] != $digit2) return false;

        return true;
    }

    // Método para exibir a view de configurações do cliente
    public function showConfigCliente()
    {
        return view('ajustes.config-cliente');
    }
}
