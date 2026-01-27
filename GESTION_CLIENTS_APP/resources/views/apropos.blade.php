@extends('layouts.app')

@section('title', 'À Propos - Mekano Garage')

@section('content')
    <div class="container">
        <section class="apropos-section">
            <h1>À Propos de Mekano Garage</h1>
            <div class="apropos-content">
                <div class="apropos-text">
                    <p>Notre garage est un atelier automobile professionnel dédié à l'entretien, au diagnostic et à la réparation de véhicules toutes marques. Forts de plus de 10 années d'expérience, nous accompagnons nos clients avec sérieux et engagement, en proposant des solutions fiables et adaptées à chaque véhicule.</p>
                    <p>Nous plaçons la fiabilité de nos interventions, la rapidité de prise en charge et la transparence dans nos diagnostics et nos devis au cœur de notre travail. Chaque véhicule est traité avec le même niveau d'exigence, qu'il s'agisse d'un simple entretien ou d'une réparation complexe.</p>
                    <p>Notre équipe de techniciens qualifiés utilise des équipements modernes et des pièces de qualité pour garantir des résultats durables. Nous nous engageons à offrir un service client exceptionnel, en écoutant attentivement les besoins de nos clients et en fournissant des conseils honnêtes et professionnels.</p>
                    <p>Chez Mekano Garage, votre satisfaction est notre priorité. Nous sommes impatients de vous accueillir dans notre atelier et de prendre soin de votre véhicule avec le plus grand soin.</p>
                </div>
                <div class="apropos-image">
                    <img src="{{ asset('garage/atelier.png') }}" alt="Atelier Mekano Garage" height="400px">
                </div>
            </div>
        </section>
    </div>
@endsection
</html>