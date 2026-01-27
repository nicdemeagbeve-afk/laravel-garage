<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Breakdown;
use App\Models\Vehicule;
use App\Models\Reparation;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Créer une nouvelle instance du contrôleur.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le tableau de bord principal.
     * Redirige vers le bon dashboard selon le rôle de l'utilisateur.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isGestionClient()) {
            return redirect()->route('gestion_client.dashboard');
        } elseif ($user->isResponsableServices()) {
            return redirect()->route('dashboards.responsable-services');
        } else {
            return redirect()->route('dashboards.client');
        }
    }

    /**
     * Tableau de bord pour les clients.
     */
    public function clientDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'mes_vehicules' => Vehicule::where('user_id', $user->id)->count(),
            'pannes_totales' => Breakdown::where('user_id', $user->id)->count(),
            'pannes_en_cours' => Breakdown::where('user_id', $user->id)
                                          ->whereIn('status', ['pending', 'in_progress'])
                                          ->count(),
            'pannes_resolues' => Breakdown::where('user_id', $user->id)
                                          ->where('status', 'resolved')
                                          ->count(),
        ];

        $mesVehicules = Vehicule::where('user_id', $user->id)->latest()->get();
        $mesPannes = Breakdown::where('user_id', $user->id)
                             ->with(['vehicule', 'technicien'])
                             ->latest()
                             ->limit(5)
                             ->get();

        return view('dashboards.client', compact('stats', 'mesVehicules', 'mesPannes'));
    }

    /**
     * Tableau de bord pour les responsables de services.
     */
    public function responsableServicesDashboard()
    {
        $stats = [
            'reparations_total' => Reparation::count(),
            'reparations_en_cours' => Reparation::whereNull('duree_main_oeuvre')->count(),
            'services_total' => Service::count(),
            'techniciens_total' => User::where('role', 'technicien')->count(),
            'pannes_en_attente' => Breakdown::where('status', 'pending')->count(),
        ];

        $reparationsRecentes = Reparation::with(['vehicule', 'technicien'])
                                         ->latest()
                                         ->limit(8)
                                         ->get();
        
        $pannesEnAttente = Breakdown::where('status', 'pending')
                                   ->with(['user', 'vehicule'])
                                   ->latest()
                                   ->limit(5)
                                   ->get();

        $servicesDisponibles = Service::latest()->limit(6)->get();

        return view('dashboards.responsable-services', compact('stats', 'reparationsRecentes', 'pannesEnAttente', 'servicesDisponibles'));
    }
}
