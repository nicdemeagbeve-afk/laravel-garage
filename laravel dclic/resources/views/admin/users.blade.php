@extends('layouts.app')

@section('title', 'Dashboard Admin - Garage')

@section('content')

<div class="tablo">
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->is_active ? 'Actif' : 'Inactif' }}</td>
                <td>
                    @if(!$user->is_active)
                    <form action="{{ route('admin.approve-user', $user) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">Approuver</button>
                    </form>
                    @endif
                    <form action="{{ route('admin.users.deactivate', $user) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection