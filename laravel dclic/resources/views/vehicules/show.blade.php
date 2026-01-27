@extends('layouts.app')

@section('title', 'Détails du Véhicule - Mekano Garage')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>Détails du Véhicule</h2>
        </div>

        <div class="card-body">
            <div class="row gap-20">
                @if($vehicule->image)
                    <div class="col-6">
                        <img src="{{ asset('storage/' . $vehicule->image) }}" alt="{{ $vehicule->marque }}" class="img-thumbnail" style="width: 100%;">
                    </div>
                @endif
                <div class="col-6 full">
                    <div class="detail-item">
                        <div class="detail-label">Immatriculation</div>
                        <div class="detail-value">{{ $vehicule->immatriculation }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Marque</div>
                        <div class="detail-value">{{ $vehicule->marque }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Modèle</div>
                        <div class="detail-value">{{ $vehicule->modele }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Couleur</div>
                        <div class="detail-value">{{ $vehicule->couleur ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Année</div>
                        <div class="detail-value">{{ $vehicule->annee ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Kilométrage</div>
                        <div class="detail-value">{{ $vehicule->kilometrage ?? 'N/A' }} km</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Carrosserie</div>
                        <div class="detail-value">{{ $vehicule->carrosserie ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Énergie</div>
                        <div class="detail-value">{{ $vehicule->energie ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Boîte de Vitesses</div>
                        <div class="detail-value">{{ $vehicule->boite ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-center">
            <div class="btn-group">
                <a href="{{ route('vehicules.edit', $vehicule->id) }}" class="btn btn-warning btn-lg">Éditer</a>
                <form action="{{ route('vehicules.destroy', $vehicule->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce véhicule?')">Supprimer</button>
                </form>
                <a href="{{ route('vehicules.index') }}" class="btn btn-secondary btn-lg">Retour</a>
            </div>
        </div>
    </div>
</div>
@endsection
        
        <br>
        <a href="{{ route('vehicules.edit', $vehicule->id) }}"><button>Éditer</button></a>
        <a href="{{ route('vehicules.index') }}"><button>Retour à la liste</button></a>
        <form action="{{ route('vehicules.destroy', $vehicule->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
        </form>
    </div>
</body>
</html>
        </div>
        <div class="info">
            <span class="label">Couleur:</span> {{ $vehicule->couleur }}
        </div>
        <div class="info">
            <span class="label">Année:</span> {{ $vehicule->annee }}
        </div>
        <div class="info">
            <span class="label">Kilométrage:</span> {{ $vehicule->kilometrage }}
        </div>
        <div class="info">
            <span class="label">Carrosserie:</span> {{ $vehicule->carrosserie }}
        </div>
        <div class="info">
            <span class="label">Énergie:</span> {{ $vehicule->energie }}
        </div>
        <div class="info">
            <span class="label">Boîte:</span> {{ $vehicule->boite }}
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('vehicules.edit', $vehicule->id) }}">Éditer</a>
            <a href="#" onclick="if(confirm('Êtes-vous sûr?')) { document.getElementById('delete-form').submit(); }">Supprimer</a>
            <a href="{{ route('vehicules.index') }}">Retour à la liste</a>
        </div>

        <form id="delete-form" action="{{ route('vehicules.destroy', $vehicule->id) }}" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</body>
@endsection
