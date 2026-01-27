@extends('layouts.app')

@section('title', 'Véhicules - Mekano Garage')

@section('extra_css')
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        #imagePreview {
            max-width: 200px;
            margin-top: 15px;
            display: none;
        }
        #imagePreview img {
            max-width: 100%;
            border-radius: 8px;
        }
        .file-info {
            font-size: 0.85rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1 class="mb-4">Créer un nouveau service</h1>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erreur!</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data" novalidate id="serviceForm">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nom du service <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" autocomplete="off" required>
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" autocomplete="off">{{ old('description') }}</textarea>
                    @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Prix (€) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" step="0.01" value="{{ old('price') }}" autocomplete="off" required>
                    @error('price')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label for="images" class="form-label">Image</label>
                    <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images" accept=".jpg,.jpeg,.png,.webp" autocomplete="off">
                    @error('images')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    <small class="text-muted d-block mt-2">
                        ✓ Formats acceptés: JPEG, PNG, WebP<br>
                        ✓ Taille maximale: 7MB<br>
                        ℹ️ Remarque: Assurez-vous que la taille du fichier ne dépasse pas 2MB si vous rencontrez des problèmes
                    </small>
                    <div id="imagePreview">
                        <p class="mb-2"><strong>Prévisualisation:</strong></p>
                        <img id="previewImg" src="" alt="Prévisualisation">
                        <div class="file-info" id="fileInfo"></div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Créer le service</button>
                    <a href="{{ route('services.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        const imageInput = document.getElementById('images');
        const previewDiv = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const fileInfo = document.getElementById('fileInfo');
        const submitBtn = document.getElementById('submitBtn');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Vérifier le type de fichier
                const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('❌ Format invalide! Acceptés: JPEG, PNG, WebP');
                    imageInput.value = '';
                    previewDiv.style.display = 'none';
                    return;
                }

                // Vérifier la taille (max 7MB = 7340032 bytes)
                const maxSize = 7 * 1024 * 1024; // 7MB
                if (file.size > maxSize) {
                    alert(`❌ Fichier trop volumineux! (${(file.size / 1024 / 1024).toFixed(2)}MB)\nTaille maximale: 7MB`);
                    imageInput.value = '';
                    previewDiv.style.display = 'none';
                    return;
                }

                // Afficher la prévisualisation
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewDiv.style.display = 'block';
                    
                    const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                    fileInfo.textContent = `📄 ${file.name} | Taille: ${sizeMB}MB`;
                };
                reader.readAsDataURL(file);
            } else {
                previewDiv.style.display = 'none';
            }
        });

        // Empêcher la soumission si la taille est trop grande
        document.getElementById('serviceForm').addEventListener('submit', function(e) {
            const file = imageInput.files[0];
            if (file) {
                const maxSize = 2 * 1024 * 1024; // 2MB pour plus de compatibilité avec PHP
                if (file.size > maxSize) {
                    e.preventDefault();
                    alert(`⚠️ Attention: Le fichier dépasse 2MB (${(file.size / 1024 / 1024).toFixed(2)}MB).\n\nVotre serveur PHP peut avoir une limite d'upload inférieure à 7MB.\n\nVeuillez utiliser une image plus petite.`);
                }
            }
        });
    </script>
</body>
@endsection