<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str; // Assurez-vous que cette ligne est présente
use Illuminate\Validation\Rules;
use App\Models\User;
use Illuminate\View\View;
class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password'); // La vue du formulaire
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'telephone' => ['required', 'string'],
        ]);
    
        $user = User::where('telephone', $request->telephone)->first();
    
        if (!$user) {
            return back()->withErrors(['telephone' => 'Aucun utilisateur trouvé avec ce numéro.']);
        }
    
        // Simule l'envoi du lien de réinitialisation et stocke le numéro de téléphone
        $token = Str::random(60); // Génère un token fictif
        session()->flash('token', $token);
        session()->flash('telephone', $request->telephone);
    
        // Ici, génère le lien (normalement, tu l'enverrais par SMS ou e-mail)
        return back()->with('status', 'Un lien de réinitialisation a été envoyé.');
    }
    
}
