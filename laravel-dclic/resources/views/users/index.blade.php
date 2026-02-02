@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('Gestion des Utilisateurs') }}</h3>
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">{{ __('Créer Utilisateur') }}</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Tabs pour les filtres -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                                {{ __('Tous') }} <span class="badge bg-secondary">{{ $users->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                                {{ __('En Attente') }} <span class="badge bg-warning">{{ $pending_users->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                                {{ __('Actifs') }} <span class="badge bg-success">{{ $active_users->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Tous les utilisateurs -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>{{ __('Nom') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Rôle') }}</th>
                                            <th>{{ __('Statut') }}</th>
                                            <th>{{ __('Créé par') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        @switch($user->role)
                                                            @case('admin')
                                                                Admin
                                                                @break
                                                            @case('responsable_services')
                                                                Resp. Services
                                                                @break
                                                            @case('gestion_client')
                                                                Gest. Clients
                                                                @break
                                                            @case('client')
                                                                Client
                                                                @break
                                                        @endswitch
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($user->deleted_at)
                                                        <span class="badge bg-danger">Supprimé</span>
                                                    @elseif ($user->is_active)
                                                        <span class="badge bg-success">Actif</span>
                                                    @else
                                                        <span class="badge bg-warning">En Attente</span>
                                                    @endif
                                                </td>
                                                <td>{{ $user->creator?->name ?? 'Inscription' }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('profile.show', $user) }}" class="btn btn-info" title="Voir Profil">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                        @if (!$user->is_active && !$user->deleted_at)
                                                            <form method="POST" action="{{ route('users.approve', $user) }}" style="display:inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-success btn-sm" title="Approuver" onclick="return confirm('Approuver cet utilisateur?')">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if ($user->is_active && !$user->deleted_at && $user->id !== Auth::id())
                                                            <form method="POST" action="{{ route('users.deactivate', $user) }}" style="display:inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-warning btn-sm" title="Désactiver" onclick="return confirm('Désactiver cet utilisateur?')">
                                                                    <i class="fas fa-ban"></i>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if (!$user->deleted_at && $user->id !== Auth::id())
                                                            <form method="POST" action="{{ route('users.destroy', $user) }}" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Supprimer cet utilisateur?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @elseif ($user->deleted_at)
                                                            <form method="POST" action="{{ route('users.restore', $user) }}" style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-secondary btn-sm" title="Restaurer" onclick="return confirm('Restaurer cet utilisateur?')">
                                                                    <i class="fas fa-undo"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">{{ __('Aucun utilisateur') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $users->links() }}
                            </div>
                        </div>

                        <!-- Utilisateurs en attente -->
                        <div class="tab-pane fade" id="pending" role="tabpanel">
                            @if ($pending_users->isEmpty())
                                <p class="text-muted text-center">{{ __('Aucun utilisateur en attente d\'approbation') }}</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>{{ __('Nom') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Rôle') }}</th>
                                                <th>{{ __('Créé le') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pending_users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            @switch($user->role)
                                                                @case('client')
                                                                    Client
                                                                    @break
                                                                @case('gestion_client')
                                                                    Gest. Clients
                                                                    @break
                                                                @case('responsable_services')
                                                                    Resp. Services
                                                                    @break
                                                            @endswitch
                                                        </span>
                                                    </td>
                                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('profile.show', $user) }}" class="btn btn-info btn-sm">Voir</a>
                                                        <form method="POST" action="{{ route('users.approve', $user) }}" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approuver cet utilisateur?')">Approuver</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Utilisateurs actifs -->
                        <div class="tab-pane fade" id="active" role="tabpanel">
                            @if ($active_users->isEmpty())
                                <p class="text-muted text-center">{{ __('Aucun utilisateur actif') }}</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>{{ __('Nom') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Rôle') }}</th>
                                                <th>{{ __('Depuis') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($active_users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            @switch($user->role)
                                                                @case('admin')
                                                                    Admin
                                                                    @break
                                                                @case('responsable_services')
                                                                    Resp. Services
                                                                    @break
                                                                @case('gestion_client')
                                                                    Gest. Clients
                                                                    @break
                                                                @case('client')
                                                                    Client
                                                                    @break
                                                            @endswitch
                                                        </span>
                                                    </td>
                                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('profile.show', $user) }}" class="btn btn-info btn-sm">Voir</a>
                                                        @if ($user->id !== Auth::id())
                                                            <form method="POST" action="{{ route('users.deactivate', $user) }}" style="display:inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Désactiver cet utilisateur?')">Désactiver</button>
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
            </div>
        </div>
    </div>
</div>
@endsection
