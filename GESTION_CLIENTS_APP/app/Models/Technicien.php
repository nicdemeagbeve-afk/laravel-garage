<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technicien extends Model
{
    protected $fillable = [
        "nom",
        "prenom",
        "specialite",
        "photo_url",
        "age",
        "image"
    ];

    /**
     * Relation : Un technicien peut avoir plusieurs réparations
     * 
     * @return HasMany
     */
    public function reparations(): HasMany
    {
        return $this->hasMany(Reparation::class);
    }

    /**
     * Relation : Un technicien peut recevoir plusieurs déclarations de pannes
     * 
     * @return HasMany
     */
    public function breakdowns(): HasMany
    {
        return $this->hasMany(Breakdown::class);
    }

    /**
     * Accesseur : Retourne le nom complet du technicien
     * 
     * @return string
     */
    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
