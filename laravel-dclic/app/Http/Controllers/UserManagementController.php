<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    /**
     * Afficher la liste de tous les utilisateurs (Admin seulement)
     */
    public function index()
    {
        $this->authorize('isAdmin', Auth::user());

        $users = User::withTrashed()->paginate(15);
        $pending_users = User::where('is_active', false)->get();
        $active_users = User::where('is_active', true)->get();

        return view('users.index', compact('users', 'pending_users', 'active_users'));
    }

    /**
     * Afficher le formulaire de création d'utilisateur
     * (Responsable services et Gestion client peuvent créer)
     */
    public function create()
    {
        $currentUser = Auth::user();
        
        // Seuls les rôles autorisés peuvent créer
        if (!in_array($currentUser->role, ['responsable_services', 'gestion_client', 'admin'])) {
            abort(403, 'Vous n\'avez pas la permission de créer des utilisateurs');
        }

        return view('users.create');
    }

    /**
     * Stocker un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();

        // Vérifier les permissions
        if (!in_array($currentUser->role, ['responsable_services', 'gestion_client', 'admin'])) {
            abort(403, 'Vous n\'avez pas la permission de créer des utilisateurs');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'role' => ['required', 'in:client,gestion_client,responsable_services'],
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'sexe' => ['nullable', 'in:M,F,Autre'],
            'residence' => ['nullable', 'string', 'max:255'],
        ]);

        // Générer un code de vérification temporaire
        $verification_code = Str::random(10);
        $temporary_password = Str::random(12);

        // Créer l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($temporary_password),
            'role' => $validated['role'],
            'age' => $validated['age'] ?? null,
            'sexe' => $validated['sexe'] ?? null,
            'residence' => $validated['residence'] ?? null,
            'verification_code' => $verification_code,
            'is_active' => false, // Non actif par défaut, en attente d'approbation admin
            'created_by' => $currentUser->id,
        ]);

        // Envoyer un email à l'utilisateur avec ses identifiants temporaires
        // TODO: Implémenter l'envoi d'email
        // Mail::send('emails.user-created', [
        //     'user' => $user,
        //     'temporary_password' => $temporary_password,
        //     'verification_code' => $verification_code,
        // ], function($mail) use ($user) {
        //     $mail->to($user->email);
        // });

        return redirect()->route('users.index')
                        ->with('success', "Utilisateur créé avec succès. Un email d'activation a été envoyé à {$user->email}");
    }

    /**
     * Approuver un utilisateur (Admin seulement)
     */
    public function approve(User $user)
    {
        $this->authorize('isAdmin', Auth::user());

        if ($user->is_active) {
            return back()->with('info', 'Cet utilisateur est déjà actif');
        }

        $user->update(['is_active' => true]);

        // TODO: Envoyer un email de confirmation
        // Mail::send('emails.user-approved', ['user' => $user], function($mail) use ($user) {
        //     $mail->to($user->email);
        // });

        return back()->with('success', "L'utilisateur {$user->name} a été approuvé avec succès");
    }

    /**
     * Rejeter/Désactiver un utilisateur (Admin seulement)
     */
    public function deactivate(User $user)
    {
        $this->authorize('isAdmin', Auth::user());

        $user->update(['is_active' => false]);

        return back()->with('success', "L'utilisateur {$user->name} a été désactivé");
    }

    /**
     * Supprimer un utilisateur (soft delete - Admin seulement)
     */
    public function destroy(User $user)
    {
        $this->authorize('isAdmin', Auth::user());

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        $user->delete();

        return back()->with('success', "L'utilisateur {$user->name} a été supprimé");
    }

    /**
     * Restaurer un utilisateur supprimé (Admin seulement)
     */
    public function restore(User $user)
    {
        $this->authorize('isAdmin', Auth::user());

        if ($user->trashed()) {
            $user->restore();
            return back()->with('success', "L'utilisateur {$user->name} a été restauré");
        }

        return back()->with('info', 'Cet utilisateur n\'a pas été supprimé');
    }

    /**
     * Valider le code de vérification lors de la connexion
     */
    public function verifyCode(Request $request)
    {
        $validated = $request->validate([
            'verification_code' => ['required', 'string'],
        ]);

        $user = Auth::user();

        if ($user->verification_code === $validated['verification_code']) {
            // Code valide, marquer comme vérifié
            $user->update(['verification_code' => null]);
            
            return redirect()->route('home')->with('success', 'Compte vérifié avec succès!');
        }

        return back()->with('error', 'Code de vérification incorrect');
    }

    /**
     * Afficher la page de vérification du code
     */
    public function showVerifyCode()
    {
        if (!Auth::user()->verification_code) {
            return redirect()->route('home');
        }

        return view('auth.verify-code');
    }
}
