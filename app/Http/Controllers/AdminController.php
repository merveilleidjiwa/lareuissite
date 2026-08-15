<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $is_connected = true;
        return view('admin.dashboard', ['is_connected' => $is_connected]);
    }

    public function produits(Request $request)
    {
        // Gérer la suppression de produit
        if ($request->has('supprimer_produit')) {
            $id = $request->input('id_produit');
            $produit = \Illuminate\Support\Facades\DB::table('produits')->where('id', $id)->first();
            if ($produit && $produit->image && file_exists(public_path($produit->image))) {
                @unlink(public_path($produit->image));
            }
            \Illuminate\Support\Facades\DB::table('produits')->where('id', $id)->delete();
            return redirect()->route('admin.produits.index')->with('success', 'Produit supprimé !');
        }

        // Gérer l'ajout de produit
        if ($request->has('ajouter_produit')) {
            $nom = $request->input('nom');
            $description = $request->input('description');
            $categorie = $request->input('categorie');
            
            $sous_categorie = null;
            $tarifs_json = null;
            $prix_final = (int)$request->input('prix_standard'); 

            if ($categorie === 'Viandes et Poissons') {
                $sous_categorie = $request->input('sous_categorie');
                if ($sous_categorie === 'viande') {
                    $data_tarifs = [
                        'male' => (int)$request->input('prix_male'),
                        'femelle' => (int)$request->input('prix_femelle'),
                        'abattu' => (int)$request->input('prix_abattu')
                    ];
                    $tarifs_json = json_encode($data_tarifs);
                    $prix_final = (int)$request->input('prix_abattu'); 
                }
            }

            $image_path = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                $image_path = 'uploads/' . $filename;
            }

            \Illuminate\Support\Facades\DB::table('produits')->insert([
                'nom' => $nom,
                'description' => $description,
                'prix' => $prix_final,
                'image' => $image_path,
                'categorie' => $categorie,
                'tarifs' => $tarifs_json,
                'sous_categorie' => $sous_categorie,
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->route('admin.produits.index')->with('success', 'Produit ajouté avec succès !');
        }

        // Récupérer les produits pour l'affichage
        $produits = \Illuminate\Support\Facades\DB::table('produits')->get()->map(function($item) {
            return (array)$item;
        })->toArray();

        return view('admin.produits.index', compact('produits'));
    }

    public function promos()
    {
        return view('admin.promos.index');
    }

    public function stats()
    {
        return view('admin.stats');
    }

    public function addAdmin()
    {
        return view('admin.utilisateurs.create');
    }
}
