<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'produits' => Produit::count(),
            'commandes' => Commande::count(),
            'clients' => User::count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}
