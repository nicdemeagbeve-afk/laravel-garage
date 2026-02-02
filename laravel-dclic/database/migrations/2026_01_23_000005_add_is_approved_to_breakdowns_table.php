<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Ajoute is_approved à Breakdown pour validation technique
     */
    public function up(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('status')->comment('Approuvé techniquement par Responsable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
};
