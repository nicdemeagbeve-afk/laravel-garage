@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- En-tête du dashboard --}}
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-tachometer-alt"></i> Mon Tableau de Bord Client
            </h1>
            <p class="text-muted mt-2">Bienvenue {{ Auth::user()->name }}, voici vos informations</p>
        </div>
    </div>

    {{-- Alertes de session --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Cartes de statistiques --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Mes Véhicules</p>
                            <h3 class="mb-0">{{ $stats['mes_vehicules'] }}</h3>
                        </div>
                        <div class="text-primary" style="font-size: 2rem;">
                            <i class="fas fa-car"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Pannes Signalées</p>
                            <h3 class="mb-0">{{ $stats['pannes_totales'] }}</h3>
                        </div>
                        <div class="text-warning" style="font-size: 2rem;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">En Cours</p>
                            <h3 class="mb-0">{{ $stats['pannes_en_cours'] }}</h3>
                        </div>
                        <div class="text-info" style="font-size: 2rem;">
                            <i class="fas fa-wrench"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Résolues</p>
                            <h3 class="mb-0">{{ $stats['pannes_resolues'] }}</h3>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Contenu principal en 2 colonnes --}}
    <div class="row g-3">
        {{-- Colonne gauche: Mes véhicules et Pannes récentes --}}
        <div class="col-lg-8">
            {{-- Section: Mes Véhicules --}}
            <div class="card mb-3">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-car-alt"></i> Mes Véhicules
                    </h5>
                    <a href="{{ route('vehicules.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Ajouter un Véhicule
                    </a>
                </div>
                <div class="card-body">
                    @if ($mesVehicules->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted mb-3">
                                <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                            </p>
                            <p class="mb-0">Vous n'avez pas encore enregistré de véhicule</p>
                            <a href="{{ route('vehicules.create') }}" class="btn btn-sm btn-primary mt-3">
                                Enregistrer mon premier véhicule
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Marque</th>
                                        <th>Modèle</th>
                                        <th>Immatriculation</th>
                                        <th>Année</th>
                                        <th>Kilométrage</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mesVehicules as $vehicule)
                                        <tr>
                                            <td>
                                                <strong>{{ $vehicule->marque }}</strong>
                                            </td>
                                            <td>{{ $vehicule->modele }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $vehicule->immatriculation }}</span>
                                            </td>
                                            <td>
                                                <small>{{ $vehicule->annee ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <small>{{ number_format($vehicule->kilometrage, 0, ',', ' ') }} km</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('vehicules.show', $vehicule) }}" class="btn btn-sm btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('vehicules.edit', $vehicule) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('vehicules.destroy', $vehicule) }}" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Section: Mes Pannes Récentes --}}
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Mes Pannes Signalées ({{ $mesPannes->count() }})
                    </h5>
                    <a href="{{ route('breakdowns.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Signaler une Panne
                    </a>
                </div>
                <div class="card-body">
                    @if ($mesPannes->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted mb-3">
                                <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                            </p>
                            <p class="mb-0">Vous n'avez pas encore signalé de panne</p>
                            <a href="{{ route('breakdowns.create') }}" class="btn btn-sm btn-primary mt-3">
                                Signaler une panne
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Véhicule</th>
                                        <th>Description</th>
                                        <th>Statut</th>
                                        <th>Technicien</th>
                                        <th>Date</th>
                                        <th>Durée</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mesPannes as $panne)
                                        <tr>
                                            <td>
                                                <a href="{{ route('vehicules.show', $panne->vehicule) }}" class="text-decoration-none">
                                                    <strong>{{ $panne->vehicule?->marque }} {{ $panne->vehicule?->modele }}</strong>
                                                    <br>
                                                    <small class="badge bg-secondary">{{ $panne->vehicule?->immatriculation }}</small>
                                                </a>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($panne->description, 30) }}</small>
                                            </td>
                                            <td>
                                                @switch($panne->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning text-dark">En Attente</span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="badge bg-info">En Cours</span>
                                                        @break
                                                    @case('resolved')
                                                        <span class="badge bg-success">Résolue</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $panne->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if ($panne->technicien)
                                                    <a href="{{ route('techniciens.show', $panne->technicien) }}" class="text-decoration-none">
                                                        {{ $panne->technicien?->nom_complet ?? 'N/A' }}
                                                    </a>
                                                @else
                                                    <small class="text-muted">Non assigné</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $panne->created_at->format('d/m/Y') }}</small>
                                                <br>
                                                <small class="text-muted">{{ $panne->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <small>
                                                    @if ($panne->date_prevue)
                                                        {{ \Carbon\Carbon::parse($panne->date_prevue)->diff($panne->created_at)->days }} j
                                                    @else
                                                        N/A
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <a href="{{ route('breakdowns.show', $panne) }}" class="btn btn-sm btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if ($panne->status === 'pending')
                                                    <a href="{{ route('breakdowns.edit', $panne) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('breakdowns.destroy', $panne) }}" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Annuler" onclick="return confirm('Êtes-vous sûr?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Colonne droite: Actions rapides et informations --}}
        <div class="col-lg-4">
            {{-- Actions Rapides --}}
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-lightning-bolt"></i> Actions Rapides
                    </h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('vehicules.create') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-car-plus text-primary me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Ajouter un Véhicule</h6>
                                <small class="text-muted">Enregistrer un nouveau véhicule</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('breakdowns.create') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle text-danger me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Signaler une Panne</h6>
                                <small class="text-muted">Déclarer un problème</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-edit text-info me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Modifier Mon Profil</h6>
                                <small class="text-muted">Mettre à jour mes informations</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Informations du Profil --}}
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle"></i> Mon Profil
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Nom</label>
                        <p class="mb-0 fw-bold">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="mb-0 fw-bold">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Âge</label>
                        <p class="mb-0 fw-bold">{{ Auth::user()->age ?? 'Non spécifié' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Sexe</label>
                        <p class="mb-0 fw-bold">
                            @switch(Auth::user()->sexe)
                                @case('M')
                                    Masculin
                                    @break
                                @case('F')
                                    Féminin
                                    @break
                                @case('Autre')
                                    Autre
                                    @break
                                @default
                                    Non spécifié
                            @endswitch
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Résidence</label>
                        <p class="mb-0 fw-bold">{{ Auth::user()->residence ?? 'Non spécifiée' }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-pencil-alt"></i> Modifier le Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 0.25rem solid #007bff !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #ffc107 !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #17a2b8 !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #28a745 !important;
    }
</style>
@endsection
