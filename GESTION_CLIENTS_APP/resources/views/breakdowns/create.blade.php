@extends('layouts.app')

@section('title', 'Déclarer une Panne')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-danger text-white py-3">
                    <h2 class="mb-0">
                        <i class="fas fa-tools"></i> Déclarer une Panne
                    </h2>
                    <small class="d-block mt-2 opacity-75">Remplissez tous les champs pour signaler votre problème</small>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-circle"></i> Erreurs de validation
                            </h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('breakdowns.store') }}" method="POST" id="breakdownForm">
                        @csrf

                        <!-- Véhicule Selection -->
                        <div class="mb-4">
                            <label for="vehicule_id" class="form-label fw-bold">
                                <i class="fas fa-car"></i> Véhicule concerné *
                            </label>
                            <select 
                                class="form-select form-select-lg @error('vehicule_id') is-invalid @enderror" 
                                id="vehicule_id" 
                                name="vehicule_id" 
                                required
                            >
                                <option value="">-- Sélectionner un véhicule --</option>
                                @forelse($vehicules as $vehicule)
                                    <option value="{{ $vehicule->id }}" @selected(old('vehicule_id') == $vehicule->id)>
                                        {{ $vehicule->marque }} {{ $vehicule->modele }} - {{ $vehicule->immatriculation }}
                                    </option>
                                @empty
                                    <option value="" disabled>Aucun véhicule disponible</option>
                                @endforelse
                            </select>
                            @error('vehicule_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-pen"></i> Description du problème *
                            </label>
                            <textarea 
                                class="form-control form-control-lg @error('description') is-invalid @enderror" 
                                id="description" 
                                name="description" 
                                rows="4"
                                placeholder="Décrivez les symptômes (ex: moteur qui ne démarre pas, bruit anormal, etc.)"
                                required
                            >{{ old('description') }}</textarea>
                            <small class="d-block mt-2 text-muted">Maximum 1000 caractères</small>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">
                                <i class="fas fa-phone"></i> Votre numéro de téléphone *
                            </label>
                            <input 
                                type="tel" 
                                class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                id="phone" 
                                name="phone" 
                                placeholder="+33 6 XX XX XX XX"
                                value="{{ old('phone') }}"
                                required
                            />
                            <small class="d-block mt-2 text-muted">Le technicien vous contactera à ce numéro</small>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <label for="location" class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt"></i> Lieu actuel *
                            </label>
                            <textarea 
                                class="form-control form-control-lg @error('location') is-invalid @enderror" 
                                id="location" 
                                name="location" 
                                rows="3"
                                placeholder="Ex: Avenue de la République, 75000 Paris"
                                required
                            >{{ old('location') }}</textarea>
                            <small class="d-block mt-2 text-muted">Où se trouve votre véhicule actuellement?</small>
                            @error('location')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Technician Needed -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block mb-3">
                                <i class="fas fa-user-tie"></i> Avez-vous besoin d'un dépannage sur place?
                            </label>
                            
                            <div class="btn-group w-100" role="group">
                                <input 
                                    type="radio" 
                                    class="btn-check" 
                                    name="needs_technician" 
                                    id="needsTechNo" 
                                    value="0"
                                    @checked(!old('needs_technician', false))
                                    onchange="toggleTechnicianSelector()"
                                />
                                <label class="btn btn-outline-secondary" for="needsTechNo">
                                    <i class="fas fa-times"></i> Non, conseil téléphonique
                                </label>

                                <input 
                                    type="radio" 
                                    class="btn-check" 
                                    name="needs_technician" 
                                    id="needsTechYes" 
                                    value="1"
                                    @checked(old('needs_technician', false))
                                    onchange="toggleTechnicianSelector()"
                                />
                                <label class="btn btn-outline-success" for="needsTechYes">
                                    <i class="fas fa-check"></i> Oui, dépannage sur place
                                </label>
                            </div>
                        </div>

                        <!-- Technician Selector (Hidden by default) -->
                        <div id="technicianContainer" class="mb-4" style="display: @checked(old('needs_technician', false)) ? 'block' : 'none';">
                            <div class="card card-body bg-light border-success">
                                <label for="technicianSearch" class="form-label fw-bold mb-3">
                                    <i class="fas fa-search"></i> Sélectionner un technicien
                                </label>

                                <!-- Search Input -->
                                <div class="mb-3">
                                    <input 
                                        type="text" 
                                        class="form-control form-control-lg" 
                                        id="technicianSearch" 
                                        placeholder="🔍 Chercher par nom ou spécialité..."
                                        onkeyup="filterTechniciens()"
                                    />
                                </div>

                                <!-- Technician Cards Grid -->
                                <div class="row g-2" id="technicianGrid">
                                    @forelse($techniciens as $tech)
                                        <div class="col-md-6 mb-2 technicien-item" data-name="{{ strtolower($tech->nom . ' ' . $tech->prenom) }}" data-specialite="{{ strtolower($tech->specialite ?? '') }}">
                                            <div class="form-check card card-body p-2 cursor-pointer tech-card" onclick="selectTechnicien({{ $tech->id }}, this)">
                                                <input 
                                                    type="radio" 
                                                    class="form-check-input" 
                                                    name="technicien_id" 
                                                    id="tech_{{ $tech->id }}" 
                                                    value="{{ $tech->id }}"
                                                    @checked(old('technicien_id') == $tech->id)
                                                />
                                                <label class="form-check-label ms-2 flex-grow-1 cursor-pointer mb-0" for="tech_{{ $tech->id }}">
                                                    <div class="d-flex align-items-center">
                                                        @if($tech->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($tech->image))
                                                            <img src="{{ asset('storage/' . $tech->image) }}" alt="{{ $tech->nom }}" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-user-circle"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <strong>{{ $tech->nom }} {{ $tech->prenom }}</strong><br/>
                                                            <small class="text-muted">{{ $tech->specialite ?? 'Généraliste' }}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i> Aucun technicien disponible
                                            </div>
                                        </div>
                                    @endforelse
                                </div>

                                <div id="noResultsMessage" style="display: none;" class="alert alert-warning">
                                    <i class="fas fa-search"></i> Aucun technicien ne correspond à votre recherche
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-between mt-5">
                            <a href="{{ route('breakdowns.index') }}" class="btn btn-lg btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-lg btn-danger">
                                <i class="fas fa-paper-plane"></i> Signaler la panne
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tech-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent !important;
    }

    .tech-card:hover {
        border-color: #198754 !important;
        background-color: #f0f9f6 !important;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15) !important;
    }

    .form-check-input:checked + .form-check-label .tech-card,
    .tech-card.selected {
        border-color: #198754 !important;
        background-color: #e7f5f0 !important;
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.25) !important;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .hidden {
        display: none !important;
    }
