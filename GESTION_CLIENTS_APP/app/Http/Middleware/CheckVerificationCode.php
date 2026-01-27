<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckVerificationCode
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
        // Si l'utilisateur est connecté et a un code de vérification
        if ($request->user() && $request->user()->verification_code) {
            // Rediriger vers la page de vérification du code sauf si c'est déjà la page de vérification
            if ($request->route()->getName() !== 'verify-code' && $request->route()->getName() !== 'verify-code.store' && $request->route()->getName() !== 'logout') {
                return redirect()->route('verify-code');
            }
        }

        return $next($request);
    }
}
