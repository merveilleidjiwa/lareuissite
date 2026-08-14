@extends('admin.layouts.master')

@section('title', 'Tableau de bord')

@section('content')
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-primary shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Produits</h5>
                <p class="card-text display-4">{{ $stats['produits'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-success shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Commandes</h5>
                <p class="card-text display-4">{{ $stats['commandes'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-warning shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Clients</h5>
                <p class="card-text display-4">{{ $stats['clients'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
