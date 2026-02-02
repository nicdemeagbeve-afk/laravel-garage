@extends('layouts.app')

@section('title', 'Éditer un Véhicule - Mekano Garage')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>Éditer le Véhicule</h2>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Erreurs lors de la validation:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        
            <form action="{{ route('vehicules.update', $vehicule->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="immatriculation" class="fw-bold">Immatriculation</label>
                    <input type="text" id="immatriculation" name="immatriculation" class="form-control @error('immatriculation') is-invalid @enderror" 
                           value="{{ $vehicule->immatriculation }}" required>
                    @error('immatriculation')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="marque" class="fw-bold">Marque</label>
                        <input type="text" id="marque" name="marque" class="form-control @error('marque') is-invalid @enderror" 
                               value="{{ $vehicule->marque }}" required>
                        @error('marque')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="modele" class="fw-bold">Modèle</label>
                        <input type="text" id="modele" name="modele" class="form-control @error('modele') is-invalid @enderror" 
                               value="{{ $vehicule->modele }}" required>
                        @error('modele')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="couleur" class="fw-bold">Couleur</label>
                        <input type="text" id="couleur" name="couleur" class="form-control" 
                               value="{{ $vehicule->couleur }}">
                    </div>

                    <div class="form-group">
                        <label for="annee" class="fw-bold">Année</label>
                        <input type="number" id="annee" name="annee" class="form-control" 
                               value="{{ $vehicule->annee }}" min="1900" max="2099">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kilometrage" class="fw-bold">Kilométrage</label>
                        <input type="number" id="kilometrage" name="kilometrage" class="form-control" 
                               value="{{ $vehicule->kilometrage }}" min="0">
                    </div>

                    <div class="form-group">
                        <label for="carrosserie" class="fw-bold">Carrosserie</label>
                        <input type="text" id="carrosserie" name="carrosserie" class="form-control" 
                               value="{{ $vehicule->carrosserie }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="energie" class="fw-bold">Énergie</label>
                        <select id="energie" name="energie" class="form-control">
                            <option value="">Sélectionner...</option>
                            <option value="essence" @if($vehicule->energie == 'essence') selected @endif>Essence</option>
                            <option value="diesel" @if($vehicule->energie == 'diesel') selected @endif>Diesel</option>
                            <option value="hybride" @if($vehicule->energie == 'hybride') selected @endif>Hybride</option>
                            <option value="électrique" @if($vehicule->energie == 'électrique') selected @endif>Électrique</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="boite" class="fw-bold">Boîte de Vitesses</label>
                        <select id="boite" name="boite" class="form-control">
                            <option value="">Sélectionner...</option>
                            <option value="manuelle" @if($vehicule->boite == 'manuelle') selected @endif>Manuelle</option>
                            <option value="automatique" @if($vehicule->boite == 'automatique') selected @endif>Automatique</option>
                        </select>
                    </div>
                </div>

                <div class="btn-group mt-20">
                    <button type="submit" class="btn btn-success btn-lg">Mettre à jour</button>
                    <a href="{{ route('vehicules.index') }}" class="btn btn-secondary btn-lg">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection