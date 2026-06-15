<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Liste tous les utilisateurs de la même entreprise.
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;
        
        // Filtrage strict par l'entreprise de l'utilisateur connecté
        $users = User::where('company_id', $companyId)
            ->latest()
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Formulaire de création d'un nouvel employé / compte direction.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Enregistrement du compte et assignation du rôle.
     */
    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,employe,direction'], 
        ]);

        // Création de l'utilisateur en injectant de force le company_id
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_id' => $companyId,
        ]);

        return redirect()->route('users.index')->with('success', 'Le compte utilisateur a été créé avec succès.');
    }

    /**
     * Formulaire d'édition d'un collaborateur existant.
     */
    public function edit(User $user)
    {
        // Vérification de la propriété de l'entreprise
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Action non autorisée. Cet utilisateur n\'appartient pas à votre entreprise.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Enregistrement des modifications du collaborateur.
     */
    public function update(Request $request, User $user)
    {
        // Vérification de la propriété de l'entreprise
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Action non autorisée. Cet utilisateur n\'appartient pas à votre entreprise.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,employe,direction'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Le compte utilisateur a été mis à jour avec succès.');
    }

    /**
     * Suppression d'un compte utilisateur.
     */
    public function destroy(User $user)
    {
        // Vérification de la propriété de l'entreprise
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Action non autorisée. Cet utilisateur n\'appartient pas à votre entreprise.');
        }

        // Empêcher l'administrateur de se supprimer lui-même par accident
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé du système.');
    }
}