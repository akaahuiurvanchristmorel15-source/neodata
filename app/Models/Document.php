<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;



class Document extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'documents';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'titre',
        'category_id',
        'chemin_fichier',
        'auteur',
        'description',
        'mots_cles',
        'est_verrouille'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // L'auteur du dépôt
    }

    /**
     * Obtenir la taille du document en Mo.
     */
    public function getTailleMoAttribute()
    {
        return cache()->remember("doc_size_{$this->id}", 86400, function () {
            try {
                if ($this->chemin_fichier && \Illuminate\Support\Facades\Storage::exists($this->chemin_fichier)) {
                    $bytes = \Illuminate\Support\Facades\Storage::size($this->chemin_fichier);
                    // Conversion de la taille base64 chiffrée vers la taille originale estimée (base64 augmente de ~33%)
                    $originalBytes = $bytes * 0.75;
                    return round($originalBytes / (1024 * 1024), 2);
                }
            } catch (\Exception $e) {
                // Ignore
            }
            return 0.1; // Fallback
        });
    }
}