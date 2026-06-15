<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CompanyAccessRequest;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

$request = CompanyAccessRequest::find(3);
if ($request) {
    echo "Request found: " . $request->company_name . "\n";
    echo "Status: " . $request->status . "\n";
    
    // Use a known password for testing
    $rootPassword = 'RootPass2024!';
    
    // Create company with root account
    try {
        $company = Company::createWithRootAccount(
            [
                'nom' => $request->company_name,
                'email' => $request->company_email,
                'telephone' => $request->telephone,
                'adresse' => $request->adresse
            ],
            [
                'name' => $request->contact_name,
                'email' => $request->contact_email,
                'role' => 'admin',
                'password' => $rootPassword
            ]
        );
        
        $request->update([
            'status' => 'approved',
            'approved_by' => 1,
            'approved_at' => now(),
            'root_password_hash' => Hash::make($rootPassword)
        ]);
        
        echo "✓ Approved! Company: " . $company->nom . "\n";
        echo "✓ Root category created: " . $company->categories->count() . "\n";
        echo "✓ Root user created: " . $company->users->count() . "\n";
        echo "✓ Root user email: " . $company->users->first()->email . "\n";
        echo "\nRoot user can login with:\n";
        echo "  Email: " . $company->users->first()->email . "\n";
        echo "  Password: " . $rootPassword . "\n";
    } catch (\Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "Request not found\n";
}
