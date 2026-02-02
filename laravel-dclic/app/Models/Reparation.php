<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reparation extends Model
{
    protected $fillable = [
        "vehicule_id",
        "technicien_id",
        "date",
        "duree_main_oeuvre",
        "objet_reparation",
        "image"
    ];

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function technicien(): BelongsTo
    {
        return $this->belongsTo(Technicien::class);
    }
}
