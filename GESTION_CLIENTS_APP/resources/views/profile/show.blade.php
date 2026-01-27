@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profil de ') }}{{ $user->name }}</div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Nom</h6>
                            <p>{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Email</h6>
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Âge</h6>
                            <p>{{ $user->age ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Sexe</h6>
                            <p>
                                @switch($user->sexe)
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
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Lieu de Résidence</h6>
                            <p>{{ $user->residence ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Rôle</h6>
                            <p>
                                <span class="badge bg-info">
                                    @switch($user->role)
                                        @case('admin')
                                            Administrateur
                                            @break
                                        @case('responsable_services')
                                            Responsable Services
                                            @break
                                        @case('gestion_client')
                                            Gestionnaire Clients
                                            @break
                                        @case('client')
                                            Client
                                            @break
                                    @endswitch
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Statut</h6>
                            <p>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-warning' }}">
                                    {{ $user->is_active ? 'Actif' : 'En attente d\'approbation' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Date d'inscription</h6>
                            <p>{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if (Auth::id() === $user->id)
                        <div class="d-grid gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">{{ __('Modifier Mon Profil') }}</a>
                        </div>
                    @elseif (Auth::user()->isAdmin())
                        <div class="d-grid gap-2 d-md-flex gap-2">
                            @if (!$user->is_active)
                                <form method="POST" action="{{ route('users.approve', $user) }}" style="flex: 1;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success w-100">{{ __('Approuver') }}</button>
                                </form>
                            @endif
                            <a href="{{ route('users.index') }}" class="btn btn-secondary flex-grow-1">{{ __('Retour à la Liste') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
