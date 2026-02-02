@extends('layouts.app')

@section('title', 'Dashboard Admin - Garage')

@section('content')

<div class="container mx-auto px-4 py-8">
    @if ($message = session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ $message }}
        </div>
    @endif

    @if ($message = session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ $message }}
        </div>
    @endif

    <h1 class="hm text-3xl font-bold mb-8"> Dashboard Admin</h1>
    <!-- Statistiques -->
    <div class="statis grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-100 p-6 rounded-lg" href="{{ route('breakdowns.index') }}">
            <p class="text-gray-600">Pannes en Attente</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['breakdowns_pending'] ?? 0 }}</p>
        </div>
        <div class="bg-yellow-100 p-6 rounded-lg" href="{{ route('breakdowns.index') }}">
            <p class="text-gray-600">Pannes En Cours</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['breakdowns_in_progress'] ?? 0 }}</p>
        </div>
        <div class="bg-green-100 p-6 rounded-lg" href="{{ route('breakdowns.index') }}">
            <p class="text-gray-600">Pannes Résolues</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['breakdowns_resolved'] ?? 0 }}</p>
        </div>
        <div class="bg-purple-100 p-6 rounded-lg" href="{{ route('users.index') }}">
            <p class="text-gray-600">Utilisateurs Actifs</p>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['users_total'] ?? 0 }}</p>
        </div>
        <div class="bg-red-100 p-6 rounded-lg" href="{{ route('users.index') }}">
            <p class="text-gray-600">Comptes en Attente</p>
            <p class="text-3xl font-bold text-red-600">{{ $stats['users_inactive'] ?? 0 }}</p>
        </div>
        <div class="bg-indigo-100 p-6 rounded-lg" >
            <p href="{{ route('reparations.index') }}"; class="text-gray-600">Réparations</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['reparations_total'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Comptes en attente d'approbation -->
    <div class="approve bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
        <h2 class="text-xl font-bold mb-4"> Comptes en Attente d'Approbation</h2>
        <a href=href="{{ route('users.create') }}"><button class="bg-green-500 text-white px-3 py-1 rounded"  >Ajoutez un Nouvel Utilisateurs</button></a>
        <table class="w-full">
            <thead>
                <tr class="bg-yellow-100">
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Rôle</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pending_users as $user)
                <tr class="border-b hover:bg-yellow-100">
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->role }}</td>
                    <td class="px-4 py-2">
                        <form action="{{ route('admin.approve-user', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">
                                 Approuver
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="tablo-lier">
     <!-- Pannes récentes -->
        <div class="panne mb-8">
            <h2 class="text-2xl font-bold mb-4">📋 Pannes Récentes</h2>
            <a href="{{ route('breakdowns.create') }}" ><button class="bg-green-500 text-white px-3 py-1 rounded ">Ajout de Pannes</button> </a>
            <table class="w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Client</th>
                        <th class="px-4 py-2 text-left">Véhicule</th>
                        <th class="px-4 py-2 text-left">Statut</th>
                        <th class="px-4 py-2 text-left">Créée</th>
                        <th class="px-4 py-2 text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(isset($breakdowns) ? $breakdowns : [] as $breakdown)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">#{{ $breakdown->id }}</td>
                        <td class="px-4 py-2">{{ $breakdown->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $breakdown->vehicule->immatriculation ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $breakdown->status }}</td>
                        <td class="px-4 py-2">{{ $breakdown->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('breakdowns.show', $breakdown) }}" class="button-view">Voir</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">Aucune panne</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    <!-- Réparations récentes (nouvelle section ajoutée/déplacée) -->
        <div class="reparation mb-8">
            <h2 class="text-2xl font-bold mb-4">🔧 Réparations Récentes</h2>
            <a href="{{ route('reparations.create') }}" ><button class="bg-green-500 text-white px-3 py-1 rounded">Ajout de Réparations</button></a>
            <table class="w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Objet</th>
                        <th class="px-4 py-2 text-left">Durée</th>
                        <th class="px-4 py-2 text-left">Créée</th>
                        <th class="px-4 py-2 text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Assurez-vous que $reparations est passé par le contrôleur --}}
                    @forelse($reparations as $reparation)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $reparation->date }}</td>
                            <td class="px-4 py-2">{{ $reparation->objet_reparation }}</td>
                            <td class="px-4 py-2">{{ $reparation->duree_main_oeuvre }}</td>
                            <td class="px-4 py-2">{{ $reparation->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                {{-- Assurez-vous que la route 'reparations.show' ou similaire existe --}}
                                <a href="{{ route('reparations.show', $reparation) }}" class="button-view">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500">Aucune réparation récente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="text-right mt-4">
                <a href="{{ route('reparations.index') }}" class="bg-green-500 text-white px-3 py-1 rounded ">Voir toutes les Réparations</a>
            </div>
        </div>
    </div>
   


<!-- Actions Admin 
    <div class="bg-gray-100 p-6 rounded-lg">
        <h2 class="text-2xl font-bold mb-4">⚙️ Actions</h2>
        <div class="space-y-2">
            <a href="{{ route('admin.users.index') }}" class="block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                👥 Gérer Utilisateurs
            </a>
            <a href="{{ route('admin.export-data', ['type' => 'breakdowns']) }}" class="block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                📥 Exporter Pannes (CSV)
            </a>
            {{-- Ajoutez d'autres liens d'action si nécessaire --}}
        </div>
    </div> 
-->

    <!--Liste des tecnicien les plus demandez-->
    <div class="star">
        <h2 class=" text-2xl font-bold mb-4 mt-8">🛠️ Techniciens les Plus Demandés</h2>
        <a href="{{ route('techniciens.create') }}" > <button class="bg-green-500 text-white px-3 py-1 rounded "> Ajout de Techniciens </button></a>
        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Technicien</th>
                    <th class="px-4 py-2 text-left">Nombre de Pannes Assignées</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($top_technicians as $technician)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $technician->nom }}</td>
                    <td class="px-4 py-2">{{ $technician->breakdown_count }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('techniciens.show', $technician) }}" class="button-view">Voir</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-4 py-2 text-center text-gray-500">Aucun technicien disponible.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection