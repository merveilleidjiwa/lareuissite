<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function produits()
    {
        return view('admin.produits.index');
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
