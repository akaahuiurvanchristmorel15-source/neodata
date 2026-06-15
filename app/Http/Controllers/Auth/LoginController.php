<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function showLoginForm()
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers le dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion de l'utilisateur.
     */
    public function login(Request $request)
    {
        // 1. Validation stricte des intrants
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Récupération des identifiants (email et password)
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // 2. Tentative de connexion via le Guard de Laravel
        if (Auth::attempt($credentials, $remember)) {
            // Régénérer la session pour éviter les attaques par fixation de session
            $request->session()->regenerate();

            // TRAÇABILITÉ : Log de la connexion réussie
            LogAction::create([
                'user_id' => Auth::id(),
                'action' => 'Connexion',
                'details' => "L'utilisateur " . Auth::user()->name . " s'est connecté au système."
            ]);

            // Redirection vers la page demandée initialement ou par défaut le dashboard
            return redirect()->intended(route('dashboard'));
        }

        // 3. Échec de connexion : Retour avec erreur explicite sans compromettre la sécurité
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Déconnecte l'utilisateur et détruit la session.
     */
    public function logout(Request $request)
    {
        // TRAÇABILITÉ : Enregistrement avant de détruire la session
        if (Auth::check()) {
            LogAction::create([
                'user_id' => Auth::id(),
                'action' => 'Déconnexion',
                'details' => "L'utilisateur " . Auth::user()->name . " s'est déconnecté."
            ]);
        }

        Auth::logout();

        // Invalidation et régénération du token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirection vers la page welcome (conforme à votre option de redirection)
        return redirect()->route('welcome');
    }
}