<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Afficher le formulaire de modification du profil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Mettre à jour le profil de l'utilisateur
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'sexe' => ['nullable', 'in:M,F,Autre'],
            'residence' => ['nullable', 'string', 'max:255'],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        // Vérifier le mot de passe actuel si on en change un
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
            }
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Supprimer le mot de passe actuel de la validation si pas utilisé
        unset($validated['current_password']);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('status', 'Profil mis à jour avec succès!');
    }

    /**
     * Afficher le profil d'un utilisateur (pour admin/gestion_client/responsable_services)
     */
    public function show(User $user)
    {
        // Vérifier les permissions
        $currentUser = Auth::user();
        
        // Admin peut voir tous les profils
        if (!$currentUser->isAdmin() && $currentUser->id !== $user->id) {
            abort(403, 'Vous n\'avez pas la permission de voir ce profil');
        }

        return view('profile.show', compact('user'));
    }
}
