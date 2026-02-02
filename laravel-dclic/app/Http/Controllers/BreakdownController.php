<?php

namespace App\Http\Controllers;

use App\Models\Breakdown;
use App\Models\Vehicule;
use App\Models\Technicien;
use App\Http\Requests\StoreBreakdownRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BreakdownController extends Controller
{
    /**
     * Constructeur : Appliquer le middleware d'authentification
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Restreindre la création de pannes aux clients uniquement
        $this->middleware('role:client')->only(['create', 'store']);
    }

    /**
     * Afficher la liste des déclarations de pannes de l'utilisateur authentifié
     * 
     * GET /breakdowns
     *
     * @return View
     */
    public function index(): View
    {
        // Récupérer les pannes de l'utilisateur authentifié avec leurs relations
        $breakdowns = Breakdown::where('user_id', auth()->id())
            ->with(['vehicule', 'technicien'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('breakdowns.index', compact('breakdowns'));
    }

    /**
     * Afficher le formulaire pour créer une nouvelle déclaration de panne
     * 
     * GET /breakdowns/create
     *
     * @return View
     */
    public function create(): View
    {
        // Récupérer uniquement les véhicules de l'utilisateur authentifié
        $vehicules = Vehicule::where('user_id', auth()->id())
            ->get();

        // Récupérer tous les techniciens pour l'affichage des cartes
        $techniciens = Technicien::all();

        return view('breakdowns.create', compact('vehicules', 'techniciens'));
    }

    /**
     * Stocker une nouvelle déclaration de panne en base de données
     * 
     * POST /breakdowns
     *
     * @param StoreBreakdownRequest $request
     * @return RedirectResponse
     */
    public function store(StoreBreakdownRequest $request): RedirectResponse
    {
        // Validation des données
        $validated = $request->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'description' => 'required|string|max:1000',
            'phone' => 'required|string|regex:/^[\d\s\-\+\(\)]+$/|min:8|max:20',
            'location' => 'required|string|max:255',
            'needs_technician' => 'boolean',
            'technicien_id' => 'nullable|exists:techniciens,id',
            'onsite_assistance' => 'boolean'
        ]);

        // Vérifier que si needs_technician est true, un technicien_id est fourni
        if ($request->boolean('needs_technician') && empty($request->technicien_id)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Veuillez sélectionner un technicien si vous en demandez un.');
        }

        // Ajouter l'ID de l'utilisateur authentifié
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';
        $validated['is_approved'] = false;

        // Créer la déclaration de panne
        $breakdown = Breakdown::create($validated);

        // Rediriger vers le dashboard approprié (HomeController redirige par rôle)
        return redirect()
            ->route('home')
            ->with('success', 'Votre déclaration de panne a été enregistrée avec succès. Un technicien vous contactera bientôt au ' . $validated['phone']);
    }

    /**
     * Afficher les détails d'une déclaration de panne
     * 
     * GET /breakdowns/{breakdown}
     *
     * @param Breakdown $breakdown
     * @return View
     */
    public function show(Breakdown $breakdown): View
    {
        // Vérifier que l'utilisateur authentifié est le propriétaire de la panne
        $this->authorize('view', $breakdown);

        // Charger les relations
        $breakdown->load(['vehicule', 'technicien', 'user']);

        return view('breakdowns.show', compact('breakdown'));
    }

    /**
     * Afficher le formulaire pour éditer une déclaration de panne
     * 
     * GET /breakdowns/{breakdown}/edit
     *
     * @param Breakdown $breakdown
     * @return View
     */
    public function edit(Breakdown $breakdown): View
    {
        // Vérifier que l'utilisateur authentifié est le propriétaire
        $this->authorize('update', $breakdown);

        // Récupérer les véhicules de l'utilisateur
        $vehicules = Vehicule::where('user_id', auth()->id())->get();

        // Récupérer tous les techniciens
        $techniciens = Technicien::all();

        return view('breakdowns.edit', compact('breakdown', 'vehicules', 'techniciens'));
    }

    /**
     * Mettre à jour une déclaration de panne
     * 
     * PUT/PATCH /breakdowns/{breakdown}
     *
     * @param StoreBreakdownRequest $request
     * @param Breakdown $breakdown
     * @return RedirectResponse
     */
    public function update(StoreBreakdownRequest $request, Breakdown $breakdown): RedirectResponse
    {
        // Vérifier que l'utilisateur authentifié est le propriétaire
        $this->authorize('update', $breakdown);

        // Vérifier que le statut n'est pas "resolved" ou "cancelled"
        if (in_array($breakdown->status, ['resolved', 'cancelled'])) {
            return redirect()
                ->back()
                ->with('error', 'Vous ne pouvez pas modifier une panne résolue ou annulée.');
        }

        // Récupérer les données validées
        $validated = $request->validated();

        // Mettre à jour la panne
        $breakdown->update($validated);

        return redirect()
            ->route('breakdowns.show', $breakdown->id)
            ->with('success', 'Votre déclaration de panne a été mise à jour avec succès.');
    }

    /**
     * Supprimer (annuler) une déclaration de panne
     * 
     * DELETE /breakdowns/{breakdown}
     *
     * @param Breakdown $breakdown
     * @return RedirectResponse
     */
    public function destroy(Breakdown $breakdown): RedirectResponse
    {
        // Vérifier que l'utilisateur authentifié est le propriétaire
        $this->authorize('delete', $breakdown);

        // Vérifier que la panne n'est pas encore en cours de traitement
        if ($breakdown->status === 'in_progress') {
            return redirect()
                ->back()
                ->with('error', 'Vous ne pouvez pas supprimer une panne en cours de traitement.');
        }

        // Marquer comme annulée au lieu de la supprimer
        $breakdown->update(['status' => 'cancelled']);

        return redirect()
            ->route('breakdowns.index')
            ->with('success', 'Votre déclaration de panne a été annulée.');
    }

    /**
     * Récupérer les véhicules de l'utilisateur en AJAX (pour le frontend)
     * 
     * GET /breakdowns/api/my-vehicules
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyVehicules()
    {
        $vehicules = Vehicule::where('user_id', auth()->id())
            ->select('id', 'immatriculation', 'marque', 'modele')
            ->get();

        return response()->json($vehicules);
    }

    /**
     * Récupérer les techniciens disponibles en AJAX
     * 
     * GET /breakdowns/api/techniciens
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTechniciens()
    {
        $techniciens = Technicien::select('id', 'nom', 'prenom', 'specialite', 'photo_url', 'age')
            ->get()
            ->map(function ($tech) {
                return [
                    'id' => $tech->id,
                    'nom_complet' => $tech->nom_complet,
                    'specialite' => $tech->specialite,
                    'photo_url' => $tech->photo_url,
                    'age' => $tech->age
                ];
            });

        return response()->json($techniciens);
    }
}
