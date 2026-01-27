@extends('layouts.app')

@section('title', 'Ajouter un Véhicule - Mekano Garage')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>Ajouter un Nouveau Véhicule</h2>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Erreurs lors de la validation:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('vehicules.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Image du Véhicule -->
                <div class="form-group">
                    <label for="image" class="fw-bold">Photo du Véhicule</label>
                    <input type="file" id="image" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                    <small class="text-muted">Formats acceptés: JPEG, PNG, JPG, GIF (Max 2MB)</small>
                    @error('image')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                    
                    <!-- Aperçu de l'image -->
                    <div id="imagePreview" class="image-preview-container">
                        <img id="previewImg" src="" alt="Aperçu" class="img-thumbnail">
                    </div>
                </div>

                <!-- Immatriculation -->
                <div class="form-group">
                    <label for="immatriculation" class="fw-bold">Immatriculation</label>
                    <input type="text" id="immatriculation" name="immatriculation" class="form-control @error('immatriculation') is-invalid @enderror" 
                           value="{{ old('immatriculation') }}" required>
                    @error('immatriculation')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="marque" class="fw-bold">Marque</label>
                        <input type="text" id="marque" name="marque" class="form-control @error('marque') is-invalid @enderror" 
                               value="{{ old('marque') }}" required>
                        @error('marque')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="modele" class="fw-bold">Modèle</label>
                        <input type="text" id="modele" name="modele" class="form-control @error('modele') is-invalid @enderror" 
                               value="{{ old('modele') }}" required>
                        @error('modele')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="annee" class="fw-bold">Année</label>
                        <input type="number" id="annee" name="annee" min="1900" max="2099" class="form-control" 
                               value="{{ old('annee') }}">
                    </div>
                    <div class="form-group">
                        <label for="couleur" class="fw-bold">Couleur</label>
                        <input type="text" id="couleur" name="couleur" class="form-control" 
                               value="{{ old('couleur') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kilometrage" class="fw-bold">Kilométrage</label>
                        <input type="number" id="kilometrage" name="kilometrage" min="0" class="form-control" 
                               value="{{ old('kilometrage') }}">
                    </div>
                    <div class="form-group">
                        <label for="carrosserie" class="fw-bold">Carrosserie</label>
                        <input type="text" id="carrosserie" name="carrosserie" class="form-control" 
                               value="{{ old('carrosserie') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="energie" class="fw-bold">Énergie</label>
                        <select id="energie" name="energie" class="form-control">
                            <option value="">Sélectionner...</option>
                            <option value="Essence" @if(old('energie') == 'Essence') selected @endif>Essence</option>
                            <option value="Diesel" @if(old('energie') == 'Diesel') selected @endif>Diesel</option>
                            <option value="Électrique" @if(old('energie') == 'Électrique') selected @endif>Électrique</option>
                            <option value="Hybride" @if(old('energie') == 'Hybride') selected @endif>Hybride</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="boite" class="fw-bold">Boîte de Vitesses</label>
                        <select id="boite" name="boite" class="form-control">
                            <option value="">Sélectionner...</option>
                            <option value="Manuel" @if(old('boite') == 'Manuel') selected @endif>Manuel</option>
                            <option value="Automatique" @if(old('boite') == 'Automatique') selected @endif>Automatique</option>
                            <option value="CVT" @if(old('boite') == 'CVT') selected @endif>CVT</option>
                        </select>
                    </div>
                </div>

                <div class="btn-group mt-20">
                    <button type="submit" class="btn btn-success btn-lg">Enregistrer</button>
                    <a href="{{ route('vehicules.index') }}" class="btn btn-secondary btn-lg">Annuler</a>
                </div>
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
            preview.classList.add('show');
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endsection
