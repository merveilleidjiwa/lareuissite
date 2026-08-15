<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        // To be implemented
        return redirect()->back();
    }

    public function registerForm()
    {
        return view('client.auth.register');
    }

    public function register(Request $request)
    {
        // To be implemented
        return redirect()->back();
    }

    public function registerLivreurForm()
    {
        return view('client.auth.register_livreur');
    }

    public function registerLivreur(Request $request)
    {
        // To be implemented
        return redirect()->back();
    }

    public function logout()
    {
        // To be implemented
        return redirect()->route('index');
    }
}
