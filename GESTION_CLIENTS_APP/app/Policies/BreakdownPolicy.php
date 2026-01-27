<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Breakdown;

class BreakdownPolicy
{
    /**
     * Déterminer si l'utilisateur peut voir une panne
     * 
     * Une panne ne peut être vue que par son propriétaire
     *
     * @param User $user
     * @param Breakdown $breakdown
     * @return bool
     */
    public function view(User $user, Breakdown $breakdown): bool
    {
        return $user->id === $breakdown->user_id;
    }

    /**
     * Déterminer si l'utilisateur peut mettre à jour une panne
     * 
     * Une panne ne peut être modifiée que par son propriétaire
     * ET seulement si elle n'est pas résolue ou annulée
     *
     * @param User $user
     * @param Breakdown $breakdown
     * @return bool
     */
    public function update(User $user, Breakdown $breakdown): bool
    {
        return $user->id === $breakdown->user_id && 
               !in_array($breakdown->status, ['resolved', 'cancelled']);
    }

    /**
     * Déterminer si l'utilisateur peut supprimer une panne
     * 
     * Une panne ne peut être annulée que par son propriétaire
     * ET seulement si elle n'est pas en cours de traitement
     *
     * @param User $user
     * @param Breakdown $breakdown
     * @return bool
     */
    public function delete(User $user, Breakdown $breakdown): bool
    {
        return $user->id === $breakdown->user_id && 
               $breakdown->status !== 'in_progress';
    }
}
