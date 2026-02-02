@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- En-tête --}}
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-users-cog"></i> Tableau de Bord Gestionnaire Clients
            </h1>
            <p class="text-muted mt-2">Gestion complète des clients et de leurs véhicules</p>
        </div>
    </div>

    {{-- Alertes --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistiques principales --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">Clients Total</p>
                    <h3 class="mb-2">{{ $stats['clients_total'] }}</h3>
                    <i class="fas fa-users text-primary" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">Actifs</p>
                    <h3 class="mb-2 text-success">{{ $stats['clients_actifs'] }}</h3>
                    <i class="fas fa-user-check text-success" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">En Attente</p>
                    <h3 class="mb-2 text-warning">{{ $stats['clients_en_attente'] }}</h3>
                    <i class="fas fa-user-clock text-warning" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">Véhicules</p>
                    <h3 class="mb-2 text-info">{{ $stats['vehicules_total'] }}</h3>
                    <i class="fas fa-car text-info" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">Pannes</p>
                    <h3 class="mb-2 text-danger">{{ $stats['pannes_totales'] }}</h3>
                    <i class="fas fa-tools text-danger" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">En Attente</p>
                    <h3 class="mb-2">{{ $stats['pannes_en_attente'] }}</h3>
                    <i class="fas fa-hourglass-half" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Résumé des pannes --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">En Attente</h6>
                    <h2 class="text-warning">{{ $stats['pannes_en_attente'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">En Cours</h6>
                    <h2 class="text-info">{{ $stats['pannes_en_cours'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Résolues</h6>
                    <h2 class="text-success">{{ $stats['pannes_resolues'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Contenu principal --}}
    <div class="row g-3">
        {{-- Pannes Récentes --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Dernières Pannes Signalées
                    </h5>
                    <a href="{{ route('breakdowns.index') }}" class="btn btn-sm btn-primary">
                        Voir Tout
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($pannesRecentes->isEmpty())
                        <div class="text-center py-5">
                            <p class="text-muted">Aucune panne signalée</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Client</th>
                                        <th>Véhicule</th>
                                        <th>Statut</th>
                                        <th>Technicien</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pannesRecentes as $panne)
                                        <tr>
                                            <td>
                                                <strong>{{ $panne->user?->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $panne->user?->email }}</small>
                                            </td>
                                            <td>
                                                {{ $panne->vehicule?->brand }} {{ $panne->vehicule?->model }}
                                                <br>
                                                <small class="badge bg-secondary">{{ $panne->vehicule?->immatriculation }}</small>
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
                                                @endswitch
                                            </td>
                                            <td>{{ $panne->technicien?->name ?? '—' }}</td>
                                            <td>{{ $panne->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('breakdowns.show', $panne) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
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

        {{-- Colonne Droite --}}
        <div class="col-lg-4">
            {{-- Actions Rapides --}}
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-lightning-bolt"></i> Actions Rapides
                    </h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('users.create') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-plus text-success me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Créer un Client</h6>
                                <small class="text-muted">Ajouter un nouvel utilisateur</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users text-primary me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Gérer Clients</h6>
                                <small class="text-muted">Voir tous les clients</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('vehicules.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-car-alt text-info me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Véhicules</h6>
                                <small class="text-muted">Liste des véhicules enregistrés</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('breakdowns.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tools text-danger me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Toutes les Pannes</h6>
                                <small class="text-muted">Voir toutes les pannes</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Clients en Attente --}}
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock"></i> Clients En Attente ({{ $clientsEnAttente->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if ($clientsEnAttente->isEmpty())
                        <div class="text-center py-4 p-3">
                            <p class="text-muted small mb-0">Aucun client en attente</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($clientsEnAttente as $client)
                                <a href="{{ route('profile.show', $client) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $client->name }}</h6>
                                            <small class="text-muted">{{ $client->email }}</small>
                                        </div>
                                        <span class="badge bg-warning text-dark ms-2">En Attente</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Autres sections --}}
    <div class="row g-3 mt-3">
        {{-- Clients Récents --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users"></i> Nouveaux Clients
                    </h5>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">Voir Tout</a>
                </div>
                <div class="card-body p-0">
                    @if ($clientsRecents->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted">Aucun client</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($clientsRecents as $client)
                                <a href="{{ route('profile.show', $client) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $client->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $client->email }}</small>
                                        </div>
                                        <small class="text-muted">{{ $client->created_at->diffForHumans() }}</small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Véhicules Récents --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-car"></i> Véhicules Récemment Enregistrés
                    </h5>
                    <a href="{{ route('vehicules.index') }}" class="btn btn-sm btn-primary">Voir Tout</a>
                </div>
                <div class="card-body p-0">
                    @if ($vehiculesRecents->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted">Aucun véhicule</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($vehiculesRecents as $vehicule)
                                <a href="{{ route('vehicules.show', $vehicule) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $vehicule->brand }} {{ $vehicule->model }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $vehicule->user?->name }}</small>
                                        </div>
                                        <span class="badge bg-secondary">{{ $vehicule->immatriculation }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Réparations Récentes --}}
    <div class="row g-3 mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-wrench"></i> Réparations Récentes
                    </h5>
                    <a href="{{ route('reparations.index') }}" class="btn btn-sm btn-primary">Voir Tout</a>
                </div>
                <div class="card-body p-0">
                    @php
                        $reparations = \App\Models\Reparation::with(['vehicule', 'technicien'])->latest()->limit(5)->get();
                    @endphp
                    @if ($reparations->isEmpty())
                        <div class="text-center py-5">
                            <p class="text-muted">Aucune réparation enregistrée</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Véhicule</th>
                                        <th>Objet Réparation</th>
                                        <th>Technicien</th>
                                        <th>Durée MO</th>
                                        <th>Coût Pièces</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reparations as $rep)
                                        <tr>
                                            <td>
                                                <small>{{ $rep->date ? \Carbon\Carbon::parse($rep->date)->format('d/m/Y') : 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('vehicules.show', $rep->vehicule) }}" class="text-decoration-none">
                                                    <strong>{{ $rep->vehicule?->brand }} {{ $rep->vehicule?->model }}</strong>
                                                    <br>
                                                    <small class="badge bg-secondary">{{ $rep->vehicule?->immatriculation }}</small>
                                                </a>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($rep->objet_reparation, 40) }}</small>
                                            </td>
                                            <td>
                                                @if ($rep->technicien)
                                                    <a href="{{ route('techniciens.show', $rep->technicien) }}" class="text-decoration-none">
                                                        {{ $rep->technicien?->nom_complet ?? 'N/A' }}
                                                    </a>
                                                @else
                                                    <small class="text-muted">Non assigné</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $rep->duree_main_oeuvre ?? 'N/A' }} h</small>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($rep->cout_pieces ?? 0, 0, ',', ' ') }} FCFA</strong>
                                            </td>
                                            <td>
                                                <a href="{{ route('reparations.show', $rep) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('reparations.edit', $rep) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
    </div>

    {{-- Services Disponibles --}}
    <div class="row g-3 mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs"></i> Services Disponibles
                    </h5>
                    <a href="{{ route('services.index') }}" class="btn btn-sm btn-primary">Voir Tout</a>
                </div>
                <div class="card-body p-0">
                    @php
                        $services = \App\Models\Service::latest()->limit(5)->get();
                    @endphp
                    @if ($services->isEmpty())
                        <div class="text-center py-5">
                            <p class="text-muted">Aucun service créé</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom du Service</th>
                                        <th>Description</th>
                                        <th>Prix</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $service)
                                        <tr>
                                            <td>
                                                <strong>{{ $service->name ?? $service->nom }}</strong>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($service->description, 50) }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($service->price ?? $service->prix ?? 0, 0, ',', ' ') }} FCFA</strong>
                                            </td>
                                            <td>
                                                @if ($service->images || $service->image)
                                                    <img src="{{ asset('storage/' . ($service->images ?? $service->image)) }}" alt="{{ $service->name }}" style="height: 40px; width: 40px; object-fit: cover; border-radius: 4px;">
                                                @else
                                                    <small class="text-muted">Pas d'image</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
    </div>
</div>

@endsection
