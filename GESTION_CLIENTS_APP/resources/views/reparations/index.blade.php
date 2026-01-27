@extends('layouts.app')

@section('title', 'Réparations - Mekano Garage')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-20">
        <h1>Liste des Réparations</h1>
        <a href="{{ route('reparations.create') }}" class="btn btn-success btn-lg">+ Nouvelle Réparation</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($reparations->isEmpty())
        <div class="alert alert-info">
            <strong>Aucune réparation enregistrée.</strong> <a href="{{ route('reparations.create') }}">Créer une nouvelle réparation</a>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Véhicule</th>
                    <th>Objet</th>
                    <th>Date</th>
                    <th>Technicien</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reparations as $reparation)
                    <tr>
                        <td><strong>#{{ $reparation->id }}</strong></td>
                        <td>{{ $reparation->vehicule->marque ?? 'N/A' }} {{ $reparation->vehicule->modele ?? '' }}</td>
                        <td>{{ Str::limit($reparation->objet_reparation, 40) }}</td>
                        <td>{{ $reparation->date ?? 'N/A' }}</td>
                        <td>{{ $reparation->technicien->nom ?? 'Non assigné' }}</td>
                        <td>
                            <span class="badge @if($reparation->statut == 'Complétée') badge-success @else badge-warning @endif">
                                {{ $reparation->statut ?? 'En cours' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('reparations.show', $reparation) }}" class="btn btn-info btn-sm">Voir</a>
                                <a href="{{ route('reparations.edit', $reparation) }}" class="btn btn-warning btn-sm">Modifier</a>
                                <form action="{{ route('reparations.destroy', $reparation) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Aucune réparation trouvée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>
@endsection
