<?php

namespace App\Http\Controllers;

use App\Models\Reparation;
use Illuminate\Http\Request;

class ReparationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reparations = Reparation::all();
        return view('reparations.index', compact('reparations', ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $vehicules = \App\Models\Vehicule::all();
        $techniciens = \App\Models\Technicien::all();
        return view('reparations.create', compact('vehicules', 'techniciens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'vehicule_id' => 'required|integer|exists:vehicules,id',
            'technicien_id' => 'nullable|integer|exists:techniciens,id',
            'date' => 'required|date',
            'duree_main_oeuvre' => 'nullable|integer|min:0',
            'objet_reparation' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $reparation = new Reparation();
        $reparation->vehicule_id = $validated['vehicule_id'];
        $reparation->technicien_id = $validated['technicien_id'] ?? null;
        $reparation->date = $validated['date'];
        $reparation->duree_main_oeuvre = $validated['duree_main_oeuvre'] ?? null;
        $reparation->objet_reparation = $validated['objet_reparation'];
        
        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reparations', 'public');
            $reparation->image = $path;
        }
        
        $reparation->save();
        return redirect()->route('home')->with('success', 'Réparation créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reparation $reparation)
    {
        //
        return view('reparations.show', compact('reparation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reparation $reparation)
    {
        //
        $vehicules = \App\Models\Vehicule::all();
        $techniciens = \App\Models\Technicien::all();
        return view('reparations.edit', compact('reparation', 'vehicules', 'techniciens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reparation $reparation)
    {
        //
        $validated = $request->validate([
            'vehicule_id' => 'required|integer|exists:vehicules,id',
            'technicien_id' => 'nullable|integer|exists:techniciens,id',
            'date' => 'required|date',
            'duree_main_oeuvre' => 'nullable|integer|min:0',
            'objet_reparation' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $reparation->vehicule_id = $validated['vehicule_id'];
        $reparation->technicien_id = $validated['technicien_id'] ?? null;
        $reparation->date = $validated['date'];
        $reparation->duree_main_oeuvre = $validated['duree_main_oeuvre'] ?? null;
        $reparation->objet_reparation = $validated['objet_reparation'];
        
        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($reparation->image && \Storage::disk('public')->exists($reparation->image)) {
                \Storage::disk('public')->delete($reparation->image);
            }
            $path = $request->file('image')->store('reparations', 'public');
            $reparation->image = $path;
        }
        
        $reparation->save();
        return redirect()->route('reparations.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reparation $reparation)
    {
        //
        $reparation->delete();
        return redirect()->route('reparations.index');
    }
}
