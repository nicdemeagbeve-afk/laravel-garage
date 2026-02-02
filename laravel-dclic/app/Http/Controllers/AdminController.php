<?php

namespace App\Http\Controllers;

use App\Models\Breakdown;
use App\Models\Reparation;
use App\Models\User;
use App\Models\Service;
use App\Models\Technicien; // Assurez-vous d'importer le modèle Technicien
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Pour utiliser les requêtes SQL brutes ou des agrégats

class AdminController extends Controller
{
    /**
     * Constructeur: Vérifier que l'utilisateur est admin
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    /**
     * Afficher le dashboard admin
     */
    public function admin()
    {
        $stats = [
            'breakdowns_pending' => Breakdown::where('status', 'pending')->count(),
            'breakdowns_in_progress' => Breakdown::where('status', 'in_progress')->count(),
            'breakdowns_resolved' => Breakdown::where('status', 'resolved')->count(),
            'users_total' => User::count(), // Tous les utilisateurs actifs/inactifs
            'users_inactive' => User::where('is_active', false)->count(),
            'reparations_total' => Reparation::count(),
            'services_total' => Service::count(),
            'techniciens_total' => Technicien::count(),
        ];

        $reparations = Reparation::with(['vehicule', 'technicien'])->latest()->limit(10)->get();
        $breakdowns = Breakdown::with(['user', 'vehicule', 'technicien'])->latest()->limit(10)->get();
        $pending_users = User::where('is_active', false)->get();

        // Logique pour les techniciens les plus demandés
        $top_technicians = Technicien::select('techniciens.id', 'techniciens.nom', DB::raw('COUNT(breakdowns.id) as breakdown_count'))
                                     ->leftJoin('breakdowns', 'techniciens.id', '=', 'breakdowns.technicien_id')
                                     ->groupBy('techniciens.id', 'techniciens.nom')
                                     ->orderByDesc('breakdown_count')
                                     ->limit(5) // Limitez au top 5, par exemple
                                     ->get();

        return view('admin.dashboard', compact('stats', 'reparations', 'breakdowns', 'pending_users', 'top_technicians'));
    }

    /**
     * Approuver un compte Gestion_Client
     */
    public function approveGestionClient(User $user)
    {
        if ($user->role !== 'gestion_client' && $user->role !== 'client' && $user->role !== 'responsable_services') {
            return redirect()->route('admin.dashboard')->with('error', 'Utilisateur n\'est pas un client ou gestion_client ou un responsable services');
        }

        $user->update(['is_active' => true]);

        Log::info("Admin approuvé Gestion_Client: {$user->email}");

        return redirect()->route('admin.dashboard')->with('success', "Compte {$user->name} activé avec succès");
    }

    /**
     * Désactiver un utilisateur
     */
    public function deactivateUser(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Impossible de désactiver un admin');
        }

        $user->update(['is_active' => false]);

        Log::warning("Admin désactivé utilisateur: {$user->email}");

        return redirect()->back()->with('success', "Utilisateur {$user->name} désactivé avec succès");
    }

    /**
     * Réinitialiser mot de passe utilisateur
     */
    public function resetPassword(User $user)
    {
        $newPassword = \Str::random(12);
        $user->update(['password' => bcrypt($newPassword)]);

        Log::warning("Admin réinitialié mot de passe: {$user->email}");

        return redirect()->back()->with('success', "Mot de passe réinitialisé pour {$user->name}. Nouveau mot de passe: {$newPassword}");
    }

    /**
     * Soft delete une panne
     */
    public function softDeleteBreakdown(Breakdown $breakdown)
    {
        $breakdown->delete();

        Log::info("Admin soft-delete panne: #{$breakdown->id}");

        return redirect()->back()->with('success', "Panne #{$breakdown->id} archivée avec succès");
    }

    /**
     * Restaurer une panne
     */
    public function restoreBreakdown(int $id)
    {
        $breakdown = Breakdown::withTrashed()->find($id);

        if (!$breakdown) {
            return redirect()->back()->with('error', 'Panne non trouvée');
        }

        $breakdown->restore();

        Log::info("Admin restauré panne: #{$breakdown->id}");

        return redirect()->back()->with('success', "Panne #{$breakdown->id} restaurée avec succès");
    }

    /**
     * Hard delete une panne (suppression définitive)
     */
    public function hardDeleteBreakdown(Breakdown $breakdown)
    {
        $id = $breakdown->id;
        $breakdown->forceDelete();

        Log::critical("Admin hard-delete panne: #{$id}");

        return redirect()->back()->with('success', "Panne #{$id} définitivement supprimée");
    }

    /**
     * Voir tous les utilisateurs
     */
    public function listUsers()
    {
        $users = User::with(['breakdowns', 'vehicules'])->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Voir les techniciens
     */
    public function listTechniciens()
    {
        $techniciens = Technicien::withCount('reparations')->paginate(15); 
        return view('admin.techniciens', compact('techniciens'));
    }
    /**
     * Voir l'audit log
     */
    public function auditLog()
    {
        // Les logs sont stockés dans storage/logs/
        // On lit le dernier fichier de log
        $logPath = storage_path('logs/laravel.log');
        $logs = file_exists($logPath) ? file_get_contents($logPath) : 'Aucun log';

        return view('admin.audit', compact('logs'));
    }

    /**
     * Ajout de bouton crée user
     */
    public function UserCreate()
    {
        return view('create.user', compact('users'));
    }
}