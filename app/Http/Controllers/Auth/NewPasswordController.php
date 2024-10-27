<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use App\Models\User;
use Illuminate\View\View; // Ajout de cette ligne

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'telephone' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        // Vérifie si l'utilisateur existe avec le numéro de téléphone
        $user = User::where('telephone', $request->telephone)->first();
    
        if (!$user) {
            return back()->withErrors(['telephone' => 'Numéro de téléphone non valide.']);
        }
    
        // Ici, nous utilisons un token fixe, par exemple "fixed_token"
        $fixedToken = 'fixed_token';
    
        if ($request->token !== $fixedToken) {
            return back()->withErrors(['token' => 'Token non valide.']);
        }
    
        // Réinitialiser le mot de passe
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();
    
        // Événement de réinitialisation
        event(new PasswordReset($user));
    
        return redirect()->route('login')->with('status', 'Mot de passe réinitialisé avec succès.');
    }
    
    
public function showResetForm(Request $request, $token): View
{
    return view('auth.reset-password', ['token' => $token, 'telephone' => $request->telephone]);
}

}
