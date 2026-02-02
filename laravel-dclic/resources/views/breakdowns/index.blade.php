@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-6">Mes Déclarations de Pannes</h1>
            <p class="text-muted">Gérez vos déclarations de pannes et suivez l'état de chacune</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('breakdowns.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i> Déclarer une Panne
            </a>
        </div>
    </div>

    @if ($breakdowns->count() > 0)
        <div class="row">
            @foreach ($breakdowns as $breakdown)
                <div class="col-lg-6 mb-4">
                    <div class="card breakdown-card shadow-sm h-100">
                        <div class="card-header bg-gradient">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-car"></i> {{ $breakdown->vehicule->marque }} {{ $breakdown->vehicule->modele }}
                                </h5>
                                <span class="badge badge-status status-{{ $breakdown->status }}">
                                    {{ $breakdown->status_label }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>Immatriculation:</strong> {{ $breakdown->vehicule->immatriculation }}
                            </p>
                            <p class="mb-2">
                                <strong>Description:</strong>
                                <span class="text-truncate d-block">{{ Str::limit($breakdown->description, 60) }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Dépannage sur place:</strong>
                                @if ($breakdown->onsite_assistance)
                                    <span class="badge bg-success">Oui</span>
                                @else
                                    <span class="badge bg-secondary">Non</span>
                                @endif
                            </p>
                            @if ($breakdown->technicien)
                                <p class="mb-0">
                                    <strong>Technicien:</strong> {{ $breakdown->technicien->nom_complet }}
                                </p>
                            @endif
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                Déclarée le {{ $breakdown->created_at->format('d/m/Y à H:i') }}
                            </small>
                            <div class="mt-2">
                                <a href="{{ route('breakdowns.show', $breakdown) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                                @if (!in_array($breakdown->status, ['resolved', 'cancelled']))
                                    <a href="{{ route('breakdowns.edit', $breakdown) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Éditer
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $breakdowns->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> <strong>Aucune déclaration pour le moment</strong>
            <p class="mb-0">Vous n'avez pas encore déclaré de panne. <a href="{{ route('breakdowns.create') }}">Commencez par déclarer une panne maintenant</a>.</p>
        </div>
    @endif
</div>

<style>
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .breakdown-card {
        transition: all 0.3s ease;
        border: none;
    }

    .breakdown-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .badge-status {
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
    }

    .status-pending {
        background-color: #ffc107;
        color: #000;
    }

    .status-in_progress {
        background-color: #007bff;
        color: #fff;
    }

    .status-resolved {
        background-color: #28a745;
        color: #fff;
    }

    .status-cancelled {
        background-color: #6c757d;
        color: #fff;
    }
</style>
@endsection
