@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <a href="{{ route('breakdowns.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Retour aux Déclarations
            </a>
            <h1 class="display-6">Détails de la Déclaration de Panne</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statut de la Panne -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> État de la Déclaration</h5>
                        <span class="badge badge-status status-{{ $breakdown->status }}">
                            {{ $breakdown->status_label }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar"></i> Date de Déclaration:</strong><br>
                                {{ $breakdown->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">
                                <strong><i class="fas fa-history"></i> Dernière Modification:</strong><br>
                                {{ $breakdown->updated_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations Véhicule -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-car"></i> Véhicule Concerné</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Marque:</strong> {{ $breakdown->vehicule->marque }}
                            </p>
                            <p class="mb-0">
                                <strong>Modèle:</strong> {{ $breakdown->vehicule->modele }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Immatriculation:</strong>
                                <span class="badge bg-warning text-dark">{{ $breakdown->vehicule->immatriculation }}</span>
                            </p>
                            <p class="mb-0">
                                <strong>Année:</strong> {{ $breakdown->vehicule->annee }}
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Carrosserie:</strong> {{ $breakdown->vehicule->carrosserie ?? 'N/A' }}
                            </p>
                            <p class="mb-0">
                                <strong>Énergie:</strong> {{ $breakdown->vehicule->energie ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Boîte de Vitesses:</strong> {{ $breakdown->vehicule->boite ?? 'N/A' }}
                            </p>
                            <p class="mb-0">
                                <strong>Kilométrage:</strong> {{ $breakdown->vehicule->kilometrage ?? 'N/A' }} km
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description de la Panne -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Description du Problème</h5>
                </div>
                <div class="card-body">
                    <p class="lead mb-0">{{ $breakdown->description }}</p>
                </div>
            </div>

            <!-- Options Dépannage -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-tools"></i> Options de Dépannage</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3"><i class="fas fa-map-marker-alt"></i> Dépannage Sur Place</h6>
                            @if ($breakdown->onsite_assistance)
                                <div class="alert alert-success" role="alert">
                                    <i class="fas fa-check-circle"></i> <strong>Demandé</strong>
                                </div>

                                @if ($breakdown->latitude && $breakdown->longitude)
                                    <p class="mb-2">
                                        <strong>Coordonnées GPS:</strong><br>
                                        <span class="badge bg-info">{{ $breakdown->formatted_coordinates }}</span>
                                    </p>
                                    <p class="mb-0">
                                        <a href="https://maps.google.com/?q={{ $breakdown->latitude }},{{ $breakdown->longitude }}" 
                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-map"></i> Voir sur Google Maps
                                        </a>
                                    </p>
                                @else
                                    <p class="text-muted">Aucune localisation fournie</p>
                                @endif
                            @else
                                <div class="alert alert-secondary" role="alert">
                                    <i class="fas fa-times-circle"></i> Non demandé
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3"><i class="fas fa-user-hard-hat"></i> Technicien Assigné</h6>
                            @if ($breakdown->technicien)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title mb-2">{{ $breakdown->technicien->nom_complet }}</h6>
                                        <p class="card-text mb-2">
                                            <strong>Spécialité:</strong> {{ $breakdown->technicien->specialite }}
                                        </p>
                                        <p class="card-text mb-0">
                                            <strong>Âge:</strong> {{ $breakdown->technicien->age }} ans
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info" role="alert">
                                    <i class="fas fa-info-circle"></i> Aucun technicien assigné pour le moment
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        @if (!in_array($breakdown->status, ['resolved', 'cancelled']))
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('breakdowns.edit', $breakdown) }}" class="btn btn-warning btn-lg w-100">
                                    <i class="fas fa-edit"></i> Éditer cette Déclaration
                                </a>
                            </div>
                            <div class="col-md-6 mb-2">
                                <form action="{{ route('breakdowns.destroy', $breakdown) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-lg w-100"
                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette déclaration ?');">
                                        <i class="fas fa-trash"></i> Annuler la Déclaration
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    <i class="fas fa-lock"></i> Cette déclaration est {{ $breakdown->status === 'resolved' ? 'résolue' : 'annulée' }} et ne peut pas être modifiée.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .badge-status {
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .status-pending {
        background-color: #ffc107 !important;
        color: #000;
    }

    .status-in_progress {
        background-color: #007bff !important;
        color: #fff;
    }

    .status-resolved {
        background-color: #28a745 !important;
        color: #fff;
    }

    .status-cancelled {
        background-color: #6c757d !important;
        color: #fff;
    }

    .card-header {
        border-bottom: 2px solid rgba(0, 0, 0, 0.1);
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
    }
</style>
@endsection
