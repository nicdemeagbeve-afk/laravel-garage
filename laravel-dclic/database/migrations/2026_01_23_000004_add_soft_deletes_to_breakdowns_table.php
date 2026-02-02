<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Ajoute soft delete à la table Breakdown
     */
    public function up(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
