<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation avec gestion d'erreurs pour les uploads
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:5000',
                'price' => 'required|numeric|min:0|max:999999.99',
                'images' => 'nullable|file|max:7168|mimes:jpg,jpeg,png,webp',
            ], [
                'images.max' => 'Le fichier ne doit pas dépasser 7MB.',
                'images.mimes' => 'Le fichier doit être en format JPEG, PNG ou WebP.',
                'images.file' => 'Le fichier est invalide.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $service = new Service();
        $service->name = $validated['name'];
        $service->description = $validated['description'] ?? null;
        $service->price = $validated['price'];

        if ($request->hasFile('images') && $request->file('images')->isValid()) {
            try {
                $file = $request->file('images');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $imagePath = $file->storeAs('services', $filename, 'public');
                $service->images = $imagePath;
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur lors du téléchargement du fichier: ' . $e->getMessage())->withInput();
            }
        }

        $service->save();

        return redirect()->route('home')->with('success', 'Service créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        // Validation avec gestion d'erreurs pour les uploads
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:5000',
                'price' => 'required|numeric|min:0|max:999999.99',
                'images' => 'nullable|file|max:7168|mimes:jpg,jpeg,png,webp',
            ], [
                'images.max' => 'Le fichier ne doit pas dépasser 7MB.',
                'images.mimes' => 'Le fichier doit être en format JPEG, PNG ou WebP.',
                'images.file' => 'Le fichier est invalide.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $service->name = $validated['name'];
        $service->description = $validated['description'] ?? null;
        $service->price = $validated['price'];

        if ($request->hasFile('images') && $request->file('images')->isValid()) {
            try {
                // Supprimer l'ancienne image si elle existe
                if ($service->images && Storage::disk('public')->exists($service->images)) {
                    Storage::disk('public')->delete($service->images);
                }

                $file = $request->file('images');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $imagePath = $file->storeAs('services', $filename, 'public');
                $service->images = $imagePath;
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur lors du téléchargement du fichier: ' . $e->getMessage())->withInput();
            }
        }

        $service->save();

        return redirect()->route('services.index')->with('success', 'Service mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
        $service->delete();
        return redirect()->route('services.index');
    }
}
