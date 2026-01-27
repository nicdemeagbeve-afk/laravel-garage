@extends('layouts.app')

@section('title', 'Contact - Mekano Garage')

@section('content')
<section id="contact">
    <div class="cont-msg">
        <div class="hero-content">
            <h1>Contactez-Nous</h1>
            <p>Nous sommes là pour vous aider. N'hésitez pas à nous envoyer un message.</p>
            <p>
                Adresse: Agoe Telessou, Togo Lomé<br>
                Téléphone: <a href="tel:+228 70 83 24 82">+228 70 83 24 82</a><br>
                Email: <a href="mailto:contact@mekano.com">contact@mekano.com</a><br>
                
            </p>
        </div>
    </div>
    <div class="container" id="cont" style="align-items: right;">
        <form action="{{ route('contact.submit') }}" method="post">
                @csrf
                <label for="name">Nom:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>

                <br><button type="submit">Envoyer</button>
        </form>
    </div>
        
</section>
@endsection