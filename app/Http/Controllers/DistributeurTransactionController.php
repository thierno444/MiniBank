<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DistributeurTransactionController extends Controller
{
    public function index()
    {
        return view('distributeur_transactions'); // Affiche la vue
    }
}