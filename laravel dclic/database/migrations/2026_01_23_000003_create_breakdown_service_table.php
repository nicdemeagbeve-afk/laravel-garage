<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Crée la table pivot entre Breakdown et Service
     * Permet l'association de services à une panne
     */
    public function up(): void
    {
        Schema::create('breakdown_service', function (Blueprint $table) {
            $table->id();
            
            // Clés étrangères
            $table->foreignId('breakdown_id')
                ->constrained('breakdowns')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreignId('service_id')
                ->constrained('services')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            // Données du lien
            $table->integer('quantity')->default(1);
            $table->decimal('price_override', 8, 2)->nullable()->comment('Prix spécial si différent du service');
            $table->boolean('is_approved')->default(false)->comment('Approuvé par Responsable Services');
            
            // Timestamps
            $table->timestamps();
            
            // Contrainte: Un service par panne (pas de doublons)
            $table->unique(['breakdown_id', 'service_id']);
            
            // Index pour les recherches rapides
            $table->index('breakdown_id');
            $table->index('service_id');
            $table->index('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakdown_service');
    }
};
