<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Categorie;

class AdminProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with('category')->latest()->get();
        return view('admin.produits.index', compact('produits'));
    }

    public function create()
    {
        $categories = Categorie::all();
        // Create a default category if none exists to avoid errors on the form
        if ($categories->isEmpty()) {
            $categories = collect([Categorie::create(['name' => 'Général', 'slug' => 'general'])]);
        }
        return view('admin.produits.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['slug'] = \Str::slug($request->name) . '-' . time();
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/produits');
            $data['image'] = str_replace('public/', '', $path);
        }

        Produit::create($data);

        return redirect()->route('admin.produits.index')->with('success', 'Produit ajouté avec succès !');
    }
}
