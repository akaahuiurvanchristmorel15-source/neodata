<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyAccessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CompanyAccessRequestController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'string', 'email', 'max:255', 'unique:companies,email'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'reason' => ['nullable', 'string', 'max:1200'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        CompanyAccessRequest::create([
            'company_name' => $validated['company_name'],
            'company_email' => $validated['company_email'],
            'contact_name' => $validated['name'],
            'contact_email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
            'reason' => $validated['reason'] ?? null,
            'status' => CompanyAccessRequest::STATUS_PENDING,
            'root_password_hash' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Votre demande d’accès a bien été envoyée. Un administrateur principal l’examinera rapidement.');
    }

    /**
     * Vérifie de façon absolue que l'utilisateur connecté est l'Administrateur Général (Super Admin).
     */
    private function authorizeSuperAdmin(): void
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Accès réservé à l\'administrateur général de la plateforme.');
        }
    }

    public function index()
    {
        $this->authorizeSuperAdmin();

        // Migration automatique et transparente de la base de données
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('companies', 'is_active')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }
        } catch (\Exception $e) {
            // Silence
        }

        $requests = CompanyAccessRequest::with('company')->latest()->paginate(15);

        return view('company_requests.index', compact('requests'));
    }

    public function show(CompanyAccessRequest $companyAccessRequest)
    {
        $this->authorizeSuperAdmin();
        return view('company_requests.show', compact('companyAccessRequest'));
    }

    public function approve(Request $request, CompanyAccessRequest $companyAccessRequest)
    {
        $this->authorizeSuperAdmin();

        if ($companyAccessRequest->status !== CompanyAccessRequest::STATUS_PENDING) {
            return redirect()->route('company-requests.index')->with('error', 'Cette demande a déjà été traitée.');
        }

        $company = Company::createWithRootAccount(
            [
                'nom' => $companyAccessRequest->company_name,
                'email' => $companyAccessRequest->company_email,
                'telephone' => $companyAccessRequest->telephone,
                'adresse' => $companyAccessRequest->adresse,
            ],
            [
                'name' => $companyAccessRequest->contact_name,
                'email' => $companyAccessRequest->contact_email,
                'password' => $companyAccessRequest->root_password_hash,
                'password_is_hashed' => true,
                'role' => 'admin',
            ]
        );

        $companyAccessRequest->update([
            'status' => CompanyAccessRequest::STATUS_APPROVED,
            'company_id' => $company->id,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('company-requests.index')->with('success', 'La demande a été approuvée et le compte entreprise a été créé.');
    }

    public function reject(Request $request, CompanyAccessRequest $companyAccessRequest)
    {
        $this->authorizeSuperAdmin();

        if ($companyAccessRequest->status !== CompanyAccessRequest::STATUS_PENDING) {
            return redirect()->route('company-requests.index')->with('error', 'Cette demande a déjà été traitée.');
        }

        $companyAccessRequest->update([
            'status' => CompanyAccessRequest::STATUS_REJECTED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('company-requests.index')->with('success', 'La demande a été rejetée.');
    }

    public function toggleCompanyStatus(Company $company)
    {
        $this->authorizeSuperAdmin();

        $company->is_active = !$company->is_active;
        $company->save();

        $status = $company->is_active ? 'réactivée' : 'suspendue';
        return redirect()->back()->with('success', "L'entreprise '{$company->nom}' a été {$status} avec succès.");
    }
}
