<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
    {
        return view('client.produits.index');
    }

    public function promos()
    {
        return view('client.promos.index');
    }
}
