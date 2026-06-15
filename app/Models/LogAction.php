<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAction extends Model
{
    use HasFactory;

    // Nom de la table associée (optionnel si respect des conventions, mais recommandé pour la clarté)
    protected $table = 'log_actions';

    // Les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'user_id',
        'action',
        'details',
    ];

    /**
     * Relation : Un log appartient à un utilisateur (l'auteur de l'action).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}