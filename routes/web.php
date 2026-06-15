<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyAccessRequestController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuditController;

// ==========================================
// 1. ROUTES PUBLIQUES / INVITÉS (GUEST)
// ==========================================

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [CompanyAccessRequestController::class, 'create'])->name('register');
    Route::post('/register', [CompanyAccessRequestController::class, 'store']);

    // ==========================================
    // SUPPORT TECHNIQUE IA - RÉINITIALISATION MDP
    // ==========================================
    Route::prefix('password-reset')->name('password-reset.')->group(function () {
        // Étape 1 : Entrer l'email
        Route::get('/', [PasswordResetController::class, 'showEmailForm'])->name('email');
        Route::post('validate-email', [PasswordResetController::class, 'validateEmail'])->name('validate-email');

        // Étape 2 : Répondre aux 3 questions
        Route::get('questions', [PasswordResetController::class, 'showQuestions'])->name('questions');
        Route::post('verify-answers', [PasswordResetController::class, 'verifyAnswers'])->name('verify-answers');

        // Étape 3 : Nouveau mot de passe
        Route::get('new-password', [PasswordResetController::class, 'showNewPasswordForm'])->name('new-password');
        Route::post('update-password', [PasswordResetController::class, 'updatePassword'])->name('update-password');

        // Annuler
        Route::get('cancel', [PasswordResetController::class, 'cancel'])->name('cancel');
    });
});


// ==========================================
// 2. ROUTES PROTÉGÉES (ACCÈS CONNECTÉ UNIQUEMENT)
// ==========================================
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::get('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['url'] ?? route('dashboard'));
    })->name('notifications.read');

    // Fonctions de base (Consultation, dépôt, téléchargement)
    Route::resource('documents', DocumentController::class)->except(['destroy', 'edit', 'update']);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/export-archives', [DocumentController::class, 'exportCSV'])->name('documents.export');

    // ==========================================
    // 3. SOUS-GROUPE EXCLUSIF À L'ADMINISTRATEUR
    // ==========================================
    // Correction ici : Utilisation de la syntaxe de filtrage par callback compatible avec le RouteRegistrar
    Route::middleware(['role:admin'])->group(function () {

        // Droits d'édition/suppression sur les documents
        Route::resource('documents', DocumentController::class)->only(['edit', 'update', 'destroy']);

        // Gestion des secteurs/catégories et des comptes
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);

        Route::get('/company-requests', [CompanyAccessRequestController::class, 'index'])->name('company-requests.index');
        Route::get('/company-requests/{companyAccessRequest}', [CompanyAccessRequestController::class, 'show'])->name('company-requests.show');
        Route::post('/company-requests/{companyAccessRequest}/approve', [CompanyAccessRequestController::class, 'approve'])->name('company-requests.approve');
        Route::post('/company-requests/{companyAccessRequest}/reject', [CompanyAccessRequestController::class, 'reject'])->name('company-requests.reject');
        Route::post('/companies/{company}/toggle-status', [CompanyAccessRequestController::class, 'toggleCompanyStatus'])->name('companies.toggle-status');
    });
});

Route::middleware(['auth'])->group(function () {

    // Vos routes existantes (dashboard, documents, etc.)

    // AJOUTEZ CETTE LIGNE TEMPORAIRE :
    Route::get('/profile', function () {
        return "Page de gestion du profil en cours de développement.";
    })->name('profile.edit');

});

Route::middleware(['auth'])->group(function () {
    // ... vos autres routes ...

    // Journal d'audit accessible à la Direction et à l'Admin
    Route::get('/audit-logs', [AuditController::class, 'index'])->name('audit.index');
});

Route::middleware(['auth'])->group(function () {
    // Routes pour la corbeille et restauration (Sécurité suppression)
    Route::get('/archives/corbeille', [DocumentController::class, 'trash'])->name('documents.trash');
    Route::post('/archives/{id}/restaurer', [DocumentController::class, 'restore'])->name('documents.restore');

    // Vos routes de ressources standards
    Route::resource('documents', DocumentController::class);
});



// OU si vous préférez déclarer les routes manuellement, il vous faut au minimum ces deux-là :
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

// ROUTE DE SECOURS POUR CRÉER L'ADMINISTRATEUR GÉNÉRAL DEPUIS LE NAVIGATEUR
Route::get('/create-super-admin', function() {
    $email = 'superadmin@neodata.com';
    $user = \App\Models\User::where('email', $email)->first();
    if (!$user) {
        $user = \App\Models\User::create([
            'name' => 'Administrateur Général',
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make('SuperAdmin2026!'),
            'role' => 'admin',
            'company_id' => null, // Super Admin
        ]);
        return "Compte Super Admin créé avec succès ! Email : superadmin@neodata.com | Mot de passe : SuperAdmin2026!";
    }
    return "Le compte Super Admin existe déjà dans le système !";
});