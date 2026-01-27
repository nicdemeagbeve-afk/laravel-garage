@extends('layouts.app')

@section('title', 'Véhicules - Mekano Garage')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-20">
            <h1>Liste des Véhicules</h1>
            <a href="{{ route('vehicules.create') }}" class="btn btn-success btn-lg">+ Ajouter un véhicule</a>
        </div>
    
        @if($vehicules->isEmpty())
            <div class="alert alert-info">
                <strong>Aucun véhicule enregistré.</strong> <a href="{{ route('vehicules.create') }}">Créer un nouveau véhicule</a>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Immatriculation</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Couleur</th>
                        <th>Année</th>
                        <th>Kilométrage</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicules as $vehicule)
                        <tr>
                            <td><strong>#{{ $vehicule->id }}</strong></td>
                            <td>{{ $vehicule->immatriculation }}</td>
                            <td>{{ $vehicule->marque }}</td>
                            <td>{{ $vehicule->modele }}</td>
                            <td>{{ $vehicule->couleur ?? 'N/A' }}</td>
                            <td>{{ $vehicule->annee ?? 'N/A' }}</td>
                            <td>{{ $vehicule->kilometrage ?? 'N/A' }} km</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('vehicules.show', $vehicule->id) }}" class="btn btn-info btn-sm">Voir</a>
                                    <a href="{{ route('vehicules.edit', $vehicule->id) }}" class="btn btn-warning btn-sm">Éditer</a>
                                    <form action="{{ route('vehicules.destroy', $vehicule->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
