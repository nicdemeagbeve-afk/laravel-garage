@extends('layouts.app')

@section('title', 'Ajouter un Technicien - Mekano Garage')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>Ajouter un Nouveau Technicien</h2>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Erreurs:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        
            <form action="{{ route('techniciens.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="nom" class="fw-bold">Nom</label>
                    <input type="text" id="nom" name="nom" class="form-control" value="{{ old('nom') }}" required>
                </div>

                <div class="form-group">
                    <label for="prenom" class="fw-bold">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="form-control" value="{{ old('prenom') }}" required>
                </div>

                <div class="form-group">
                    <label for="specialite" class="fw-bold">Spécialité</label>
                    <textarea id="specialite" name="specialite" class="form-control">{{ old('specialite') }}</textarea>
                </div>

                <div class="btn-group mt-20">
                    <button type="submit" class="btn btn-success btn-lg">Enregistrer</button>
                    <a href="{{ route('techniciens.index') }}" class="btn btn-secondary btn-lg">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
