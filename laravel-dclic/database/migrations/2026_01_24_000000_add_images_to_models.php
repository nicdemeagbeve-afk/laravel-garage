<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ajouter colonne image aux véhicules
        if (!Schema::hasColumn('vehicules', 'image')) {
            Schema::table('vehicules', function (Blueprint $table) {
                $table->string('image')->nullable()->after('boite');
            });
        }

        // Ajouter colonne image aux réparations
        if (!Schema::hasColumn('reparations', 'image')) {
            Schema::table('reparations', function (Blueprint $table) {
                $table->string('image')->nullable()->after('objet_reparation');
            });
        }

        // La colonne 'images' existe déjà dans services (skip)
        
        // Ajouter colonne image aux techniciens
        if (!Schema::hasColumn('techniciens', 'image')) {
            Schema::table('techniciens', function (Blueprint $table) {
                $table->string('image')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            if (Schema::hasColumn('vehicules', 'image')) {
                $table->dropColumn('image');
            }
        });

        Schema::table('reparations', function (Blueprint $table) {
            if (Schema::hasColumn('reparations', 'image')) {
                $table->dropColumn('image');
            }
        });

        // services a déjà 'images', on ne le supprime pas

        Schema::table('techniciens', function (Blueprint $table) {
            if (Schema::hasColumn('techniciens', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
