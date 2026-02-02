<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use Illuminate\Http\Request;

class VehiculeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $vehicules = Vehicule::all();
        return view('vehicules.index', compact('vehicules'));

        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('vehicules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'immatriculation' => 'required|string|unique:vehicules',
            'marque' => 'required|string',
            'modele' => 'required|string',
            'couleur' => 'nullable|string',
            'annee' => 'nullable|integer|min:1900|max:2099',
            'kilometrage' => 'nullable|integer|min:0',
            'carrosserie' => 'nullable|string',
            'energie' => 'nullable|string',
            'boite' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $vehicule = new Vehicule();
        $vehicule->immatriculation = $validated['immatriculation'];
        $vehicule->marque = $validated['marque'];
        $vehicule->modele = $validated['modele'];
        $vehicule->couleur = $validated['couleur'] ?? null;
        $vehicule->annee = $validated['annee'] ?? null;
        $vehicule->kilometrage = $validated['kilometrage'] ?? null;
        $vehicule->carrosserie = $validated['carrosserie'] ?? null;
        $vehicule->energie = $validated['energie'] ?? null;
        $vehicule->boite = $validated['boite'] ?? null;
        
        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('vehicules', 'public');
            $vehicule->image = $path;
        }
        
        $vehicule->save();
        return redirect()->route('home')->with('success', 'Véhicule créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicule $vehicule)
    {
        //
        return view('vehicules.show', compact('vehicule'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicule $vehicule)
    {
        //
        return view('vehicules.edit', compact('vehicule'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicule $vehicule)
    {
        //
        $validated = $request->validate([
            'immatriculation' => 'required|string|unique:vehicules,immatriculation,' . $vehicule->id,
            'marque' => 'required|string',
            'modele' => 'required|string',
            'couleur' => 'nullable|string',
            'annee' => 'nullable|integer|min:1900|max:2099',
            'kilometrage' => 'nullable|integer|min:0',
            'carrosserie' => 'nullable|string',
            'energie' => 'nullable|string',
            'boite' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $vehicule->immatriculation = $validated['immatriculation'];
        $vehicule->marque = $validated['marque'];
        $vehicule->modele = $validated['modele'];
        $vehicule->couleur = $validated['couleur'] ?? null;
        $vehicule->annee = $validated['annee'] ?? null;
        $vehicule->kilometrage = $validated['kilometrage'] ?? null;
        $vehicule->carrosserie = $validated['carrosserie'] ?? null;
        $vehicule->energie = $validated['energie'] ?? null;
        $vehicule->boite = $validated['boite'] ?? null;
        
        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($vehicule->image && \Storage::disk('public')->exists($vehicule->image)) {
                \Storage::disk('public')->delete($vehicule->image);
            }
            $path = $request->file('image')->store('vehicules', 'public');
            $vehicule->image = $path;
        }
        
        $vehicule->save();
        return redirect()->route('vehicules.show', compact('vehicule'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicule $vehicule)
    {
        //
        $vehicule->delete();
        return redirect()->route('vehicules.index');


    }
}
