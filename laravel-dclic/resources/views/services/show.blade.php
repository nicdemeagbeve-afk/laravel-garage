@extends('layouts.app')

@section('title', 'Véhicules - Mekano Garage')

@section('extra_css')
<style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .service-detail {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .service-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="service-detail">
            <div class="row">
                <div class="col-md-6">
                    @if($service->images)
                        <img src="{{ asset('storage/' . $service->images) }}" alt="{{ $service->name }}" class="service-image">
                    @else
                        <div style="height: 300px; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                            <span style="color: #999; font-size: 18px;">Pas d'image disponible</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <h1>{{ $service->name }}</h1>
                    <hr>
                    <p class="text-muted">Service ID: {{ $service->id }}</p>
                    
                    <h3 class="text-primary">{{ number_format($service->price, 2, ',', ' ') }} €</h3>
                    
                    <h4 class="mt-4">Description</h4>
                    <p>{{ $service->description ?? 'Pas de description disponible' }}</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning">Éditer</a>
                        <a href="{{ route('services.index') }}" class="btn btn-secondary">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
@endsection