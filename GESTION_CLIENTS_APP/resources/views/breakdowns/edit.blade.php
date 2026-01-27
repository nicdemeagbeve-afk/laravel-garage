@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <a href="{{ route('breakdowns.show', $breakdown) }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1 class="display-6">Modifier la Déclaration de Panne</h1>
            <p class="text-muted">Mettez à jour les informations de votre panne</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Formulaire de Modification</h5>
                </div>
                <div class="card-body p-5">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5><i class="fas fa-times-circle"></i> Erreurs de validation</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('breakdowns.update', $breakdown) }}" method="POST" x-data="breakdownEditForm()">
                        @csrf
                        @method('PUT')

                        <!-- Sélection du Véhicule -->
                        <div class="form-group mb-4">
                            <label for="vehicule_id" class="form-label fw-bold">
                                <i class="fas fa-car"></i> Sélectionnez votre Véhicule *
                            </label>
                            <select name="vehicule_id" id="vehicule_id" class="form-select form-select-lg @error('vehicule_id') is-invalid @enderror" required>
                                @foreach ($vehicules as $vehicule)
                                    <option value="{{ $vehicule->id }}"
                                        {{ $breakdown->vehicule_id == $vehicule->id ? 'selected' : '' }}>
                                        {{ $vehicule->marque }} {{ $vehicule->modele }} ({{ $vehicule->immatriculation }})
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicule_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description de la Panne -->
                        <div class="form-group mb-4">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-pen-fancy"></i> Description du Problème *
                            </label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                x-model="form.description"
                                rows="5" required>{{ $breakdown->description }}</textarea>
                            <small class="form-text text-muted">
                                <span x-text="form.description.length"></span> / 1000 caractères
                            </small>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Option Dépannage sur Place -->
                        <div class="form-group mb-4 p-4 bg-light rounded">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="onsite_assistance"
                                    name="onsite_assistance" value="true"
                                    {{ $breakdown->onsite_assistance ? 'checked' : '' }}
                                    @change="showLocationSection = !showLocationSection">
                                <label class="form-check-label fw-bold" for="onsite_assistance">
                                    <i class="fas fa-map-marker-alt"></i> Dépannage sur place ?
                                </label>
                            </div>
                        </div>

                        <!-- Section Géolocalisation -->
                        <div class="form-group mb-4 p-4 bg-info bg-opacity-10 rounded border border-info"
                            x-show="showLocationSection" x-transition.duration.300ms 
                            style="{{ $breakdown->onsite_assistance ? 'display: block;' : 'display: none;' }}">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="text" class="form-control" id="latitude_display"
                                        x-model="form.latitude" readonly>
                                    <input type="hidden" name="latitude" id="latitude" x-model="form.latitude">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="text" class="form-control" id="longitude_display"
                                        x-model="form.longitude" readonly>
                                    <input type="hidden" name="longitude" id="longitude" x-model="form.longitude">
                                </div>
                            </div>

                            <button type="button" @click="getLocation()" class="btn btn-info btn-lg w-100 mt-3">
                                <i class="fas fa-location-crosshairs"></i> Mettre à Jour ma Localisation
                            </button>

                            <div x-show="locationMessage" class="alert mt-3" :class="locationMessageType">
                                <span x-text="locationMessage"></span>
                            </div>
                        </div>

                        <!-- Option Technicien -->
                        <div class="form-group mb-4 p-4 bg-light rounded">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="choose_technician"
                                    {{ $breakdown->technicien_id ? 'checked' : '' }}
                                    @change="showTechnicianSection = !showTechnicianSection">
                                <label class="form-check-label fw-bold" for="choose_technician">
                                    <i class="fas fa-user-hard-hat"></i> Choisir un technicien spécifique ?
                                </label>
                            </div>
                        </div>

                        <!-- Section Sélection Technicien -->
                        <div class="form-group mb-4" x-show="showTechnicianSection" x-transition.duration.300ms
                            style="{{ $breakdown->technicien_id ? 'display: block;' : 'display: none;' }}">
                            
                            <label class="form-label fw-bold mb-3">Sélectionnez un Technicien</label>

                            <div class="row g-3">
                                @foreach ($techniciens as $tech)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card technician-card h-100 cursor-pointer {{ $breakdown->technicien_id == $tech->id ? 'selected' : '' }}"
                                            onclick="document.getElementById('tech_{{ $tech->id }}').click();">
                                            <div class="card-body text-center">
                                                <div class="mb-3">
                                                    @if ($tech->photo_url)
                                                        <img src="{{ $tech->photo_url }}" alt="Photo" class="rounded-circle"
                                                            style="width: 80px; height: 80px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center"
                                                            style="width: 80px; height: 80px;">
                                                            <i class="fas fa-user text-white fa-2x"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                <h6 class="card-title mb-2">{{ $tech->nom_complet }}</h6>
                                                <p class="card-text text-muted mb-2">
                                                    <small>{{ $tech->specialite }}</small>
                                                </p>
                                                <p class="card-text mb-0">
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-birthday-cake"></i> {{ $tech->age }} ans
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="card-footer bg-white text-center border-top">
                                                <input type="radio" id="tech_{{ $tech->id }}" name="technicien_id" value="{{ $tech->id }}"
                                                    {{ $breakdown->technicien_id == $tech->id ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2">Sélectionner</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="form-group mt-5 pt-4 border-top">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('breakdowns.show', $breakdown) }}" class="btn btn-secondary btn-lg w-100">
                                        <i class="fas fa-times"></i> Annuler
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-lg w-100">
                                        <i class="fas fa-save"></i> Enregistrer les Modifications
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function breakdownEditForm() {
        return {
            form: {
                description: `{{ $breakdown->description }}`,
                latitude: `{{ $breakdown->latitude }}`,
                longitude: `{{ $breakdown->longitude }}`
            },
            showLocationSection: {{ $breakdown->onsite_assistance ? 'true' : 'false' }},
            showTechnicianSection: {{ $breakdown->technicien_id ? 'true' : 'false' }},
            locationMessage: '',
            locationMessageType: 'alert-info',

            /**
             * Récupérer la localisation GPS
             */
            getLocation() {
                if (!navigator.geolocation) {
                    this.locationMessage = 'La géolocalisation n\'est pas supportée par votre navigateur';
                    this.locationMessageType = 'alert-danger';
                    return;
                }

                this.locationMessage = 'Localisation en cours...';
                this.locationMessageType = 'alert-info';

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.form.latitude = position.coords.latitude.toFixed(8);
                        this.form.longitude = position.coords.longitude.toFixed(8);
                        this.locationMessage = '✓ Localisation mise à jour ! (Lat: ' + this.form.latitude + ', Lng: ' + this.form.longitude + ')';
                        this.locationMessageType = 'alert-success';
                    },
                    (error) => {
                        let errorMessage = 'Erreur lors de la récupération de votre localisation';
                        
                        if (error.code === error.PERMISSION_DENIED) {
                            errorMessage = 'Permission de localisation refusée. Autorisez-la dans les paramètres du navigateur.';
                        }

                        this.locationMessage = errorMessage;
                        this.locationMessageType = 'alert-danger';
                    }
                );
            }
        }
    }
</script>

<style>
    .technician-card {
        transition: all 0.3s ease;
        border: 3px solid #dee2e6;
        cursor: pointer;
    }

    .technician-card:hover {
        border-color: #007bff;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        transform: translateY(-5px);
    }

    .technician-card.selected {
        border-color: #28a745;
        background-color: #f1f5f1;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }

    .cursor-pointer {
        cursor: pointer;
    }
</style>

@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
