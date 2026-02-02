<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Cette migration crée la table 'breakdowns' pour enregistrer les déclarations de pannes.
     * Structure :
     * - id : Identifiant unique
     * - user_id : Référence à l'utilisateur qui déclare la panne
     * - vehicule_id : Référence au véhicule concerné
     * - technicien_id : Référence au technicien choisi (optionnel)
     * - description : Description détaillée du problème
     * - onsite_assistance : Boolean pour savoir si dépannage sur place requis
     * - latitude/longitude : Coordonnées GPS si dépannage sur place
     * - status : État de la déclaration (pending, in_progress, resolved)
     * - timestamps : Dates de création et modification
     */
    public function up(): void
    {
        Schema::create('breakdowns', function (Blueprint $table) {
            $table->id();
            
            // Clés étrangères
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->unsignedBigInteger('vehicule_id');
            $table->unsignedBigInteger('technicien_id')->nullable();
            
            // Contenu de la déclaration
            $table->text('description');
            
            // Options de dépannage
            $table->boolean('onsite_assistance')->default(false);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // État de la déclaration
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'cancelled'])->default('pending');
            
            // Timestamps
            $table->timestamps();
            
            // Clés étrangères additionnelles
            $table->foreign('vehicule_id')
                ->references('id')
                ->on('vehicules')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->foreign('technicien_id')
                ->references('id')
                ->on('techniciens')
                ->onUpdate('cascade')
                ->onDelete('set null');
            
            // Index pour les recherches rapides
            $table->index('user_id');
            $table->index('vehicule_id');
            $table->index('technicien_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakdowns');
    }
};
