@extends('layouts.app')

@section('title', 'Détails du Technicien - Mekano Garage')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>Détails du Technicien</h2>
        </div>

        <div class="card-body">
            <div class="detail-item">
                <div class="detail-label">ID</div>
                <div class="detail-value">{{ $technicien->id }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Nom</div>
                <div class="detail-value">{{ $technicien->nom }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Prénom</div>
                <div class="detail-value">{{ $technicien->prenom }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Spécialité</div>
                <div class="detail-value">{{ $technicien->specialite ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="card-footer text-center">
            <div class="btn-group">
                <a href="{{ route('techniciens.edit', $technicien->id) }}" class="btn btn-warning btn-lg">Éditer</a>
                <form action="{{ route('techniciens.destroy', $technicien->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                </form>
                <a href="{{ route('techniciens.index') }}" class="btn btn-secondary btn-lg">Retour</a>
            </div>
        </div>
    </div>
</div>
@endsection