<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrientandoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            Auth::check() && 
            Auth::user()->funcao === 'orientando'
        ) {
            return $next($request);
        }

        abort(403, 'Acesso permitido apenas para orientandos.');
    }
}
