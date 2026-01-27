{{-- filepath: resources/views/gestion_client/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Tableau de Bord Gestion Client') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>Bienvenue, {{ Auth::user()->name }} !</p>
                    <p>Vous êtes connecté en tant que Gestionnaire de Clients.</p>

                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Clients Enregistrés</h5>
                                    <p class="card-text fs-4">{{ $totalClients }}</p>
                                    <a href="{{-- Route pour lister les clients --}}" class="card-link text-white">Voir les clients</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-white bg-info">
                                <div class="card-body">
                                    <h5 class="card-title">Véhicules Gérés</h5>
                                    <p class="card-text fs-4">{{ $totalVehicules }}</p>
                                    <a href="{{-- Route pour lister les véhicules --}}" class="card-link text-white">Voir les véhicules</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body">
                                    <h5 class="card-title">Pannes en Attente</h5>
                                    <p class="card-text fs-4">{{ $breakdownsPending }}</p>
                                    <a href="{{-- Route pour les pannes en attente --}}" class="card-link text-white">Voir les pannes</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h4>Dernières Pannes</h4>
                            @if($latestBreakdowns->isEmpty())
                                <p>Aucune panne récente.</p>
                            @else
                                <ul class="list-group">
                                    @foreach($latestBreakdowns as $breakdown)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Panne #{{ $breakdown->id }} ({{ $breakdown->status }}) - {{ $breakdown->vehicule->brand ?? 'N/A' }}
                                            <span class="badge bg-secondary">{{ $breakdown->created_at->diffForHumans() }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h4>Nouveaux Clients</h4>
                            @if($latestClients->isEmpty())
                                <p>Aucun nouveau client.</p>
                            @else
                                <ul class="list-group">
                                    @foreach($latestClients as $client)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $client->name }} ({{ $client->email }})
                                            <span class="badge bg-secondary">{{ $client->created_at->diffForHumans() }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    {{-- Ajoutez d'autres sections pertinentes ici --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection