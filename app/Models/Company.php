<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'adresse',
    ];

    /**
     * Relation vers les utilisateurs de l'entreprise.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation vers les catégories de l'entreprise.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Retourne la catégorie racine de l'entreprise.
     */
    public function rootCategory()
    {
        return $this->hasOne(Category::class)->whereNull('parent_id');
    }

    /**
     * Crée une entreprise avec un compte racine et une catégorie racine.
     *
     * @param array $companyData  ['nom', 'email', 'telephone'?, 'adresse'?]
     * @param array $rootUserData ['name', 'email', 'password', 'role']
     *
     * @throws InvalidArgumentException
     */
    public static function createWithRootAccount(array $companyData, array $rootUserData): self
    {
        if (empty($companyData['nom']) || empty($companyData['email'])) {
            throw new InvalidArgumentException('Le nom et l\'email de l\'entreprise sont obligatoires.');
        }

        if (empty($rootUserData['name']) || empty($rootUserData['email']) || empty($rootUserData['password'])) {
            throw new InvalidArgumentException('Le nom, l\'email et le mot de passe de l\'utilisateur racine sont obligatoires.');
        }

        return DB::transaction(function () use ($companyData, $rootUserData) {
            $company = self::create($companyData);

            $company->categories()->create([
                'nom' => $company->nom . ' - Racine',
                'description' => 'Catégorie racine de l\'entreprise ' . $company->nom,
                'parent_id' => null,
            ]);

            $password = $rootUserData['password'];
            if (!empty($rootUserData['password_is_hashed'])) {
                $storedPassword = $password;
            } else {
                $storedPassword = Hash::make($password);
            }

            $company->users()->create([
                'name' => $rootUserData['name'],
                'email' => $rootUserData['email'],
                'password' => $storedPassword,
                'role' => $rootUserData['role'] ?? 'admin',
            ]);

            return $company->load('rootCategory', 'users');
        });
    }
}
