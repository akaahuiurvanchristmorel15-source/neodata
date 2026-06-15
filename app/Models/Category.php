<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'parent_id', 'company_id'];

    /**
     * L'entreprise à laquelle appartient ce dossier.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Récupérer le dossier parent
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Récupérer les sous-dossiers enfants immédiats
     */
    public function enfants()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Récupérer TOUS les documents de ce dossier ET de ses sous-dossiers
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'category_id');
    }

    /**
     * Récupérer tous les IDs des catégories enfants récursivement
     */
    public function getAllDescendantIds()
    {
        $ids = [$this->id];
        foreach ($this->enfants as $enfant) {
            $ids = array_merge($ids, $enfant->getAllDescendantIds());
        }
        return $ids;
    }

    /**
     * Calculer la taille totale de tous les documents de ce dossier (et ses sous-dossiers récursivement) en Mo
     */
    public function getTailleMoAttribute()
    {
        $categoryIds = $this->getAllDescendantIds();
        $documents = Document::whereIn('category_id', $categoryIds)->get();
        
        $totalSizeMo = 0;
        foreach ($documents as $doc) {
            $totalSizeMo += $doc->taille_mo;
        }
        
        return round($totalSizeMo, 2);
    }
}