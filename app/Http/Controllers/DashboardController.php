<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function profil()
    {
        return view('client.dashboard.profil');
    }

    public function livreur()
    {
        return view('client.dashboard.livreur');
    }

    public function vendeur()
    {
        return view('client.dashboard.vendeur');
    }

    public function vip()
    {
        return view('client.dashboard.vip');
    }
}
