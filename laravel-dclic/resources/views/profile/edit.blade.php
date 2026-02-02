@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modifier Mon Profil') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Nom') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Âge -->
                        <div class="mb-3">
                            <label for="age" class="form-label">{{ __('Âge') }}</label>
                            <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                   id="age" name="age" value="{{ old('age', $user->age) }}" min="1" max="150">
                            @error('age')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Sexe -->
                        <div class="mb-3">
                            <label for="sexe" class="form-label">{{ __('Sexe') }}</label>
                            <select class="form-control @error('sexe') is-invalid @enderror" id="sexe" name="sexe">
                                <option value="">-- Sélectionner --</option>
                                <option value="M" {{ old('sexe', $user->sexe) === 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('sexe', $user->sexe) === 'F' ? 'selected' : '' }}>Féminin</option>
                                <option value="Autre" {{ old('sexe', $user->sexe) === 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('sexe')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Lieu de Résidence -->
                        <div class="mb-3">
                            <label for="residence" class="form-label">{{ __('Lieu de Résidence') }}</label>
                            <input type="text" class="form-control @error('residence') is-invalid @enderror" 
                                   id="residence" name="residence" value="{{ old('residence', $user->residence) }}" placeholder="Ex: Montréal, QC">
                            @error('residence')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr>

                        <!-- Changer le mot de passe (optionnel) -->
                        <h5 class="mb-3">{{ __('Changer le Mot de Passe (Optionnel)') }}</h5>

                        <!-- Mot de passe actuel -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('Mot de Passe Actuel') }}</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Nouveau mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Nouveau Mot de Passe') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirmer Mot de Passe') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Mettre à Jour le Profil') }}</button>
                            <a href="{{ route('home') }}" class="btn btn-secondary">{{ __('Annuler') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