</style>

<script>
function toggleTechnicianSelector() {
    const needsTech = document.getElementById('needsTechYes').checked;
    const container = document.getElementById('technicianContainer');
    const technicienInput = document.querySelector('input[name="technicien_id"]');
    
    if (needsTech) {
        container.style.display = 'block';
        // Make technicien_id required if visible
        document.querySelectorAll('input[name="technicien_id"]').forEach(input => {
            input.required = true;
        });
    } else {
        container.style.display = 'none';
        // Make technicien_id not required if hidden
        document.querySelectorAll('input[name="technicien_id"]').forEach(input => {
            input.required = false;
            input.checked = false;
        });
    }
}

function filterTechniciens() {
    const searchTerm = document.getElementById('technicianSearch').value.toLowerCase();
    const items = document.querySelectorAll('.technicien-item');
    let visibleCount = 0;

    items.forEach(item => {
        const name = item.getAttribute('data-name');
        const specialite = item.getAttribute('data-specialite');
        
        if (name.includes(searchTerm) || specialite.includes(searchTerm)) {
            item.classList.remove('hidden');
            item.style.display = '';
            visibleCount++;
        } else {
            item.classList.add('hidden');
            item.style.display = 'none';
        }
    });

    // Show or hide "no results" message
    const noResults = document.getElementById('noResultsMessage');
    noResults.style.display = visibleCount === 0 && searchTerm !== '' ? 'block' : 'none';
}

function selectTechnicien(technicienId, card) {
    // Remove selection from all cards
    document.querySelectorAll('.tech-card').forEach(c => {
        c.classList.remove('selected');
    });
    
    // Add selection to clicked card
    card.classList.add('selected');
    
    // Check the corresponding radio button
    document.getElementById('tech_' + technicienId).checked = true;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if technician selector should be visible
    const needsTech = document.getElementById('needsTechYes').checked;
    if (!needsTech) {
        document.getElementById('technicianContainer').style.display = 'none';
    }
    
    // Highlight selected technician on load
    const selectedRadio = document.querySelector('input[name="technicien_id"]:checked');
    if (selectedRadio) {
        const card = selectedRadio.closest('.tech-card');
        if (card) card.classList.add('selected');
    }
});
</script>
@endsection
