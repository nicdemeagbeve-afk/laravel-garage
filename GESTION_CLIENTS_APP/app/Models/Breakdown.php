<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Breakdown extends Model
{
    use SoftDeletes;
    
    protected $table = 'breakdowns';

    /**
     * Les attributs qui peuvent être assignés en masse
     * 
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'vehicule_id',
        'technicien_id',
        'description',
        'onsite_assistance',
        'latitude',
        'longitude',
        'status',
        'is_approved',
        'phone',
        'location',
        'needs_technician'
    ];

    /**
     * Les attributs à caster dans des types spécifiques
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'onsite_assistance' => 'boolean',
        'is_approved' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Relation : Une déclaration de panne appartient à un utilisateur
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Une déclaration de panne concerne un véhicule
     * 
     * @return BelongsTo
     */
    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Relation : Une déclaration de panne peut être assignée à un technicien
     * 
     * @return BelongsTo
     */
    public function technicien(): BelongsTo
    {
        return $this->belongsTo(Technicien::class);
    }

    /**
     * Scope : Récupère uniquement les pannes en attente
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope : Récupère uniquement les pannes en cours de traitement
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope : Récupère uniquement les pannes résolues
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Relation : Une panne peut avoir plusieurs services
     * 
     * @return BelongsToMany
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'breakdown_service')
            ->withPivot(['quantity', 'price_override', 'is_approved'])
            ->withTimestamps();
    }

    /**
     * Scope : Récupérer seulement les services approuvés
     */
    public function scopeWithApprovedServices($query)
    {
        return $query->whereHas('services', function ($q) {
            $q->wherePivot('is_approved', true);
        });
    }

    /**
     * Accesseur : Retourne le statut au format lisible
     * 
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'En attente',
            'in_progress' => 'En cours',
            'resolved' => 'Résolue',
            'cancelled' => 'Annulée'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Accesseur : Retourne si le dépannage a été demandé
     * 
     * @return bool
     */
    public function hasOnsiteAssistance(): bool
    {
        return $this->onsite_assistance === true;
    }

    /**
     * Accesseur : Retourne les coordonnées GPS formatées
     * 
     * @return string|null
     */
    public function getFormattedCoordinatesAttribute(): ?string
    {
        if ($this->latitude && $this->longitude) {
            return "{$this->latitude}, {$this->longitude}";
        }

        return null;
    }
}
