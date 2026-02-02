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
        // Ajouter les colonnes téléphone et localisation à la table breakdowns
        if (!Schema::hasColumn('breakdowns', 'phone')) {
            Schema::table('breakdowns', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('status');
                $table->string('location')->nullable()->after('phone');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {
            if (Schema::hasColumn('breakdowns', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('breakdowns', 'location')) {
                $table->dropColumn('location');
            }
        });
    }
};
