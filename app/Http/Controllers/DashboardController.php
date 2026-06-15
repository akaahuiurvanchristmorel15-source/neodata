<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use App\Models\LogAction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $companyId = $user->company_id;

        // 1. Journaux d'activité
        $logsQuery = LogAction::query()->with('user');
        if (!$isSuperAdmin) {
            $logsQuery->whereHas('user', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            });
        }
        $logs = $logsQuery->latest()->take(10)->get();

        // 2. Nombre total de documents de l'entreprise (ou global si Super Admin)
        $totalDocumentsQuery = Document::query();
        if (!$isSuperAdmin) {
            $totalDocumentsQuery->whereHas('category', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            });
        }
        $totalDocuments = $totalDocumentsQuery->count();

        $totalCategoriesQuery = Category::query();
        if (!$isSuperAdmin) {
            $totalCategoriesQuery->where('company_id', $companyId);
        }
        $totalCategories = $totalCategoriesQuery->count();

        // 3. Derniers documents ajoutés
        $derniersDocumentsQuery = Document::with('category');
        if (!$isSuperAdmin) {
            $derniersDocumentsQuery->whereHas('category', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            });
        }
        $derniersDocuments = $derniersDocumentsQuery->latest()->take(5)->get();

        // 4. Documents les plus consultés
        $topConsultationsQuery = LogAction::where('action', 'Téléchargement');
        if (!$isSuperAdmin) {
            $topConsultationsQuery->whereHas('user', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            });
        }
        $topConsultations = $topConsultationsQuery->select('details', DB::raw('count(*) as total'))
            ->groupBy('details')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get()
            ->map(function ($log) {
                // Extraction propre du titre du document entre guillemets simples '...'
                preg_match("/'([^']+)'/", $log->details, $matches);
                return [
                    'titre' => $matches[1] ?? 'Document inconnu',
                    'total' => $log->total
                ];
            });

        // 5. Statistiques d'archivage par dossier/catégorie
        $statsCategoriesQuery = Category::query();
        if (!$isSuperAdmin) {
            $statsCategoriesQuery->where('company_id', $companyId);
        }
        $statsCategories = $statsCategoriesQuery->withCount('documents')
            ->orderBy('documents_count', 'desc')
            ->take(4)
            ->get();

        // Envoi de toutes les variables filtrées à la vue
        return view('dashboard', compact(
            'totalDocuments', 
            'totalCategories', 
            'derniersDocuments', 
            'topConsultations', 
            'statsCategories', 
            'logs'
        ));
    }
}