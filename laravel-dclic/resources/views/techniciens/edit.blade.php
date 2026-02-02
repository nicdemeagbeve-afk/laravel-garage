@extends('layouts.app')

@section('title', 'Modifier un Technicien - Mekano Garage')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>Modifier le Technicien</h2>
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
        
            <form action="{{ route('techniciens.update', $technicien->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nom" class="fw-bold">Nom</label>
                    <input type="text" id="nom" name="nom" class="form-control" value="{{ $technicien->nom }}" required>
                </div>

                <div class="form-group">
                    <label for="prenom" class="fw-bold">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="form-control" value="{{ $technicien->prenom }}" required>
                </div>

                <div class="form-group">
                    <label for="specialite" class="fw-bold">Spécialité</label>
                    <textarea id="specialite" name="specialite" class="form-control">{{ $technicien->specialite }}</textarea>
                </div>

                <div class="btn-group mt-20">
                    <button type="submit" class="btn btn-success btn-lg">Mettre à jour</button>
                    <a href="{{ route('techniciens.index') }}" class="btn btn-secondary btn-lg">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection