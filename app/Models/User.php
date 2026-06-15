<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Assurez-vous que le rôle est bien présent ici
        'company_id',
    ];

    /**
     * Les attributs à masquer pour la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs à caster.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ==========================================
    // AJOUTEZ LES MÉTHODES DE RÔLES ICI :
    // ==========================================

    /**
     * Vérifie si l'utilisateur est un Administrateur.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est l'Administrateur Général (niveau plateforme).
     * C'est un admin sans entreprise rattachée.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin' && is_null($this->company_id);
    }

    /**
     * Vérifie si l'utilisateur fait partie de la Direction.
     */
    public function isDirection(): bool
    {
        return $this->role === 'direction';
    }

    /**
     * Vérifie si l'utilisateur est un Employé standard.
     */
    public function isEmploye(): bool
    {
        return $this->role === 'employe';
    }

    /**
     * L'entreprise à laquelle appartient cet utilisateur.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
