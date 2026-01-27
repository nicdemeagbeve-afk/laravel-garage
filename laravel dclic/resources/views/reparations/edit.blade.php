@extends('layouts.app')

@section('title', 'Modification de reparation - Mekano Garage')

@section('extra_css')
    <h1>Modifier une Réparation</h1>
    
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('reparations.update', $reparation->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="vehicule_id">Véhicule:</label>
        <select id="vehicule_id" name="vehicule_id" required>
            <option value="">-- Sélectionner un véhicule --</option>
            @foreach ($vehicules as $vehicule)
                <option value="{{ $vehicule->id }}" {{ $reparation->vehicule_id == $vehicule->id ? 'selected' : '' }}>{{ $vehicule->immatriculation }} - {{ $vehicule->marque }} {{ $vehicule->modele }}</option>
            @endforeach
        </select><br><br>

        <label for="technicien_id">Technicien:</label>
        <select id="technicien_id" name="technicien_id">
            <option value="">-- Sélectionner un technicien --</option>
            @foreach ($techniciens as $technicien)
                <option value="{{ $technicien->id }}" {{ $reparation->technicien_id == $technicien->id ? 'selected' : '' }}>{{ $technicien->prenom }} {{ $technicien->nom }}</option>
            @endforeach
        </select><br><br>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="{{ $reparation->date }}" required><br><br>

        <label for="duree_main_oeuvre">Durée Main d'Œuvre (minutes):</label>
        <input type="number" id="duree_main_oeuvre" name="duree_main_oeuvre" value="{{ $reparation->duree_main_oeuvre }}" min="0"><br><br>

        <label for="objet_reparation">Objet de la Réparation:</label>
        <textarea id="objet_reparation" name="objet_reparation" required>{{ $reparation->objet_reparation }}</textarea><br><br>

        <button type="submit">Mettre à jour Réparation</button>
        <a href="{{ route('reparations.index') }}">Annuler</a>
    </form>
</body>
</html>