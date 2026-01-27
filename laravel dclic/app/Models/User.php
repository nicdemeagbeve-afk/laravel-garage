<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'age',
        'sexe',
        'residence',
        'verification_code',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Accesseur: Vérifier si admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Accesseur: Vérifier si Gestion_Client
     */
    public function isGestionClient(): bool
    {
        return $this->role === 'gestion_client';
    }

    /**
     * Accesseur: Vérifier si Responsable Services
     */
    public function isResponsableServices(): bool
    {
        return $this->role === 'responsable_services';
    }

    /**
     * Accesseur: Vérifier si Client
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Relation: Utilisateurs créés par cet utilisateur
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Relation: Utilisateur qui a créé ce compte
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation : Un utilisateur possède plusieurs véhicules
     * 
     * @return HasMany
     */
    public function vehicules(): HasMany
    {
        return $this->hasMany(Vehicule::class);
    }

    /**
     * Relation : Un utilisateur peut déclarer plusieurs pannes
     * 
     * @return HasMany
     */
    public function breakdowns(): HasMany
    {
        return $this->hasMany(Breakdown::class);
    }
}