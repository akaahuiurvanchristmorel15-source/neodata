<?php

namespace App\Http\Controllers;

use App\Models\LogAction;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Affiche le journal d'audit de sécurité
     */
    public function index()
    {
        $user = auth()->user();

        // Restriction supplémentaire par sécurité (rôles globaux)
        if (!$user->isAdmin() && !$user->isDirection()) {
            abort(403, 'Accès réservé aux auditeurs et administrateurs.');
        }

        $isSuperAdmin = $user->isSuperAdmin();
        $companyId = $user->company_id;

        // Récupération des actions filtrées par l'entreprise des utilisateurs associés (sauf si Super Admin)
        $logsQuery = LogAction::query()->with('user');

        if (!$isSuperAdmin) {
            $logsQuery->whereHas('user', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            });
        }

        $logs = $logsQuery->latest()->paginate(25);

        return view('audit.index', compact('logs'));
    }
}