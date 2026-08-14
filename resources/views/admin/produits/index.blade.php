@extends('admin.layouts.master')

@section('title', 'Liste des Produits')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.produits.create') }}" class="btn btn-primary">Ajouter un produit</a>
</div>

<div class="table-responsive shadow-sm bg-white rounded">
    <table class="table table-striped table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produits as $produit)
            <tr>
                <td>{{ $produit->id }}</td>
                <td>
                    @if($produit->image)
                        <img src="{{ asset('storage/' . $produit->image) }}" alt="img" width="50" class="rounded">
                    @else
                        <span class="badge bg-secondary">Aucune</span>
                    @endif
                </td>
                <td>{{ $produit->name }}</td>
                <td>{{ $produit->category ? $produit->category->name : '-' }}</td>
                <td>{{ number_format($produit->price, 2) }} €</td>
                <td>{{ $produit->stock }}</td>
                <td>
                    @if($produit->is_active)
                        <span class="badge bg-success">Actif</span>
                    @else
                        <span class="badge bg-danger">Inactif</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Aucun produit trouvé.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
