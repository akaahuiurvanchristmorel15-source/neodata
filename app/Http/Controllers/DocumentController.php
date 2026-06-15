<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use App\Models\LogAction;
use App\Models\User;
use App\Notifications\DocumentAddedNotification;
use App\Notifications\DocumentModifiedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DocumentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (auth()->check() && auth()->user()->isSuperAdmin()) {
                    abort(403, 'Accès interdit aux administrateurs généraux.');
                }
                return $next($request);
            }),
        ];
    }
    // -------------------------------------------------------------------------
    // HELPERS PRIVÉS
    // -------------------------------------------------------------------------

    /**
     * Empêche l'administrateur général (Super Admin) d'accéder aux données documentaires des entreprises.
     */
    private function checkNotSuperAdmin(): void
    {
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            abort(403, 'Accès interdit aux administrateurs généraux.');
        }
    }

    /**
     * Retourne la requête de base pour les catégories de l'entreprise.
     */
    private function companyCategories()
    {
        return Category::where('company_id', auth()->user()->company_id);
    }

    /**
     * Vérifie de manière stricte qu'un document appartient à l'entreprise.
     */
    private function authorizeDocument(Document $document): void
    {
        $belongsToCompany = Category::where('id', $document->category_id)
            ->where('company_id', auth()->user()->company_id)
            ->exists();

        if (!$belongsToCompany) {
            abort(403, "Accès refusé : ce document n'appartient pas à votre entreprise.");
        }
    }

    // =========================================================================
    // TOUS LES RÔLES : Liste et Recherche de documents
    // =========================================================================
    public function index(Request $request)
    {
        $this->checkNotSuperAdmin();

        // Récupère uniquement les IDs des catégories de l'entreprise
        $companyCategoryIds = $this->companyCategories()->pluck('id');

        // Restreint la requête principale au périmètre de l'entreprise
        $query = Document::with('category')
            ->whereIn('category_id', $companyCategoryIds);

        // 1. Recherche par Mot-clé / Titre / Auteur
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                    ->orWhere('mots_cles', 'like', "%{$search}%")
                    ->orWhere('auteur', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Recherche par Catégorie (Double protection)
        if ($request->filled('category_id')) {
            $requested = (int) $request->input('category_id');
            if ($companyCategoryIds->contains($requested)) {
                $query->where('category_id', $requested);
            } else {
                $query->whereRaw('1 = 0'); // Force un résultat vide si tentative d'injection d'un ID tiers
            }
        }

        // 3. Recherche par Date
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->input('date_debut'));
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->input('date_fin'));
        }

        $documents = $query->latest()->paginate(15)->withQueryString();
        $categories = $this->companyCategories()->get();

        return view('documents.index', compact('documents', 'categories'));
    }

    // =========================================================================
    // TOUS LES RÔLES : Formulaire de dépôt
    // =========================================================================
    public function create()
    {
        $categories = $this->companyCategories()->get();
        return view('documents.create', compact('categories'));
    }

    // =========================================================================
    // TOUS LES RÔLES : Enregistrement du dépôt (Indexation)
    // =========================================================================
    public function store(Request $request)
    {
        $companyCategoryIds = $this->companyCategories()->pluck('id')->toArray();

        $request->validate([
            'titre'       => 'required|string|max:255',
            'category_id' => [
                'required', 
                'exists:categories,id', 
                Rule::in($companyCategoryIds) // Validation multi-tenant sécurisée
            ],
            'fichier'     => 'required|file|max:10240',
        ]);

        $file             = $request->file('fichier');
        $encryptedContent = Crypt::encryptString(file_get_contents($file->getRealPath()));
        $fileName         = 'archives/' . uniqid() . '_' . $file->getClientOriginalName();
        Storage::put($fileName, $encryptedContent);

        $document = Document::create([
            'titre'          => $request->titre,
            'category_id'    => $request->category_id,
            'chemin_fichier' => $fileName,
            'auteur'         => auth()->user()->name,
            'description'    => $request->description,
            'mots_cles'      => $request->mots_cles,
        ]);

        LogAction::create([
            'user_id' => auth()->id(),
            'action'  => 'Ajout document',
            'details' => "A ajouté le document '{$document->titre}' (ID: {$document->id}) dans le dossier ID: {$document->category_id}.",
        ]);

        $destinataires = User::whereIn('role', ['admin', 'direction'])
            ->where('company_id', auth()->user()->company_id)
            ->get();
            
        Notification::send($destinataires, new DocumentAddedNotification($document));

        return redirect()->route('documents.index')->with('success', 'Document archivé et logué.');
    }

    // =========================================================================
    // TOUS LES RÔLES : Téléchargement sécurisé
    // =========================================================================
    public function download(Document $document)
    {
        $this->authorizeDocument($document);

        if (!Storage::exists($document->chemin_fichier)) {
            abort(404, "Le fichier physique est introuvable sur le serveur.");
        }

        LogAction::create([
            'user_id' => auth()->id(),
            'action'  => 'Téléchargement',
            'details' => "A téléchargé et décrypté le document '{$document->titre}' (ID: {$document->id}).",
        ]);

        $encryptedContent = Storage::get($document->chemin_fichier);
        $decryptedContent = Crypt::decryptString($encryptedContent);

        return response()->streamDownload(function () use ($decryptedContent) {
            echo $decryptedContent;
        }, basename($document->titre));
    }

    // =========================================================================
    // EXCLUSIF DIRECTION & ADMIN : Exportation globale des métadonnées
    // =========================================================================
    public function exportCSV()
    {
        if (!auth()->user()->isDirection() && !auth()->user()->isAdmin()) {
            abort(403, "Accès refusé. Fonctionnalité réservée à la Direction.");
        }

        $companyCategoryIds = $this->companyCategories()->pluck('id');

        $documents = Document::with('category')
            ->whereIn('category_id', $companyCategoryIds)
            ->latest()
            ->get();

        $fileName = 'export_archives_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $columns = ['ID', 'Titre', 'Secteur/Categorie', 'Auteur du dépot', 'Date d\'archivage', 'Mots-cles'];

        $callback = function () use ($documents, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fputcsv($file, $columns, ';');

            foreach ($documents as $doc) {
                fputcsv($file, [
                    $doc->id,
                    $doc->titre,
                    $doc->category->nom,
                    $doc->auteur,
                    $doc->created_at->format('d/m/Y H:i'),
                    $doc->mots_cles ?? 'Aucun',
                ], ';');
            }
            fclose($file);
        };

        LogAction::create([
            'user_id' => auth()->id(),
            'action'  => 'Export Global',
            'details' => "La Direction a exporté la base de données des archives au format CSV.",
        ]);

        return response()->stream($callback, 200, $headers);
    }

    // =========================================================================
    // EXCLUSIF ADMIN : Formulaire de modification d'un document
    // =========================================================================
    public function edit(Document $document)
    {
        $this->authorizeDocument($document);
        $categories = $this->companyCategories()->get();

        return view('documents.edit', compact('document', 'categories'));
    }

    // =========================================================================
    // EXCLUSIF ADMIN : Traitement de la modification
    // =========================================================================
    public function update(Request $request, Document $document)
    {
        $this->authorizeDocument($document);

        if ($document->est_verrouille) {
            return redirect()->back()->with('error', '🚨 Sécurité : Ce document est classé en archivage longue durée et ne peut être modifié.');
        }

        $companyCategoryIds = $this->companyCategories()->pluck('id')->toArray();

        $request->validate([
            'titre'       => 'required|string|max:255',
            'category_id' => [
                'required', 
                'exists:categories,id', 
                Rule::in($companyCategoryIds)
            ],
        ]);

        $changementTitre = $document->titre !== $request->titre
            ? "Ancien titre : '{$document->titre}' -> Nouveau : '{$request->titre}'"
            : "Titre inchangé";

        $document->update($request->only(['titre', 'category_id', 'description', 'mots_cles']));

        LogAction::create([
            'user_id' => auth()->id(),
            'action'  => 'Modification document',
            'details' => "A modifié le document '{$document->titre}' (ID: {$document->id}). {$changementTitre}.",
        ]);

        $destinataires = User::whereIn('role', ['admin', 'direction'])
            ->where('company_id', auth()->user()->company_id)
            ->get();
            
        Notification::send($destinataires, new DocumentModifiedNotification($document, auth()->user()));

        return redirect()->route('documents.index')->with('success', 'Métadonnées de l\'archive mises à jour.');
    }

    // =========================================================================
    // EXCLUSIF ADMIN : Envoi à la corbeille (Soft Delete)
    // =========================================================================
    public function destroy(Document $document)
    {
        $this->authorizeDocument($document);

        if ($document->est_verrouille) {
            return redirect()->back()->with('error', '🚨 Sécurité : Ce document est verrouillé de façon permanente pour archivage longue durée.');
        }

        $titreDocument = $document->titre;

        // Soft-delete uniquement (On conserve le fichier pour permettre la restauration)
        $document->delete();

        LogAction::create([
            'user_id' => auth()->id(),
            'action'  => 'Suppression',
            'details' => "A déplacé l'archive '{$titreDocument}' vers la corbeille.",
        ]);

        return redirect()->route('documents.index')->with('success', 'Document déplacé dans la corbeille.');
    }

    // =========================================================================
    // Corbeille : documents supprimés de l'entreprise uniquement
    // =========================================================================
    public function trash()
    {
        $companyCategoryIds = $this->companyCategories()->pluck('id');

        $documentsSupprimes = Document::onlyTrashed()
            ->whereIn('category_id', $companyCategoryIds)
            ->latest()
            ->get();

        return view('documents.trash', compact('documentsSupprimes'));
    }

    // =========================================================================
    // Restauration d'un document supprimé
    // =========================================================================
    public function restore($id)
    {
        // Récupération du document détruit au préalable
        $document = Document::onlyTrashed()->findOrFail($id);

        // Vérification de la propriété de l'entreprise avant traitement
        $this->authorizeDocument($document);

        $document->restore();

        LogAction::create([
            'user_id' => auth()->id(),
            'action'  => 'Restauration archive',
            'details' => "A restauré le document accidentellement supprimé : '{$document->titre}'.",
        ]);

        return redirect()->route('documents.index')->with('success', 'Le document a été restauré avec succès.');
    }
}