@extends('layouts.app')

@section('title', 'Dashboard Admin - Garage')

@section('content')
 <!--page de profil ou s'affiche les informations de l'utilisateur connectez , il peut modifiez et ajoutez que ce soit l'admin, le client etc... ne peut modifiez role, juste ses infos personnelle-->
<div>
    <h1 class="text-2xl font-bold mb-4">Profil de l'Administrateur</h1>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form method="POST" action="{{ route('admin.update-profile') }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Nom
                </label>
                <input id="name" type="text" name="name" value="{{ Auth::user()->name }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input id="email" type="email" name="email" value="{{ Auth::user()->email }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Mettre à Jour le Profil
                </button>
</div>

@endsection