@extends('layouts.app')

@section('title', 'Accueil - Mekano Garage')

@section('content')
    <div class="container">
        <section class="hero">
            <div>
                <h1>Mekano Garage</h1>
                <p>Réparation, entretien et diagnostic de véhicules toutes marques</p>
                <p>Votre Satisfaction, Notre Prioritée</p>
            </div>
            <div class="button">
                <a href="{{ route('breakdowns.create') }}"><button>Prendre Rendez-vous</button></a>
                <a href="{{ route('contact') }}"><button>Contactez</button></a>
            </div>
        </section>
        <section class="detail">
            <h2>Garage de l'excellence</h2>
            <div class="cont-detail">
                <div class="first">
                
                <p>Notre garage est un atelier automobile professionnel dédié à l'entretien, au diagnostic et à la réparation de véhicules toutes marques. Forts de plus de 10 années d'expérience, nous accompagnons nos clients avec sérieux et engagement, en proposant des solutions fiables et adaptées à chaque véhicule.</p>
                <p>Nous plaçons la fiabilité de nos interventions, la rapidité de prise en charge et la transparence dans nos diagnostics et nos devis au cœur de notre travail. Chaque véhicule est traité avec le même niveau d'exigence, qu'il s'agisse d'un simple entretien ou d'une réparation complexe.</p>
            </div>
            <div>
                <img src="{{ asset('garage/atelier.png') }}" alt="" height="400px">
            </div>
            </div>
            
        </section>

        <section class="service">
            <h1>Nos Services</h1>
            
                
                <div class="cont-service">
                    
                         @forelse($services as $service)
                        <div class="service-box">
                            @if($service->images)
                                <img src="{{ asset('storage/' . $service->images) }}" alt="{{ $service->name }}"  style="object-fit: cover; width: 100%; height: 100px;">
                            @else
                                <div style="height: 100px; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #999;">Pas d'image</span>
                                </div>
                            @endif
                            <h3>{{ $service->name }}</h3>
                            <p>{{ Str::limit($service->description, 80) }}</p>
                            <p style="font-weight: bold; color: #2563eb;">{{ number_format($service->price, 2, ',', ' ') }} FCFA</p>
                        <a href="{{ route('services.index') }}"><button>Voir tous les services</button></a>
                        </div>
                    @empty
                        <div class="service-box">
                            <p>Aucun service disponible pour le moment.</p>
                        <a href="{{ route('services.index') }}"><button>Voir tous les services</button></a>
                        </div>
                    @endforelse
                    
                   
                </div>
            
        </section>

        <section class="faq">
            <h1>Pourquoi Nous Choisir?</h1>
            <div class="cont-faq"> 
                <div>
                    <h3>Expertise et Expérience :</h3>
                    <p>Notre équipe de techniciens qualifiés possède une vaste expérience dans la réparation et l'entretien de véhicules de toutes marques.</p>
                </div>
                <div>
                    <h3>Transparence et Confiance :</h3>
                    <p>Nous fournissons des diagnostics clairs et des devis détaillés avant toute intervention, assurant ainsi une totale transparence.</p>
                </div>
                <div>
                    <h3>Service Rapide et Efficace :</h3>
                    <p>Nous comprenons l'importance de votre temps. C'est pourquoi nous nous efforçons de fournir des services rapides sans compromettre la qualité.</p>
                </div>
                <div>
                    <h3>Garantie de Qualité :</h3>
                    <p>Toutes nos réparations sont garanties, vous offrant ainsi une tranquillité d'esprit supplémentaire.</p>
                </div>
                <div>
                    <h3>Service Client Exceptionnel :</h3>
                    <p>Votre satisfaction est notre priorité. Nous nous engageons à offrir un service client exceptionnel à chaque étape de votre expérience avec nous.</p>
                </div>
            </div>
        </section>
        <section class="testimoniale">
            <h1>Témoignages de Nos Clients</h1>
            <div class="cont-testimoniale">
                <div class="box-testimoniale">
                    <p>"Service exceptionnel! Mon véhicule a été réparé rapidement et à un prix raisonnable. Je recommande vivement ce garage."</p>
                    <h4><img src={{asset('garage/1.jpeg')}} alt="" width="50px"> Jean Dupont</h4>
                </div>
                <div class="box-testimoniale">
                    <p>"L'équipe est très professionnelle et compétente. Ils ont su diagnostiquer le problème de ma voiture en un rien de temps."</p>
                    <h4><img src={{asset('garage/atelier.png')}} alt="" width="50px"> Marie Curie</h4>
                </div>
                <div class="box-testimoniale">
                    <p>"Je suis très satisfait de la qualité du service. Mon véhicule fonctionne parfaitement après l'entretien."</p>
                    <h4><img src={{asset('garage/3.png')}} alt="" width="50px"> Paul Martin</h4>
                </div>
            </div>
        </section>
        <section class="cta">
            <h2>Prêt à Confier Votre Véhicule à des Experts?</h2>
            <p>Contactez-nous dès aujourd'hui pour prendre rendez-vous ou obtenir un devis gratuit.</p>
            <a href="{{ route('contact') }}"><button>Contactez-Nous</button></a>
        </section>
    </main>
        </div>

@endsection
