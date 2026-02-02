@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-wrapper">
        <!-- Logo/Header Section -->
        <div class="login-header">
            <div class="logo-box">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <h1 class="login-title">Garage Manager</h1>
            <p class="login-subtitle">Créez votre compte</p>
        </div>

        <!-- Form Section -->
        <div class="login-form-wrapper">
            <form method="POST" action="{{ route('register') }}" class="login-form">
                @csrf

                <!-- Name Input -->
                <div class="form-group">
                    <label for="name" class="form-label">Nom complet</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input 
                            id="name" 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autocomplete="name" 
                            autofocus
                            placeholder="Jean Dupont"
                        >
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block mt-2">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Email Input -->
                <div class="form-group">
                    <label for="email" class="form-label">Adresse email</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input 
                            id="email" 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email"
                            placeholder="vous@example.com"
                        >
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block mt-2">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Phone Input -->
                <div class="form-group">
                    <label for="phone" class="form-label">Numéro de téléphone</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </span>
                        <input 
                            id="phone" 
                            type="tel" 
                            class="form-control @error('phone') is-invalid @enderror" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            required
                            autocomplete="tel"
                            placeholder="+33 6 12 34 56 78"
                        >
                    </div>
                    @error('phone')
                        <div class="invalid-feedback d-block mt-2">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input 
                            id="password" 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="••••••••"
                        >
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-2">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password Input -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            class="form-control" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login btn-lg w-100">
                    {{ __('Créer un compte') }}
                </button>

                <!-- Login Link -->
                @if (Route::has('login'))
                    <div class="text-center mt-4 border-top pt-3">
                        <span class="text-muted">Vous avez déjà un compte ?</span>
                        <a href="{{ route('login') }}" class="register-link ms-2">
                            Se connecter
                        </a>
                    </div>
                @endif
            </form>
        </div>
</div>
@endsection
