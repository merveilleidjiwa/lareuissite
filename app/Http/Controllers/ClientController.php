<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function apropos()
    {
        return view('client.apropos');
    }

    public function contact()
    {
        return view('client.contact');
    }
}
