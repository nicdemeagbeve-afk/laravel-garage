@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h4>🔧 Créer une Réparation</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('reparations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Image -->
                <div class="mb-3">
                    <label for="image" class="form-label fw-bold">📸 Photo de la Réparation</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*">
                    @error('image')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                    <div id="imagePreview" class="mt-3" style="display:none;">
                        <img id="previewImg" src="" alt="Aperçu" class="img-thumbnail" style="max-width: 300px;">
                    </div>
                </div>

                <!-- Véhicule -->
                <div class="mb-3">
                    <label for="vehicule_id" class="form-label fw-bold">Véhicule</label>
                    <select class="form-control @error('vehicule_id') is-invalid @enderror" 
                            id="vehicule_id" name="vehicule_id" required>
                        <option value="">-- Sélectionner un véhicule --</option>
                        @foreach($vehicules as $vehicule)
                            <option value="{{ $vehicule->id }}" {{ old('vehicule_id') == $vehicule->id ? 'selected' : '' }}>
                                {{ $vehicule->marque }} {{ $vehicule->modele }} - {{ $vehicule->immatriculation }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Technicien -->
                <div class="mb-3">
                    <label for="technicien_id" class="form-label fw-bold">Technicien</label>
                    <select class="form-control" id="technicien_id" name="technicien_id">
                        <option value="">-- Aucun --</option>
                        @foreach($techniciens as $tech)
                            <option value="{{ $tech->id }}" {{ old('technicien_id') == $tech->id ? 'selected' : '' }}>
                                {{ $tech->nom }} {{ $tech->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div class="mb-3">
                    <label for="date" class="form-label fw-bold">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
                </div>

                <!-- Objet Réparation -->
                <div class="mb-3">
                    <label for="objet_reparation" class="form-label fw-bold">Description</label>
                    <textarea class="form-control" id="objet_reparation" name="objet_reparation" required></textarea>
                </div>

                <!-- Durée -->
                <div class="mb-3">
                    <label for="duree_main_oeuvre" class="form-label fw-bold">Durée (heures)</label>
                    <input type="number" class="form-control" id="duree_main_oeuvre" name="duree_main_oeuvre" min="0">
                </div>

                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Créer</button>
                <a href="{{ route('reparations.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(event) {
            previewImg.src = event.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endsection
