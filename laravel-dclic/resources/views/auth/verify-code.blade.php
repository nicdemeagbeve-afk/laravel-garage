@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Vérifier Votre Compte') }}</div>

                <div class="card-body">
                    <p class="text-muted">
                        {{ __('Un code de vérification a été envoyé à votre adresse email. Veuillez le saisir ci-dessous pour activer votre compte.') }}
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verify-code.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="verification_code" class="form-label">{{ __('Code de Vérification') }}</label>
                            <input type="text" class="form-control form-control-lg text-center @error('verification_code') is-invalid @enderror" 
                                   id="verification_code" name="verification_code" 
                                   placeholder="Entrez votre code" required autofocus 
                                   style="letter-spacing: 0.5em; font-size: 1.2rem;">
                            @error('verification_code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">{{ __('Vérifier') }}</button>
                        </div>

                        <div class="mt-3 text-center">
                            <p class="text-muted small">
                                {{ __('Vous n\'avez pas reçu le code? Contactez l\'administrateur.') }}
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
