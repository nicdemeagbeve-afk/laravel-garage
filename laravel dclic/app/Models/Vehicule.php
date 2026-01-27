<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicule extends Model
{
    protected $table = 'vehicules';
    protected $fillable = [
        'immatriculation',
        'marque',
        'modele',
        'couleur',
        'annee',
        'kilometrage',
        'carrosserie',
        'energie',
        'boite',
        'user_id',
        'image'
    ];

    /**
     * Relation : Un véhicule appartient à un utilisateur
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Un véhicule peut avoir plusieurs réparations
     * 
     * @return HasMany
     */
    public function reparations(): HasMany
    {
        return $this->hasMany(Reparation::class);
    }

    /**
     * Relation : Un véhicule peut avoir plusieurs déclarations de pannes
     * 
     * @return HasMany
     */
    public function breakdowns(): HasMany
    {
        return $this->hasMany(Breakdown::class);
    }
}
