@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-wrapper">
        <!-- Logo/Header Section -->
        <div class="login-header">
            <div class="logo-box">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <path d="M7 3v18M17 3v18M3 7h18M3 17h18"/>
                </svg>
            </div>
            <h1 class="login-title">Garage Manager</h1>
            <p class="login-subtitle">Connectez-vous à votre compte</p>
        </div>

        <!-- Form Section -->
        <div class="login-form-wrapper">
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

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
                            autofocus
                            placeholder="vous@example.com"
                        >
                    </div>
                    @error('email')
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
                            autocomplete="current-password"
                            placeholder="••••••••"
                        >
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-2">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="form-group">
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="remember" 
                            id="remember" 
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="remember">
                            Se souvenir de moi
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login btn-lg w-100">
                    {{ __('Connexion') }}
                </button>

                <!-- Password Reset Link -->
                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="forgot-password-link">
                            Mot de passe oublié ?
                        </a>
                    </div>
                @endif

                <!-- Registration Link -->
                @if (Route::has('register'))
                    <div class="text-center mt-4 border-top pt-3">
                        <span class="text-muted">Vous n'avez pas de compte ?</span>
                        <a href="{{ route('register') }}" class="register-link ms-2">
                            S'inscrire maintenant
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Demo Credentials -->
        <div class="login-demo-section">
            <p class="demo-title">🧪 Identifiants de test :</p>
            <div class="demo-credentials">
                <div class="credential-item">
                    <strong>Admin:</strong> admin@garage.test / password123
                </div>
                <div class="credential-item">
                    <strong>Client:</strong> client@garage.test / password123
                </div>
                <div class="credential-item">
                    <strong>Gestion:</strong> gestion@garage.test / password123
                </div>
            </div>
        </div>
    </div>

    <!-- Decorative Element -->
    <div class="decoration-element"></div>
</div>
@endsection
