<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validação dos dados do usuário
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'cpf_cnpj' => ['required', 'string', 'unique:users', function($attribute, $value, $fail) {
                $this->validateCpfCnpj($value, $fail);  // Validação customizada do CPF ou CNPJ
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Criação do usuário
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Remover caracteres não numéricos do CPF/CNPJ
        $cpf_cnpj = preg_replace('/\D/', '', $data['cpf_cnpj']);
        
        // Verificando se o CPF ou CNPJ é válido
        $this->validateCpfCnpj($cpf_cnpj, null);
        
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'cpf_cnpj' => $cpf_cnpj, // Armazenando CPF ou CNPJ
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Validação de CPF ou CNPJ
     *
     * @param  string  $cpf_cnpj
     * @param  \Closure|null  $fail
     */
    protected function validateCpfCnpj($cpf_cnpj, $fail = null)
    {
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

    /**
     * Validação de CPF
     *
     * @param  string  $cpf
     * @return bool
     */
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

    /**
     * Validação de CNPJ
     *
     * @param  string  $cnpj
     * @return bool
     */
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
}
