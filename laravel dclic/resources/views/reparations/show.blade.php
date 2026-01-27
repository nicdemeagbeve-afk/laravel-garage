@extends('layouts.app')

@section('title', 'Véhicules - Mekano Garage')

@section('extra_css')
    <h1>Détails de la Réparation #{{ $reparation->id }}</h1>
    
    <p><strong>Véhicule:</strong> {{ $reparation->vehicule->immatriculation ?? 'N/A' }} - {{ $reparation->vehicule->marque ?? '' }} {{ $reparation->vehicule->modele ?? '' }}</p>
    <p><strong>Technicien:</strong> {{ $reparation->technicien->prenom ?? 'Non assigné' }} {{ $reparation->technicien->nom ?? '' }}</p>
    <p><strong>Date:</strong> {{ $reparation->date }}</p>
    <p><strong>Durée Main d'Œuvre:</strong> {{ $reparation->duree_main_oeuvre }} minutes</p>
    <p><strong>Objet de la Réparation:</strong></p>
    <p>{{ $reparation->objet_reparation }}</p>
    
    <p><strong>Créée:</strong> {{ $reparation->created_at }}</p>
    <p><strong>Modifiée:</strong> {{ $reparation->updated_at }}</p>
    
    <br>
    <a href="{{ route('reparations.edit', $reparation->id) }}">Éditer</a> |
    <a href="{{ route('reparations.index') }}">Retour à la liste</a> |
    <form action="{{ route('reparations.destroy', $reparation->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
@endsection