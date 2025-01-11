<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Assinatura;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\Authenticatable  $user
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        Log::info('Usuário autenticado: ' . $user->id);

        // Verifica a assinatura do usuário
        $assinatura = Assinatura::where('user_id', $user->id)->first();

        if ($assinatura) {
            if ($assinatura->status == 0) {
                // Redireciona para a página de pagamento ou outra ação para usuários não pagos
                Log::info('Assinatura não paga para o usuário: ' . $user->id);
                return redirect()->route('pagina.de.pagamento'); // Atualize com a rota correta
            } else {
                // Assinatura paga, continua o redirecionamento padrão
                Log::info('Assinatura paga para o usuário: ' . $user->id);
            }
        } else {
            // Nenhum registro de assinatura encontrado, permite login normal
            Log::info('Nenhum registro de assinatura encontrado para o usuário: ' . $user->id);
        }

        // Retorna a resposta padrão do redirecionamento
        return redirect()->intended($this->redirectTo);
    }
}