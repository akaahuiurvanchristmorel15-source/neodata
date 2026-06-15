<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création du compte Administrateur Général de la plateforme (Super Admin)
        User::updateOrCreate(
            ['email' => 'neodata@ya-consulting.com'],
            [
                'name' => 'Administrateur Général',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'company_id' => null, // Pas de compagnie = Super Admin
            ]
        );
    }
}