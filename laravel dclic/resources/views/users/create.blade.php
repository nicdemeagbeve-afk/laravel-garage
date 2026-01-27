@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Créer un Nouvel Utilisateur') }}</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{ __('Erreurs:') }}</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Nom') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Rôle -->
                        <div class="mb-3">
                            <label for="role" class="form-label">{{ __('Rôle') }} <span class="text-danger">*</span></label>
                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">-- Sélectionner un rôle --</option>
                                <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
                                <option value="gestion_client" {{ old('role') === 'gestion_client' ? 'selected' : '' }}>Gestionnaire Clients</option>
                                <option value="responsable_services" {{ old('role') === 'responsable_services' ? 'selected' : '' }}>Responsable Services</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                L'utilisateur devra être approuvé par un administrateur avant de pouvoir accéder à son compte.
                            </small>
                        </div>

                        <!-- Âge -->
                        <div class="mb-3">
                            <label for="age" class="form-label">{{ __('Âge') }}</label>
                            <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                   id="age" name="age" value="{{ old('age') }}" min="1" max="150">
                            @error('age')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Sexe -->
                        <div class="mb-3">
                            <label for="sexe" class="form-label">{{ __('Sexe') }}</label>
                            <select class="form-control @error('sexe') is-invalid @enderror" id="sexe" name="sexe">
                                <option value="">-- Sélectionner --</option>
                                <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>Féminin</option>
                                <option value="Autre" {{ old('sexe') === 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('sexe')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Lieu de Résidence -->
                        <div class="mb-3">
                            <label for="residence" class="form-label">{{ __('Lieu de Résidence') }}</label>
                            <input type="text" class="form-control @error('residence') is-invalid @enderror" 
                                   id="residence" name="residence" value="{{ old('residence') }}" placeholder="Ex: Montréal, QC">
                            @error('residence')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-info" role="alert">
                            <strong>{{ __('Important:') }}</strong> Un code de vérification et un mot de passe temporaire seront envoyés par email à l'utilisateur. L'administrateur devra approuver ce compte avant qu'il ne soit actif.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Créer l\'Utilisateur') }}</button>
                            <a href="{{ route('home') }}" class="btn btn-secondary">{{ __('Annuler') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
