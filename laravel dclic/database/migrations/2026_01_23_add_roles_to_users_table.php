<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Ajoute le système de rôles à la table users
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rôles du système
            $table->enum('role', [
                'client',
                'gestion_client',
                'responsable_services',
                'admin'
            ])->default('client')->after('email_verified_at');
            
            // Statut de compte (pour approbation admin)
            $table->boolean('is_active')->default(true)->after('role');
            
            // Soft delete pour archivage
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'deleted_at']);
        });
    }
};
