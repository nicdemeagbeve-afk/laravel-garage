<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Middleware pour vérifier les rôles utilisateur
     * 
     * Utilisation:
     * Route::get(...)->middleware('role:admin,responsable_services')
     * 
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Vérifier l'utilisateur est authentifié
        if (!auth()->check()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        // Vérifier le rôle
        $userRole = auth()->user()->role;
        if (!in_array($userRole, $roles)) {
            return response()->json([
                'error' => 'Accès refusé. Rôle requis: ' . implode(', ', $roles)
            ], 403);
        }

        return $next($request);
    }
}
