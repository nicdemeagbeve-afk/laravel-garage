<?php

namespace App\Http\Controllers;

use App\Models\Breakdown;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GestionClientController extends Controller
{
    /**
     * Constructeur: Vérifier que l'utilisateur est 'gestion_client'
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || auth()->user()->role !== 'gestion_client') {
                abort(403, 'Accès interdit. Vous n\'avez pas la permission de gérer les clients.');
            }
            return $next($request);
        });
    }

    /**
     * Afficher le tableau de bord Gestion Client.
     * Affiche les clients gérés, leurs véhicules, les pannes, et les statistiques.
     */
    public function index()
    {
        $stats = [
            'clients_total' => User::where('role', 'client')->count(),
            'clients_actifs' => User::where('role', 'client')->where('is_active', true)->count(),
            'clients_en_attente' => User::where('role', 'client')->where('is_active', false)->count(),
            'vehicules_total' => Vehicule::count(),
            'pannes_totales' => Breakdown::count(),
            'pannes_en_attente' => Breakdown::where('status', 'pending')->count(),
            'pannes_en_cours' => Breakdown::where('status', 'in_progress')->count(),
            'pannes_resolues' => Breakdown::where('status', 'resolved')->count(),
        ];

        $pannesRecentes = Breakdown::with(['user', 'vehicule', 'technicien'])
                                   ->latest()
                                   ->limit(8)
                                   ->get();

        $clientsRecents = User::where('role', 'client')
                             ->latest()
                             ->limit(6)
                             ->get();

        $clientsEnAttente = User::where('role', 'client')
                                ->where('is_active', false)
                                ->latest()
                                ->limit(5)
                                ->get();

        $vehiculesRecents = Vehicule::with('user')
                                    ->latest()
                                    ->limit(5)
                                    ->get();

        return view('dashboards.gestion-client', compact(
            'stats',
            'pannesRecentes',
            'clientsRecents',
            'clientsEnAttente',
            'vehiculesRecents'
        ));
    }
}