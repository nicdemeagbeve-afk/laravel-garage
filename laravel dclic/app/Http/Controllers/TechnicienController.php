<?php

namespace App\Http\Controllers;

use App\Models\Technicien;
use Illuminate\Http\Request;

class TechnicienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $techniciens = Technicien::all();
        return view('techniciens.index', compact('techniciens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('techniciens.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'specialite' => 'nullable|string',
        ]);
        
        $technicien = new Technicien();
        $technicien->nom = $validated['nom'];
        $technicien->prenom = $validated['prenom'];
        $technicien->specialite = $validated['specialite'] ?? null;
        $technicien->save();
        return redirect()->route('techniciens.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Technicien $technicien)
    {
        //
        return view('techniciens.show', compact('technicien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technicien $technicien)
    {
        //
        return view('techniciens.edit', compact('technicien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technicien $technicien)
    {
        //
        $validated = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'specialite' => 'nullable|string',
        ]);
        
        $technicien->nom = $validated['nom'];
        $technicien->prenom = $validated['prenom'];
        $technicien->specialite = $validated['specialite'] ?? null;
        $technicien->save();
        return redirect()->route('techniciens.index');  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technicien $technicien)
    {
        //
        $technicien->delete();
        return redirect()->route('techniciens.index');
    }
}
