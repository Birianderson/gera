<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthSolicitacaoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request);
        }
        // Pega o último segmento da URL (o token)
        $token = $request->segment(3); // Ou ajuste para pegar o segmento correto

        // Redireciona para a página de login, passando o token como parâmetro "id"
        return redirect()->route('solicitacao_login', ['id' => $token]);
    }
}
