<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LogAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * 1. AFFICHAGE DES CATÉGORIES
     */
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $currentCategoryId = $request->input('category_id');
        $currentCategory = null;
        $breadcrumbs = [];

        if ($currentCategoryId) {
            // Sécurité : on récupère la catégorie uniquement si elle appartient à l'entreprise
            $currentCategory = Category::with('parent')
                ->where('company_id', $companyId)
                ->find($currentCategoryId);

            if ($currentCategory) {
                // Construction du breadcrumb en remontant aux parents
                $temp = $currentCategory;
                while ($temp) {
                    array_unshift($breadcrumbs, $temp);
                    $temp = $temp->parent;
                }
            }
        }

        // Toutes les catégories de l'entreprise (pour le select du formulaire de création)
        $allCategories = Category::with('parent')
            ->where('company_id', $companyId)
            ->get();

        // Déterminer les sous-dossiers à afficher dans la vue
        if ($currentCategory) {
            $subCategories = Category::where('parent_id', $currentCategory->id)
                ->where('company_id', $companyId)
                ->withCount('documents')
                ->get();
                
            // Récupérer les documents du dossier actuel
            $documents = $currentCategory->documents()->latest()->get();
        } else {
            // Niveau Racine de l'entreprise uniquement
            $subCategories = Category::whereNull('parent_id')
                ->where('company_id', $companyId)
                ->withCount('documents')
                ->get();
                
            $documents = collect(); // À la racine de la structure, on n'affiche pas de documents directement
        }

        return view('categories.index', compact(
            'allCategories',
            'currentCategory',
            'subCategories',
            'breadcrumbs',
            'documents'
        ));
    }

    /**
     * 2. FORMULAIRE DE CRÉATION
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * 3. ENREGISTREMENT D'UNE NOUVELLE CATÉGORIE
     */
    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Validation stricte : le dossier parent doit appartenir à la même entreprise
        if ($request->parent_id) {
            $parentValide = Category::where('id', $request->parent_id)
                ->where('company_id', $companyId)
                ->exists();

            if (!$parentValide) {
                return redirect()->back()->with('error', 'Le dossier parent sélectionné est invalide.');
            }
        }

        // Vérification de l'unicité locale au sein de l'entreprise et du dossier parent
        $existe_deja = Category::where('nom', $request->nom)
            ->where('parent_id', $request->parent_id)
            ->where('company_id', $companyId)
            ->exists();

        if ($existe_deja) {
            return redirect()->back()->with('error', 'Un dossier portant ce nom existe déjà à cet emplacement.');
        }

        // Injection forcée du company_id de l'utilisateur connecté
        $data = $request->all();
        $data['company_id'] = $companyId;

        $category = Category::create($data);

        // Log de traçabilité
        $type = $category->parent_id ? "sous-dossier" : "dossier principal";
        \App\Models\LogAction::create([
            'user_id' => auth()->id(),
            'action' => 'Création Structure',
            'details' => "A créé le {$type} : '{$category->nom}'"
        ]);

        return redirect()->route('categories.index', ['category_id' => $category->parent_id])->with('success', 'Dossier créé avec succès.');
    }

    /**
     * 4. FORMULAIRE D'ÉDITION / MODIFICATION
     */
    public function edit(Category $category)
    {
        // Vérification de la propriété
        if ($category->company_id !== auth()->user()->company_id) {
            abort(403, 'Action non autorisée.');
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * 5. MISE À JOUR DE LA CATÉGORIE
     */
    public function update(Request $request, Category $category)
    {
        // Vérification de la propriété
        if ($category->company_id !== auth()->user()->company_id) {
            abort(403, 'Action non autorisée.');
        }

        $companyId = auth()->user()->company_id;

        // Mise à jour de la règle unique pour cibler uniquement les dossiers de cette entreprise
        $request->validate([
            'nom' => "required|string|max:255|unique:categories,nom,{$category->id},id,company_id,{$companyId}",
        ]);

        $ancienNom = $category->nom;

        $category->update([
            'nom' => $request->nom,
            'slug' => Str::slug($request->nom),
        ]);

        // TRAÇABILITÉ : Log du changement de nom du dossier
        LogAction::create([
            'user_id' => auth()->id(),
            'action' => 'Modification Catégorie',
            'details' => "A renommé la catégorie '{$ancienNom}' en '{$category->nom}'"
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * 6. SUPPRESSION D'UNE CATÉGORIE
     */
    public function destroy(Category $category)
    {
        // Vérification de la propriété
        if ($category->company_id !== auth()->user()->company_id) {
            abort(403, 'Action non autorisée.');
        }

        // Option de sécurité : Empêcher la suppression si la catégorie contient des documents
        if ($category->documents()->count() > 0) {
            return redirect()->back()->with('error', "Impossible de supprimer la catégorie '{$category->nom}' car elle contient des documents actifs. Déplacez d'abord les fichiers.");
        }

        LogAction::create([
            'user_id' => auth()->id(),
            'action' => 'Suppression Catégorie',
            'details' => "A supprimé la catégorie d'archives : '{$category->nom}'"
        ]);

        $parentId = $category->parent_id;
        $category->delete();

        return redirect()->route('categories.index', ['category_id' => $parentId])->with('success', 'Catégorie supprimée avec succès.');
    }
}