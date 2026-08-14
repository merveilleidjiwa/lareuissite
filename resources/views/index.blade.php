@extends('client.layouts.master')

@section('title', 'Accueil')

@section('content')
<div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="display-4 fw-bold text-success mb-3">La reuisite agronomique</h1>
        <p class="lead text-muted">Votre partenaire de confiance pour des produits agricoles de qualité supérieure. Nous mettons l'innovation au service de la terre.</p>
        <a href="/produits" class="btn btn-success btn-lg px-4 me-md-2 mt-3 shadow-sm" style="transition: transform 0.3s; onmouseover='this.style.transform=\'scale(1.05)\''; onmouseout='this.style.transform=\'scale(1)\''">
            Voir nos produits
        </a>
    </div>
    <div class="col-md-6 mt-4 mt-md-0 text-center">
        <!-- We use an image from the old project if available, otherwise a placeholder, but the prompt says no placeholders -->
        <img src="{{ asset('frontend/images/hero1.jpg') }}" alt="Agricole" class="img-fluid rounded shadow-lg" style="max-height: 400px; object-fit: cover;">
    </div>
</div>
@endsection
