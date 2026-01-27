@extends('layouts.app')

@section('title', 'Techniciens - Mekano Garage')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-20">
            <h1>Liste des Techniciens</h1>
            <a href="{{ route('techniciens.create') }}" class="btn btn-success btn-lg">+ Ajouter un Technicien</a>
        </div>
    
        @if($techniciens->isEmpty())
            <div class="alert alert-info">
                <strong>Aucun technicien enregistré.</strong> <a href="{{ route('techniciens.create') }}">Créer un nouveau technicien</a>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Spécialité</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($techniciens as $technicien)
                        <tr>
                            <td><strong>#{{ $technicien->id }}</strong></td>
                            <td>{{ $technicien->nom }}</td>
                            <td>{{ $technicien->prenom }}</td>
                            <td>{{ $technicien->specialite ?? 'N/A' }}</td>
                            <td>{{ $technicien->telephone ?? 'N/A' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('techniciens.show', $technicien) }}" class="btn btn-info btn-sm">Voir</a>
                                    <a href="{{ route('techniciens.edit', $technicien) }}" class="btn btn-warning btn-sm">Modifier</a>
                                    <form action="{{ route('techniciens.destroy', $technicien->id) }}" method="POST" style="display:inline;">
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