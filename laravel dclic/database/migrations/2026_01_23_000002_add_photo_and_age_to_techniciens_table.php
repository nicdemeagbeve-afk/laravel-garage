<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Cette migration ajoute les colonnes photo_url et age à la table techniciens
     * pour améliorer l'affichage des cartes technicien dans la sélection de dépannage
     */
    public function up(): void
    {
        Schema::table('techniciens', function (Blueprint $table) {
            $table->string('photo_url')->nullable()->after('specialite');
            $table->integer('age')->nullable()->after('photo_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('techniciens', function (Blueprint $table) {
            $table->dropColumn(['photo_url', 'age']);
        });
    }
};
