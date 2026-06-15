<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    /**
     * Affiche le formulaire pour entrer l'email
     */
    public function showEmailForm()
    {
        return view('auth.password-reset.email');
    }

    /**
     * Valide l'email et charge les questions de vérification
     */
    public function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.exists' => 'Cette adresse email n\'est pas trouvée dans nos registres.'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        // Vérifier que l'utilisateur a une entreprise
        if (!$user->company_id) {
            return back()->with('error', 'Erreur : Cet utilisateur n\'est pas associé à une entreprise.');
        }

        // Stocker l'email en session pour la vérification
        session(['password_reset_email' => $request->email]);

        return redirect()->route('password-reset.questions');
    }

    /**
     * Affiche les 3 questions de vérification
     */
    public function showQuestions()
    {
        // Vérifier que l'email est en session
        if (!session('password_reset_email')) {
            return redirect()->route('password-reset.email')
                ->with('error', 'Veuillez d\'abord entrer votre adresse email.');
        }

        $email = session('password_reset_email');
        $user = User::where('email', $email)->firstOrFail();
        $company = $user->company;

        // Les 3 questions et leurs réponses attendues
        $questions = [
            [
                'id' => 1,
                'question' => 'Quel est le nom de votre entreprise ?',
                'answer' => $company->nom,
                'placeholder' => 'Saisissez le nom de l\'entreprise'
            ],
            [
                'id' => 2,
                'question' => 'Quel est le numéro de téléphone de votre entreprise ?',
                'answer' => $company->telephone,
                'placeholder' => 'Saisissez le numéro de téléphone'
            ],
            [
                'id' => 3,
                'question' => 'Quel est l\'adresse email de votre entreprise ?',
                'answer' => $company->email,
                'placeholder' => 'Saisissez l\'adresse email de l\'entreprise'
            ]
        ];

        return view('auth.password-reset.questions', compact('questions'));
    }

    /**
     * Vérifie les réponses aux 3 questions
     */
    public function verifyAnswers(Request $request)
    {
        // Vérifier que l'email est en session
        if (!session('password_reset_email')) {
            return redirect()->route('password-reset.email')
                ->with('error', 'Session expirée. Veuillez recommencer.');
        }

        $email = session('password_reset_email');
        $user = User::where('email', $email)->firstOrFail();
        $company = $user->company;

        // Valider les 3 réponses
        $request->validate([
            'answer1' => 'required|string',
            'answer2' => 'required|string',
            'answer3' => 'required|string'
        ], [
            'answer1.required' => 'La réponse 1 est requise.',
            'answer2.required' => 'La réponse 2 est requise.',
            'answer3.required' => 'La réponse 3 est requise.'
        ]);

        // Normaliser les réponses pour la comparaison (ignorer casse et espaces)
        $normalize = function($str) {
            return strtolower(trim($str));
        };

        $answers = [
            $normalize($request->answer1) === $normalize($company->nom),
            $normalize($request->answer2) === $normalize($company->telephone),
            $normalize($request->answer3) === $normalize($company->email)
        ];

        // Vérifier que toutes les réponses sont correctes
        if (!in_array(false, $answers, true)) {
            // Toutes les réponses sont correctes
            session(['password_reset_verified' => true]);
            return redirect()->route('password-reset.new-password')
                ->with('success', '✓ Vérification réussie ! Vous pouvez maintenant réinitialiser votre mot de passe.');
        } else {
            // Une ou plusieurs réponses sont incorrectes
            return back()->with('error', '✕ Une ou plusieurs réponses sont incorrectes. Veuillez réessayer.');
        }
    }

    /**
     * Affiche le formulaire de réinitialisation du mot de passe
     */
    public function showNewPasswordForm()
    {
        // Vérifier que la vérification est complétée
        if (!session('password_reset_verified')) {
            return redirect()->route('password-reset.email')
                ->with('error', 'Veuillez d\'abord vérifier votre identité.');
        }

        $email = session('password_reset_email');

        return view('auth.password-reset.new-password', compact('email'));
    }

    /**
     * Traite la réinitialisation du mot de passe
     */
    public function updatePassword(Request $request)
    {
        // Vérifier que la vérification est complétée
        if (!session('password_reset_verified')) {
            return redirect()->route('password-reset.email')
                ->with('error', 'Session expirée. Veuillez recommencer.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $email = session('password_reset_email');
        $user = User::where('email', $email)->firstOrFail();

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Effacer la session
        session()->forget(['password_reset_email', 'password_reset_verified']);

        return redirect()->route('login')
            ->with('success', '✓ Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
    }

    /**
     * Annule la réinitialisation et revient au login
     */
    public function cancel()
    {
        session()->forget(['password_reset_email', 'password_reset_verified']);

        return redirect()->route('login')
            ->with('info', 'Réinitialisation du mot de passe annulée.');
    }
}
