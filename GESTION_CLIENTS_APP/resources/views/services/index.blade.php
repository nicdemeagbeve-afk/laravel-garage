@extends('layouts.app')

@section('title', 'Services - Mekano Garage')

@section('extra_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
        }
        .service-card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .service-card img {
            height: 150px;
            object-fit: cover;
        }
        .btn-action {
            margin-right: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1>Gestion des Services</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('services.create') }}" class="btn btn-primary">+ Ajouter un Service</a>
                <a href="{{ route('welcome') }}" class="btn btn-secondary">Retour à l'accueil</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            @forelse($services as $service)
                <div class="col-md-4">
                    <div class="card service-card">
                        @if($service->images)
                            <img src="{{ asset('storage/' . $service->images) }}" class="card-img-top" alt="{{ $service->name }}">
                        @else
                            <div class="card-img-top" style="height: 150px; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center;">
                                <span style="color: #999;">Pas d'image</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $service->name }}</h5>
                            <p class="card-text">{{ Str::limit($service->description, 100) }}</p>
                            <p class="text-primary fw-bold">{{ number_format($service->price, 2, ',', ' ') }} €</p>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="{{ route('services.show', $service->id) }}" class="btn btn-sm btn-info btn-action">Voir</a>
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-warning btn-action">Éditer</a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Aucun service pour le moment. <a href="{{ route('services.create') }}">Créer un service</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"><\/script>
@endsection