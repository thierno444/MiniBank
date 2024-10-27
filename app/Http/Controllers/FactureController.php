<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class FactureController extends Controller
{
    public function show($id)
    {
        // Récupérer la transaction par ID
        $transaction = Transaction::findOrFail($id);

        // Retourner la vue avec les détails de la transaction
        return view('facture.show', compact('transaction'));
    }
}