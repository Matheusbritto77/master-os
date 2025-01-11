<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assinatura;

class VerificaAssinatura
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário está autenticado
        $user = Auth::user();

        // Busca a assinatura do usuário logado
        $assinatura = Assinatura::where('user_id', $user->id)->first();

        // Verifica se a assinatura existe
        if (!$assinatura) {
            // Caso o usuário não tenha assinatura, pode deixar acessar a página normalmente ou redirecionar
            return $next($request);
        }

        // Se a assinatura existir, verifica o status
        if ($assinatura->status == 0) {
            // Redireciona o usuário para a página de pagamento se a assinatura estiver inativa
            return redirect('/pagina-de-pagamento')->with('message', 'Sua assinatura está inativa. Por favor, renove.');
        }

        // Se a assinatura estiver ativa, permite o acesso à página normalmente
        return $next($request);
    }
}
