<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#2c3e50">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="{{ asset('welcome.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard-admin.css') }}" rel="stylesheet">
    <link href="{{ asset('style.css') }}" rel="stylesheet">
    <link href="{{ asset('CRUD.CSS') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layout.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @vite([ 'resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app" class="app-wrapper">
        <!-- ========== HEADER ========== -->
        <header class="app-header">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="navbar-container">
                    <!-- Logo & Brand -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('garage/logo.png') }}" alt="Garage Logo" class="navbar-logo">
                        <span class="brand-name">{{ config('app.name', 'Garage Manager') }}</span>
                    </a>

                    <!-- Toggle Button Mobile -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Navigation Content -->
                    <div class="collapse navbar-collapse" id="navbarContent">
                        <!-- Center Navigation -->
                        <ul class="navbar-nav mx-auto">
                            @guest
                                @if (Route::has('welcome'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('welcome') }}">
                                            <i class="icon-home"></i>{{ __('Accueil') }}
                                        </a>
                                    </li>
                                @endif
                                @if (Route::has('contact'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('contact') }}">
                                            <i class="icon-contact"></i>{{ __('Contact') }}
                                        </a>
                                    </li>
                                @endif
                                @if (Route::has('apropos'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('apropos') }}">
                                            <i class="icon-about"></i>{{ __('À Propos') }}
                                        </a>
                                    </li>
                                @endif
                            @endguest
                        </ul>

                        <!-- Right Side Auth Links -->
                        <ul class="navbar-nav ms-auto">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link btn-link-login" href="{{ route('login') }}">{{ __('Connexion') }}</a>
                                    </li>
                                @endif
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link btn-link-register" href="{{ route('register') }}">{{ __('Inscription') }}</a>
                                    </li>
                                @endif
                            @else
                                <!-- User Dropdown -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="icon-user"></i>{{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <!-- Admin Menu -->
                                        @if (Auth::user()->role === 'admin')
                                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Tableau de Bord</a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"> Gestion Utilisateurs</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                        @endif

                                        <!-- Responsable Services Menu -->
                                        @if (Auth::user()->role === 'responsable_services')
                                            <li><a class="dropdown-item" href="{{ route('home') }}"> Tableau de Bord</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                        @endif

                                        <!-- Gestion Client Menu -->
                                        @if (Auth::user()->role === 'gestion_client')
                                            <li><a class="dropdown-item" href="{{ route('gestion_client.dashboard') }}"> Tableau de Bord</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                        @endif

                                        <!-- Client Menu -->
                                        @if (Auth::user()->role === 'client')
                                            <li><a class="dropdown-item" href="{{ route('home') }}"> Accueil</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                        @endif

                                        <!-- Profile for all users -->
                                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"> Mon Profil</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                 Déconnexion
                                            </a>
                                        </li>
                                    </ul>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- ========== MAIN CONTENT ========== -->
        <main class="app-main">
            @yield('content')
        </main>

        <!-- ========== FOOTER ========== -->
        <footer class="app-footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h5>Mekano Garage</h5>
                    <p>Votre partenaire de confiance pour l'entretien et la réparation de votre véhicule.</p>
                </div>

                <div class="footer-section">
                    <h5>Navigation</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('apropos') }}">À Propos</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h5>Informations</h5>
                    <ul class="footer-links">
                        <li><a href="#">Conditions d'Utilisation</a></li>
                        <li><a href="#">Politique de Confidentialité</a></li>
                        <li><a href="#">Mentions Légales</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h5>Nous Suivre</h5>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">f</a>
                        <a href="#" class="social-link" aria-label="Instagram"></a>
                        <a href="#" class="social-link" aria-label="Twitter">𝕏</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Garage Manager. Tous droits réservés.</p>
            </div>
        </footer>
    </div>

    <!-- SweetAlert2 pour les pop-ups -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Script pour afficher les messages de session en pop-up -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Afficher les messages de succès
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745'
                });
            @endif

            // Afficher les messages d'erreur
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: "{{ session('error') }}",
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            @endif

            // Afficher les messages d'avertissement
            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Attention',
                    text: "{{ session('warning') }}",
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ffc107'
                });
            @endif

            // Afficher les messages d'information
            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Information',
                    text: "{{ session('info') }}",
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#17a2b8'
                });
            @endif

            // Afficher les messages de statut (par défaut succès)
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Opération réussie',
                    text: "{{ session('status') }}",
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745'
                });
            @endif
        });
    </script>
</body>
</html>
