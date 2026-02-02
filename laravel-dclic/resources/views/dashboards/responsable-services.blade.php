@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- En-tête --}}
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-tools"></i> Tableau de Bord Responsable Services
            </h1>
            <p class="text-muted mt-2">Gestion des réparations, services et techniciens</p>
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
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Réparations Total</p>
                            <h3 class="mb-0">{{ $stats['reparations_total'] }}</h3>
                        </div>
                        <div class="text-primary" style="font-size: 2rem;">
                            <i class="fas fa-tools"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">En Cours</p>
                            <h3 class="mb-0">{{ $stats['reparations_en_cours'] }}</h3>
                        </div>
                        <div class="text-info" style="font-size: 2rem;">
                            <i class="fas fa-wrench"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Services</p>
                            <h3 class="mb-0">{{ $stats['services_total'] }}</h3>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="fas fa-cogs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Techniciens</p>
                            <h3 class="mb-0">{{ $stats['techniciens_total'] }}</h3>
                        </div>
                        <div class="text-warning" style="font-size: 2rem;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alertes importantes --}}
    @if ($stats['pannes_en_attente'] > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>{{ $stats['pannes_en_attente'] }} panne(s) en attente d'assignment!</strong>
            <a href="{{ route('breakdowns.index') }}" class="alert-link">Voir les détails</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Contenu principal --}}
    <div class="row g-3">
        {{-- Réparations Récentes --}}
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Réparations Récentes
                    </h5>
                    <a href="{{ route('reparations.index') }}" class="btn btn-sm btn-primary">
                        Voir Toutes
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($reparationsRecentes->isEmpty())
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
                                        <th>Objet</th>
                                        <th>Technicien</th>
                                        <th>Durée MO</th>
                                        <th>Coût Pièces</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reparationsRecentes as $reparation)
                                        <tr>
                                            <td>
                                                <small>{{ $reparation->date ? \Carbon\Carbon::parse($reparation->date)->format('d/m/Y') : 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('vehicules.show', $reparation->vehicule) }}" class="text-decoration-none">
                                                    <strong>{{ $reparation->vehicule?->brand }} {{ $reparation->vehicule?->model }}</strong>
                                                    <br>
                                                    <small class="badge bg-secondary">{{ $reparation->vehicule?->immatriculation }}</small>
                                                </a>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($reparation->objet_reparation, 40) }}</small>
                                            </td>
                                            <td>
                                                @if ($reparation->technicien)
                                                    <a href="{{ route('techniciens.show', $reparation->technicien) }}" class="text-decoration-none">
                                                        {{ $reparation->technicien?->nom_complet ?? 'N/A' }}
                                                    </a>
                                                @else
                                                    <small class="text-muted">Non assigné</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $reparation->duree_main_oeuvre ?? 'N/A' }} h</small>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($reparation->cout_pieces ?? 0, 0, ',', ' ') }} FCFA</strong>
                                            </td>
                                            <td>
                                                <a href="{{ route('reparations.show', $reparation) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('reparations.edit', $reparation) }}" class="btn btn-sm btn-warning">
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

            {{-- Pannes en Attente --}}
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-hourglass-half"></i> Pannes En Attente d'Assignment ({{ $pannesEnAttente->count() }})
                    </h5>
                    <a href="{{ route('breakdowns.index') }}" class="btn btn-sm btn-primary">
                        Voir Toutes
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($pannesEnAttente->isEmpty())
                        <div class="text-center py-5">
                            <p class="text-success">
                                <i class="fas fa-check-circle" style="font-size: 2rem;"></i>
                            </p>
                            <p class="text-muted">Toutes les pannes sont assignées!</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Client</th>
                                        <th>Véhicule</th>
                                        <th>Description</th>
                                        <th>Date Signalement</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pannesEnAttente as $panne)
                                        <tr class="table-warning">
                                            <td>
                                                <a href="{{ route('profile.show', $panne->user) }}" class="text-decoration-none">
                                                    <strong>{{ $panne->user?->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $panne->user?->phone ?? 'N/A' }}</small>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('vehicules.show', $panne->vehicule) }}" class="text-decoration-none">
                                                    <strong>{{ $panne->vehicule?->brand }} {{ $panne->vehicule?->model }}</strong>
                                                    <br>
                                                    <small class="badge bg-secondary">{{ $panne->vehicule?->immatriculation }}</small>
                                                </a>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($panne->description, 40) }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $panne->created_at->format('d/m/Y H:i') }}</small>
                                                <br>
                                                <small class="text-muted">{{ $panne->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">En Attente</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('breakdowns.show', $panne) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('breakdowns.edit', $panne) }}" class="btn btn-sm btn-warning">
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
                    <a href="{{ route('reparations.create') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-plus-circle text-success me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Nouvelle Réparation</h6>
                                <small class="text-muted">Créer une réparation</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('services.create') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-cog text-info me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Nouveau Service</h6>
                                <small class="text-muted">Ajouter un service</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('reparations.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tools text-primary me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Réparations</h6>
                                <small class="text-muted">Gérer les réparations</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('services.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list text-warning me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Services</h6>
                                <small class="text-muted">Liste des services</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('techniciens.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users text-secondary me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0">Techniciens</h6>
                                <small class="text-muted">Gérer les techniciens</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Services Disponibles --}}
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs"></i> Services Disponibles ({{ $servicesDisponibles->count() }})
                    </h5>
                    <a href="{{ route('services.index') }}" class="btn btn-sm btn-primary">Voir Tous</a>
                </div>
                <div class="card-body p-0">
                    @if ($servicesDisponibles->isEmpty())
                        <div class="text-center py-4 p-3">
                            <p class="text-muted small mb-0">Aucun service créé</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($servicesDisponibles as $service)
                                <a href="{{ route('services.show', $service) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $service->nom ?? $service->name }}</h6>
                                            <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary">{{ number_format($service->prix ?? $service->price ?? 0, 0, ',', ' ') }} FCFA</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
